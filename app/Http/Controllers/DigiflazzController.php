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
        Log::info('Request: ', $request->all());
        $secret = 'sanspaysecret'; // Ganti dengan secret dari DigiFlazz  

        // Mendapatkan data mentah dari request  
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        // Logging signature untuk debugging  
        Log::info('Generated Signature: ' . $signature);

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

        // Logging payload untuk debugging  
        Log::info('Payload: ', $payload);

        // Memeriksa status transaksi  
        $status = $payload['data']['status'] ?? null;

        if ($status === 'Gagal') {
            // Status transaksi gagal, buat entri Mutation  
            $username = Auth::user()->name ?? 'System'; // Gunakan nama pengguna yang login atau 'System' jika tidak ada  
            $type = '+';
            $amount = $payload['data']['amount'] ?? 0;
            $note = 'Refund :: ' . $payload['data']['ref_id'];

            // Buat entri Mutation  
            Mutation::create([
                'username' => $username,
                'type' => $type,
                'amount' => $amount,
                'note' => $note,
            ]);

            // Increment saldo user  
            $user = User::where('name', $username)->first();
            $user->increment('saldo', $amount);

            return response()->json(['success' => true, 'message' => 'Refund successfully'], 200);
        }

        // Jika status transaksi bukan 'Gagal', lakukan proses lainnya jika diperlukan  
        Log::info('Transaction status is not "Gagal": ' . $status);

        return response()->json(['success' => true, 'message' => 'Transaction processed'], 200);
    }
}
