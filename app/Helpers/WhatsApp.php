<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsApp
{
    public static function sendMessage($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => "Bearer " . configWeb('wa_token')->value // Ganti dengan token API Anda
        ])->post(configWeb('wa_url')->value, [
            'to' => $target,
            'type' => 'text',
            'text' => [
                'body' => $message
            ],
        ]);


        $responseData = $response->json();
        // Cek apakah ada "Status" di payload JSON dan pastikan bernilai true
        if (!isset($responseData['code']) || $responseData['code'] !== 200) {
            return [
                'success' => false,
                'message' => 'Pesan gagal dikirim'
            ];
            Log::error('WhatsApp Error: ' . json_encode($responseData));
        }

        return [
            'success' => true,
            'response' => $response->json()
        ];
    }
}
