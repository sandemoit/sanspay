<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DigiFlazz;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $data = [
            'digi' => Provider::where('code', 'DigiFlazz')->first(),
        ];

        return view('admin.provider', $data);
    }

    public function configurationDIgiFlazz(Request $request)
    {
        $username = $request->input('username');
        $proApiKey = $request->input('product_api_key');
        $devApiKey = $request->input('development_api_key');
        $status = $request->input('status');

        // Validasi input
        if (empty($username) || empty($proApiKey)) {
            return response()->json([
                'result' => false,
                'message' => 'Username and Product API Key are required.'
            ]);
        }

        $DigiFlazz = DigiFlazz::makeRequest('/cek-saldo', [
            'cmd' => 'deposit',
            'username' => $username,
            'sign' => DigiFlazz::generateSignature($username, $proApiKey, 'depo')
        ]);

        // Cek apakah deposit tersedia di response
        if (!isset($DigiFlazz['data']['deposit'])) {
            return response()->json([
                'result' => false,
                'message' => 'Failed to retrieve balance: ' . ($DigiFlazz['message'] ?? 'Unknown error')
            ]);
        }

        // Ambil saldo dari response API
        $saldo = $DigiFlazz['data']['deposit'] ?? 0;

        // Simpan data ke tabel Provider
        Provider::updateOrCreate(
            ['code' => 'DigiFlazz'],  // field unik untuk update atau create
            [
                'username' => $username,
                'product_api_key' => $proApiKey,
                'development_api_key' => $devApiKey,
                'saldo' => $saldo,
                'status' => $status
            ]
        );

        // Response sukses
        return response()->json([
            'result' => true,
            'message' => 'Configuration updated successfully'
        ]);
    }
}
