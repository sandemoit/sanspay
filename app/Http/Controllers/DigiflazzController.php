<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use App\Models\Referrals;
use App\Models\TrxPpob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DigiflazzController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $this->validateSignature($request);
            $payload = $this->getValidPayload($request);

            $data = $payload['data'] ?? [];
            $status = $data['status'] ?? 'Unknown';
            $ref_id = $data['ref_id'] ?? null;
            $amount = $data['price'] ?? 0;
            $message = $data['message'] ?? null;
            $sn = $data['sn'] ?? '-';

            if (!$ref_id) {
                throw new \Exception('Ref ID missing in webhook payload');
            }

            $trxPpob = $this->getTransaction($ref_id);
            $username = $trxPpob->user->name;

            match ($status) {
                'Gagal' => $this->handleFailedTransaction($trxPpob, $username, $amount, $ref_id, $message, $sn),
                'Sukses' => $this->handleSuccessTransaction($trxPpob, $username, $message, $sn),
                default => Log::info("Unhandled transaction status: $status")
            };
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json(['success' => true]);
    }

    private function validateSignature(Request $request): void
    {
        $secret = config('services.digiflazz_secret');
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        if ($request->header('X-Hub-Signature') !== 'sha1=' . $signature) {
            throw new \Exception('Invalid Webhook Signature');
        }
    }

    private function getValidPayload(Request $request): array
    {
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON Payload: ' . json_last_error_msg());
        }

        return $payload;
    }

    private function getTransaction(string $ref_id): TrxPpob
    {
        $trxPpob = TrxPpob::with('user')->where('id_order', $ref_id)->first();

        if (!$trxPpob || !$trxPpob->user) {
            throw new \Exception('Transaction or User not found for Ref ID: ' . $ref_id);
        }

        return $trxPpob;
    }

    private function handleFailedTransaction(TrxPpob $trxPpob, string $username, float $amount, string $ref_id, string $message, string $sn): void
    {
        $note = "Refund :: $ref_id";

        $trxPpob->user->increment('saldo', $amount);

        Mutation::create([
            'username' => $username,
            'type' => '+',
            'amount' => $amount,
            'note' => $note,
        ]);

        $trxPpob->update([
            'status' => 'Gagal',
            'note' => $message,
            'sn' => $sn,
        ]);
    }

    private function handleSuccessTransaction(TrxPpob $trxPpob, string $username, string $message, string $sn): void
    {
        Referrals::where('username_to', $username)
            ->where('status', 'inactive')
            ->each(function ($referral) {
                $referral->update(['status' => 'active']);
                User::where('name', $referral->username_from)->increment('point', $referral->point);
            });

        $trxPpob->update([
            'status' => 'Sukses',
            'note' => $message,
            'sn' => $sn,
        ]);
    }
}
