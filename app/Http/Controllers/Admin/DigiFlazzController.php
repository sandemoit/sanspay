<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DigiFlazz;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiFlazzController extends Controller
{
    protected string $baseUrl;
    protected string $username;
    protected string $key;

    public function __construct()
    {
        $this->baseUrl = 'https://api.digiflazz.com/v1';
        $this->username = DigiFlazz::ProvDigi()->username;
        $this->key = DigiFlazz::ProvDigi()->development_api_key;
    }

    public function getPriceList(): array
    {
        try {
            // Get prepaid data
            $prepaidData = $this->makeRequest('/price-list', [
                'cmd' => 'prepaid',
                'username' => $this->username,
                'sign' => $this->generateSignature('pricelist')
            ]);

            // Get postpaid data
            // $postpaidData = $this->makeRequest('/price-list', [
            //     'cmd' => 'pasca',
            //     'username' => $this->username,
            //     'sign' => $this->generateSignature('pricelist')
            // ]);

            // Check for errors in prepaid data
            if (!is_array($prepaidData) || !isset($prepaidData['data'])) {
                return [
                    'result' => false,
                    'data' => null,
                    'message' => 'Error on prepaid: ' . ($prepaidData['message'] ?? 'Unknown error')
                ];
            }

            // Check for errors in postpaid data
            // if (!is_array($postpaidData) || $postpaidData['data'] === null) {
            //     Log::warning('Postpaid data retrieval failed or response structure is invalid.', [
            //         'postpaidData' => $postpaidData
            //     ]);

            //     // You can choose to continue without postpaid data or return an error
            //     return [
            //         'result' => false,
            //         'data' => null,
            //         'message' => 'Error on postpaid: No data available or request denied by API.'
            //     ];
            // }

            // Process the data if all responses are valid
            $processedData = array_merge(
                $this->processData($prepaidData['data'], 'Prepaid'),
                // $this->processData($postpaidData['data'], 'Postpaid')
            );

            return [
                'result' => true,
                'data' => $processedData,
                'message' => 'Service Data successfully obtained.'
            ];
        } catch (\Exception $e) {
            Log::error('Error in getPriceList: ' . $e->getMessage());
            return [
                'result' => false,
                'data' => null,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    protected function makeRequest(string $endpoint, array $data): ?array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . $endpoint, $data);

        $responseData = $response->json();

        return $responseData ?? null;
    }


    protected function generateSignature(string $refId): string
    {
        return md5($this->username . $this->key . $refId);
    }

    protected function processData(array $data, string $prepost): array
    {
        $output = [];
        foreach ($data as $item) {
            $output[] = [
                'brand' => $item['brand'],
                'category' => $item['brand'],
                'otype' => $item['category'],
                'type' => $this->filterType($item['category'], $item['product_name']),
                'name' => $this->space($item['product_name']),
                'note' => $this->space($item['desc'] ?? ''),
                'code' => str_replace(['&', '*'], '', $item['buyer_sku_code']),
                'price' => $item['price'],
                'status' => $this->stock($item['buyer_product_status']),
                'prepost' => strtolower($prepost),
                'label' => $item['type']
            ];
        }
        return $output;
    }

    protected function filterType(string $x, string $z): string
    {
        $Type   = 'not-filtered';
        $type1  = ['PULSA', 'PULSA-REGULER', 'PULSA-TRANSFER'];
        $type2  = ['CHINA TOPUP', 'MALAYSIA TOPUP', 'PHILIPPINES TOPUP', 'SINGAPORE TOPUP', 'THAILAND TOPUP', 'VIETNAM TOPUP', 'PULSA-INTERNASIONAL'];
        $type3  = ['DATA', 'PAKET-INTERNET'];
        $type4  = ['PAKET SMS & TELPON', 'PAKET-TELEPON'];
        $type5  = ['PLN', 'TOKEN-PLN'];
        $type6  = ['E-MONEY', 'SALDO-EMONEY'];
        $type7  = ['VOUCHER', 'PAKET-LAINNYA'];
        $type8  = ['STREAMING', 'TV', 'STREAMING-TV'];
        $type9  = ['GAMES', 'VOUCHER-GAME'];
        $type10 = ['PASCABAYAR'];
        $type11 = ['AKTIVASI VOUCHER'];
        $type12 = ['AKTIVASI PERDANA'];
        $type13 = ['MASA AKTIF'];
        $type14 = ['PERTAGAS'];

        if (in_array(strtoupper($x), $type1)) $Type = (stristr(strtolower($z), 'transfer')) ? 'pulsa-transfer' : 'pulsa-reguler';
        if (in_array(strtoupper($x), $type2)) $Type = 'pulsa-internasional';
        if (in_array(strtoupper($x), $type3)) $Type = 'paket-internet';
        if (in_array(strtoupper($x), $type4)) $Type = 'paket-telepon';
        if (in_array(strtoupper($x), $type5)) $Type = 'token-pln';
        if (in_array(strtoupper($x), $type6)) $Type = 'saldo-emoney';
        if (in_array(strtoupper($x), $type7)) $Type = 'paket-lainnya';
        if (in_array(strtoupper($x), $type8)) $Type = 'streaming-tv';
        if (in_array(strtoupper($x), $type9)) $Type = 'voucher-game';
        if (in_array(strtoupper($x), $type10)) $Type = 'pascabayar';
        if (in_array(strtoupper($x), $type11)) $Type = 'aktivasi-voucher';
        if (in_array(strtoupper($x), $type12)) $Type = 'aktivasi-perdana';
        if (in_array(strtoupper($x), $type13)) $Type = 'masa-aktif';
        if (in_array(strtoupper($x), $type14)) $Type = 'pertagas';
        return $Type;
    }

    protected function space(string $text): string
    {
        // Implementasi untuk menghapus atau menyesuaikan spasi
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    protected function stock($x)
    {
        $available = ['available', 'active', 'normal'];
        return in_array(strtolower($x), $available) ? 'available' : 'empty';
    }
}
