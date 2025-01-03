<?php

namespace App\Http\Controllers;

use App\Helpers\DigiFlazz;
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

class OrderController extends Controller
{
    private const VALID_TYPES = ['pulsa-reguler', 'pulsa-transfer', 'paket-internet', 'paket-telepon'];
    private const CACHE_DURATION = 300; // 5 minutes
    private const MAX_ATTEMPTS = 5;
    private const THROTTLE_DURATION = 300; // 5 minutes

    protected string $baseUrl;
    protected string $username;
    protected string $key;

    public function __construct()
    {
        $this->baseUrl = 'https://api.digiflazz.com/v1';
        $this->username = DigiFlazz::ProvDigi()->username;
        $this->key = DigiFlazz::ProvDigi()->development_api_key;
    }

    public function newOrder(Request $request)
    {
        $segment = $request->segment(2);

        if (!in_array($segment, self::VALID_TYPES, true)) {
            return redirect()->route('home')->with('error', 'Invalid order type');
        }

        $prefixes = [
            'pulsa-reguler' => 'Pulsa Reguler',
            'paket-telepon' => 'Paket SMS & Telepon',
            'paket-internet' => 'Paket Internet',
            'pulsa-transfer' => 'Pulsa Transfer',
        ];

        return view('users.orders.new', [
            'title' => $prefixes[$segment] ?? 'Default Title',
            'type' => $segment
        ]);
    }

    public function showOrderForm($type)
    {
        $validTypes = ['pulsa-reguler', 'pulsa-transfer', 'paket-internet', 'paket-telepon'];

        if (!in_array($type, $validTypes)) {
            return redirect()->route('home');
        }

        $category = Category::where('type', $type)->first();
        $title = $category->name ?? ucfirst(str_replace('-', ' ', $type));

        return view('ppob.order', compact('title', 'type'));
    }

    public function checkProvider(Request $request)
    {
        // Log request details
        Log::info('PPOB Request:', $request->all());

        $phone = normalizePhoneNumber($request->input('phone'));
        $type = $request->input('type');

        // Validate input
        if (empty($phone) || empty($type)) {
            return response()->json([
                'success' => false,
                'service' => '<div class="alert alert-danger">Data tidak lengkap</div>',
                'class' => ''
            ]);
        }

        $provider = detectProvider($phone);

        // Log provider detection
        Log::info('Provider Detection:', [
            'phone' => $phone,
            'provider' => $provider
        ]);

        if (!$provider) {
            return response()->json([
                'success' => false,
                'service' => '<div class="alert alert-danger">Nomor tidak valid atau provider tidak terdeteksi</div>',
                'class' => ''
            ]);
        }

        try {
            $cacheKey = "products_{$type}_{$provider}";

            $products = cache()->remember($cacheKey, now()->addMinutes(self::CACHE_DURATION), function () use ($type, $provider) {
                return ProductPpob::where([
                    'type' => $type,
                    'brand' => $provider,
                    'provider' => 'DigiFlazz',
                    'status' => 'available'
                ])->orderBy('price', 'asc')
                    ->get();
            });

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'service' => '<div class="alert alert-danger">Produk tidak tersedia untuk provider ini</div>',
                    'class' => 'sc-' . strtolower($provider),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
            // dd($products[0]->price);
            $html = view('layouts.sim-cards', compact('products'))->render();

            return response()->json([
                'success' => true,
                'service' => $html,
                'class' => 'sc-' . strtolower($provider),
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'service' => '<div class="alert alert-danger">Terjadi kesalahan sistem</div>',
                'class' => '',
                'timestamp' => now()->toDateTimeString(),
            ]);
        }
    }

    public function confirmOrder($code)
    {
        $product = ProductPpob::where('code', $code)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ]);
        }

        $userRole = Auth::user()->role; // Assuming user roles are 'admin', 'mitra', 'customer'
        $priceField = match ($userRole) {
            'admin' => 'price',
            'mitra' => 'mitra_price',
            'customer' => 'cust_price',
        };

        $userSaldo = Auth::user()->saldo;

        if ($product->$priceField > $userSaldo) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak cukup untuk melakukan transaksi. Rp' . nominal($userSaldo),
            ]);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
            'price' => nominal($product->$priceField),
            'userSaldo' => nominal($userSaldo),
        ]);
    }

    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        $ref_id = substr(str_shuffle('0123456789'), 0, 12);

        $prepaidData = $this->makeRequest('/transaction', [
            'username' => $this->username,
            'buyer_sku_code' => $request->code,
            'customer_no' => $request->target,
            'ref_id' => $ref_id,
            'sign' => $this->generateSignature($ref_id),
        ]);

        // Validasi respons dari API
        if (!isset($prepaidData['data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses transaksi. Cek koneksi atau API Anda.',
            ]);
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
                'point' => ($user->role === 'customer') ? 0 : $point,
                'refund' => '0',
                'note' => 'Transaksi Berhasil Dibuat',
                'status' => $prepaidData['data']['status'] ?? 'Unknown',
                'from' => request()->ip(),
                'type' => 'prepaid',
                'sn' => $prepaidData['data']['sn'] ?? '',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses.',
            ]);
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

    public function historyTransaksi()
    {
        $title = 'History Transaksi';
        return view('users.orders.history', compact('title'));
    }
}
