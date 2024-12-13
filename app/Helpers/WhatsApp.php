<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsApp
{
    public static function sendMessage($target, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => configWeb('wa_token')->value // Ganti dengan token API Anda
        ])->post(configWeb('wa_url')->value, [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        $responseData = $response->json();

        // Cek apakah ada "Status" di payload JSON dan pastikan bernilai true
        if (!isset($responseData['status']) || $responseData['status'] === false) {
            return [
                'success' => false,
                'message' => self::handleError($responseData)
            ];
        }

        return [
            'success' => true,
            'response' => $response->json()
        ];
    }

    private static function handleError($errorResponse)
    {
        $reason = $errorResponse['reason'] ?? 'unknown error';

        switch ($reason) {
            case 'invalid token':
                return 'Invalid token. Please check your API key.';
            case 'devices must belong to an account':
                return 'Your device is not linked to this account.';
            case 'input invalid':
                return 'One or more inputs are invalid.';
            case 'url invalid':
                return 'Provided URL is invalid.';
            case 'url unreachable':
                return 'Provided URL is not responding.';
            case 'file format not supported':
                return 'Unsupported file format.';
            case 'file size must under 4MB':
                return 'File size exceeds the 4MB limit.';
            case 'target invalid':
                return 'The target number is invalid.';
            case 'JSON format invalid':
                return 'Invalid JSON format. Please verify your payload.';
            case 'insufficient quota':
                return 'Insufficient quota for sending messages.';
            default:
                return 'An unknown error occurred.';
        }
    }
}
