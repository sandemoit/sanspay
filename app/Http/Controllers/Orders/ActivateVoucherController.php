<?php

namespace App\Http\Controllers\Orders;

use App\Helpers\DigiFlazz;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Mutation;
use App\Models\Point;
use App\Models\ProductPpob;
use App\Models\TrxPpob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ActivateVoucherController extends Controller
{
    protected string $baseUrl;
    protected string $username;
    protected string $key;

    public function __construct()
    {
        $this->baseUrl = 'https://api.digiflazz.com/v1';
        $this->username = DigiFlazz::ProvDigi()->username;
        $this->key = config('app.env') === 'local' ? DigiFlazz::ProvDigi()->development_api_key : DigiFlazz::ProvDigi()->product_api_key;
    }

    public function index()
    {
        $title = 'Aktivasi Voucher';

        // Ambil kategori dengan real 'Aktivasi Voucher'
        $categories = Category::where('real', 'Aktivasi Voucher')
            ->distinct()
            ->get(['brand', 'name']);


        return view('users.orders.activate-voucher', compact('title', 'categories'));
    }

    public function priceActivateVoucher(Request $request)
    {
        $categoryBrand = $request->categoryBrand;

        if (!$categoryBrand) {
            return response()->json(['success' => false, 'message' => 'Kategori harus diisi.']);
        }

        $cacheKey = "products_{$categoryBrand}";

        // Ambil produk dengan relasi ke category
        $products = ProductPpob::with('category')
            ->where('brand', $categoryBrand)
            ->where('type', 'aktivasi-voucher')
            ->orderBy('price', 'asc')
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan.']);
        }

        // Grupkan produk berdasarkan type pada category
        $groupedProducts = $products->groupBy('label');

        // Sort group agar label "Umum" selalu di atas
        $sortedGroupedProducts = $groupedProducts->sortBy(function ($group, $label) {
            return $label === 'Umum' ? 0 : 1; // Prioritaskan "Umum"
        });

        // Render data produk dengan token
        $service = view('layouts.list-price', compact('sortedGroupedProducts'))->render();

        return response()->json(['success' => true, 'service' => $service]);
    }

    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        $ref_id = substr(str_shuffle('0123456789'), 0, 12);

        if (config('app.env') === 'local') {
            // Mode development/local
            $prepaidData = $this->makeRequest('/transaction', [
                'username' => $this->username,
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001230',
                'ref_id' => 'test1',
                'testing' => true,
                'sign' => $this->generateSignature('test1'),
            ]);
        } else {
            // Mode production
            $prepaidData = $this->makeRequest('/transaction', [
                'username' => $this->username,
                'buyer_sku_code' => $request->code,
                'customer_no' => $request->target,
                'ref_id' => $ref_id,
                'sign' => $this->generateSignature($ref_id),
            ]);
        }


        // Validasi respons dari API
        if (!isset($prepaidData['data'])) {
            Log::info('Failed to retrieve prepaid data: ' . json_encode($prepaidData));
        }

        // Ambil data user yang login
        $user = Auth::user();
        $storedPin = $user->pin;

        // Ambil data produk
        $product = ProductPpob::where('code', $request->code)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ]);
        }

        // Tentukan harga berdasarkan role user
        $price = match ($user->role) {
            'admin' => $product->price,
            'mitra' => $product->mitra_price,
            'customer' => $product->cust_price,
        };

        // Validasi token
        $serverToken = hash_hmac('sha256', $product->code . $price, env('APP_KEY'));
        if ($serverToken !== $request->token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid. Request ini ditolak.',
                'token' => $serverToken,
            ]);
        }

        // Hitung point
        $point = $this->calculatePoint($user->point);

        if (Hash::check($request->pin, $storedPin)) {
            // Cek apakah saldo cukup
            if ($user->saldo < $price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo Anda tidak mencukupi untuk transaksi ini.',
                ]);
            }

            // Proses transaksi berdasarkan RC
            $rc = $prepaidData['data']['rc']; // Ambil RC dari data transaksi

            switch ($rc) {
                case '00': // Transaksi Sukses
                case '03': // Transaksi Pending
                    // Kurangi saldo dan mutasi
                    User::updateOrCreate(['id' => $user->id], ['saldo' => $user->saldo - $price, 'point' => $point]);
                    Mutation::create([
                        'username' => $user->name,
                        'type' => '-',
                        'amount' => strip_tags($price),
                        'note' => "Trx :: $ref_id",
                    ]);

                    // Simpan data transaksi ke database
                    TrxPpob::create([
                        'id_order' => $ref_id,
                        'user_id' => $user->id,
                        'code' => $product->code,
                        'name' => $product->name,
                        'data' => $request->target,
                        'price' => strip_tags($price),
                        'profit' => strip_tags($price - $product->price),
                        'point' => ($user->role === 'customer') ? 0 : $point,
                        'refund' => '0',
                        'note' => $prepaidData['data']['message'] ?? 'Unknown',
                        'status' => $prepaidData['data']['status'] ?? 'Unknown',
                        'from' => request()->ip(),
                        'type' => 'prepaid',
                        'sn' => $prepaidData['data']['sn'] ?? '',
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Transaksi berhasil diproses.',
                        'id_order' => $ref_id
                    ]);

                case '01': // Timeout
                case '02': // Transaksi Gagal
                    return response()->json([
                        'success' => false,
                        'message' => 'Transaksi tidak berhasil. Status: ' . $prepaidData['data']['message'],
                    ]);

                default: // RC lainnya
                    return response()->json([
                        'success' => false,
                        'message' => 'Kesalahan server: ' . $prepaidData['data']['message'],
                    ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah.',
            ]);
        }
    }

    /**
     * Hitung point berdasarkan konfigurasi.
     */
    private function calculatePoint($userPoint)
    {
        $initPoint = Point::whereIn('key', ['type', 'amount'])->get()->keyBy('key');

        if ($initPoint->get('type')->value === '+') {
            return $userPoint + $initPoint->get('amount')->value;
        } elseif ($initPoint->get('type')->value === '%') {
            return ($userPoint / 100) * $initPoint->get('amount')->value;
        }

        return 0;
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
}
