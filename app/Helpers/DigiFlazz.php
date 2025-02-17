<?php

namespace App\Helpers;

use App\Models\Provider;
use Illuminate\Support\Facades\Http;

class DigiFlazz
{
    public static function makeRequest(string $endpoint, array $data): ?array
    {
        $baseUrl = 'https://api.digiflazz.com/v1';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($baseUrl . $endpoint, $data);

        $responseData = $response->json();

        return $responseData ?? null;
    }

    public static function generateSignature(string $data1, string $data2, string $data3): string
    {
        return md5($data1 . $data2 . $data3);
    }

    public static function ProvDigi(): Provider
    {
        return Provider::where('code', 'DigiFlazz')->first();
    }
}
