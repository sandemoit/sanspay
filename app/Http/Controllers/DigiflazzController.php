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
        // Mendapatkan data mentah dari request  
        $secret = config('services.digiflazz_secret');
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        // Validasi tanda tangan (signature)  
        if ($request->header('X-Hub-Signature') !== 'sha1=' . $signature) {
            // Tanda tangan tidak valid  
            Log::warning('Invalid Webhook Signature');
        }

        // Mendapatkan payload dari request  
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Payload tidak valid JSON  
            Log::warning('Invalid JSON Payload: ' . json_last_error_msg());
        }

        $data = $payload['data'] ?? [];
        $status = $data['status'] ?? 'Unknown';
        $ref_id = $data['ref_id'] ?? null;
        $amount = $data['price'] ?? 0;
        $message = $data['message'] ?? null;
        $sn = $data['sn'] ?? '-';

        // Validasi ID order
        if (!$ref_id) {
            Log::error('Ref ID missing in webhook payload');
        }

        // Ambil data transaksi
        $trxPpob = TrxPpob::with('user')->where(
            'id_order',
            $ref_id
        )->first();

        if (!$trxPpob || !$trxPpob->user) {
            Log::error('Transaction or User not found for Ref ID: ' . $ref_id);
        }

        $username = $trxPpob->user->name;

        // Proses sesuai status
        if ($status === 'Gagal') {
            $note = 'Refund :: ' . $ref_id;

            // Update saldo
            $user = $trxPpob->user;
            $user->increment('saldo', $amount);

            // Buat mutasi
            Mutation::create([
                'username' => $username,
                'type' => '+',
                'amount' => $amount,
                'note' => $note,
            ]);

            // Update status transaksi
            $trxPpob->update([
                'status' => $status,
                'note' => $message,
                'sn' => $sn,
            ]);
        } elseif ($status === 'Sukses') {
            // Cari referral yang masih inactive dan ubah statusnya
            // Serta tambahkan poin ke user yang mereferensi
            Referrals::where('username_to', $username)
                ->where('status', 'inactive')
                ->each(function ($referral) {
                    $referral->update(['status' => 'active']);
                    User::where('name', $referral->username_from)->increment('point', $referral->point);
                });

            // Update status transaksi
            $trxPpob->update([
                'status' => $status,
                'note' => $message,
                'sn' => $sn,
            ]);
        } else {
            Log::info('Unhandled transaction status: ' . $status);
        }
    }
}
