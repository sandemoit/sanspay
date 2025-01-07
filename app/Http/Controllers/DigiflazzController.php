<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use App\Models\TrxPpob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return response()->json(['success' => false, 'message' => 'Invalid Webhook Signature'], 400);
        }

        // Mendapatkan jenis event dari header  
        $eventType = $request->header('X-Digiflazz-Event');

        if ($eventType !== 'update') {
            // Jenis event tidak dikenali  
            Log::warning('Unknown Event Type: ' . $eventType);
            return response()->json(['success' => false, 'message' => 'Unknown Event Type'], 400);
        }

        // Mendapatkan payload dari request  
        $payload = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Payload tidak valid JSON  
            Log::warning('Invalid JSON Payload: ' . json_last_error_msg());
            return response()->json(['success' => false, 'message' => 'Invalid JSON Payload'], 400);
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
            return response()->json(['success' => false, 'message' => 'Missing Ref ID'], 400);
        }

        // Ambil data transaksi
        $trxPpob = TrxPpob::with('user')->where(
            'id_order',
            $ref_id
        )->first();

        if (!$trxPpob || !$trxPpob->user) {
            Log::error('Transaction or User not found for Ref ID: ' . $ref_id);
            return response()->json(['success' => false, 'message' => 'Transaction or User not found'], 404);
        }

        $username = $trxPpob->user->name;

        // Proses sesuai status
        if (in_array($status, ['Gagal', 'Sukses'])) {
            $type = $status === 'Gagal' ? '+' : '-';
            $note = $status === 'Gagal' ? 'Refund :: ' . $ref_id : 'Transaction Success :: ' . $ref_id . ' :: ' . $sn;

            // Buat mutasi
            Mutation::create([
                'username' => $username,
                'type' => $type,
                'amount' => $amount,
                'note' => $note,
            ]);

            // Update saldo jika gagal
            if ($status === 'Gagal') {
                $user = $trxPpob->user;
                $user->increment('saldo', $amount);
            }

            // Update status transaksi
            $trxPpob->update([
                'status' => $status,
                'note' => $message,
                'sn' => $sn,
            ]);

            Log::info('Transaction updated successfully for Ref ID: ' . $ref_id);
        } else {
            Log::info('Unhandled transaction status: ' . $status);
        }
    }
}
