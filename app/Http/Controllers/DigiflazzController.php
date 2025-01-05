<?php

namespace App\Http\Controllers;

use App\Models\TrxPpob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DigiflazzController extends Controller
{
    public function handle(Request $request)
    {
        $secret = 'sanspaysecret'; // Ganti dengan secret dari DigiFlazz

        // Mendapatkan data mentah dari request
        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        // Logging signature untuk debugging
        Log::info('Generated Signature: ' . $signature);

        // Validasi tanda tangan (signature)
        if ($request->header('X-Hub-Signature') == 'sha1=' . $signature) {
            // Data valid, proses payload
            $eventType = $request->header('X-Digiflazz-Event');

            if ($eventType === 'update') {
                $payload = json_decode($request->getContent(), true);
                // Lakukan update data di sini
                Log::info('Update data: ', $payload);
            }

            // Lakukan proses sesuai event
            // Contoh: Simpan data transaksi atau update status transaksi
            // TrxPpob::updated();
        } else {
            // Tanda tangan tidak valid
            Log::warning('Invalid Webhook Signature');
        }
    }
}
