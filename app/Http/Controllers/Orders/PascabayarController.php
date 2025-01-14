<?php

namespace App\Http\Controllers\Orders;

use App\Helpers\DigiFlazz;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Str;

class PascabayarController extends Controller
{
    protected string $baseUrl;
    protected string $username;
    protected string $key;

    public function __construct()
    {
        $this->baseUrl = 'https://api.digiflazz.com/v1';
        $this->username = DigiFlazz::ProvDigi()->username;
        $this->key = DigiFlazz::ProvDigi()->product_api_key;
    }

    public function index($brand)
    {
        // Ubah slug menjadi format "normal" (contoh: 'bpjs-kesehatan' -> 'BPJS KESEHATAN')
        $brandName = $brand === 'e-money' ? 'E-MONEY' : Str::of($brand)->replace('-', ' ')->upper();

        $title = $brandName;

        // Ambil kategori dengan type 'voucher-game'
        $categories = ProductPpob::where('brand', $brandName)->select('id', 'name', 'code', 'brand', 'price', 'mitra_price', 'cust_price', 'healthy', 'discount', 'status', 'note')->get();

        // Jika data ditemukan
        if (!$categories) {
            return abort(404);
        }

        return view('users.orders.pascabayar', compact('title', 'categories'));
    }

    public function checkBill(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
            'customer_no' => 'required',
        ]);

        $ref_id = substr(str_shuffle('0123456789'), 0, 12);

        $prepaidData = $this->makeRequest('/transaction', [
            'commands' => 'inq-pasca',
            'username' => $this->username,
            'buyer_sku_code' => $validated['code'],
            'customer_no' => $validated['customer_no'],
            'ref_id' => $ref_id,
            'sign' => $this->generateSignature($ref_id),
        ]);

        try {
            // Proses transaksi berdasarkan RC  
            $rc = $prepaidData['data']['rc']; // Ambil RC dari data transaksi  

            switch ($rc) {
                case '00': // Transaksi Sukses  
                case '03': // Transaksi Pending  
                    $htmlContent = $this->generateHtmlContent($prepaidData['data']);
                    return response()->json([
                        'success' => true,
                        'htmlContent' => $htmlContent,
                    ]);

                case '01': // Timeout  
                case '02': // Transaksi Gagal  
                    return response()->json([
                        'success' => false,
                        'rc' => $rc,
                        'message' => 'Transaksi tidak berhasil. Status: ' . $prepaidData['data']['message'],
                    ]);

                default: // RC lainnya  
                    return response()->json([
                        'success' => false,
                        'rc' => $rc,
                        'message' => 'Kesalahan server: ' . $prepaidData['data']['message'],
                    ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function generateHtmlContent(array $data): string
    {
        // Inisialisasi variabel untuk menampung detail tagihan  
        $detailTagihan = '';
        $totalTagihan = 0;
        $totalAdmin = 0;
        $totalDenda = 0;

        // Loop melalui setiap detail tagihan  
        foreach ($data['desc']['detail'] as $detail) {
            $detailTagihan .= "  
            <hr>  
            <p>Periode Tagihan: {$detail['periode']}</p>  
            <b>  
                <p>Nilai Tagihan: Rp" . number_format($detail['nilai_tagihan'], 0, ',', '.') . "</p>  
                <p>Biaya Admin: Rp" . number_format($detail['admin'], 0, ',', '.') . "</p>  
                <p>Biaya Denda: Rp" . number_format($detail['denda'] ?? 0, 0, ',', '.') . "</p>  
            </b>  
        ";
            $totalTagihan += (int) $detail['nilai_tagihan'];
            $totalAdmin += (int) $detail['admin'];
            $totalDenda += (int) ($detail['denda'] ?? 0);
        }

        // Buat HTML content  
        $htmlContent = "
        <p><b>ID Pelanggan: {$data['customer_no']}</b></p>
        <p>Nama Pelanggan: {$data['customer_name']}</p>
        " . ($data['buyer_sku_code'] === 'PLNPSC' ? "<p>Tarif/Daya: {$data['desc']['tarif']}/{$data['desc']['daya']}</p>" : '') . "
        {$detailTagihan}
        <hr>
        <p><b>Total Pembayaran: Rp" . number_format($data['selling_price'], 0, ',', '.') . "</b></p>
        <div class=\"form-group mb-3\">
            <label for=\"transaction-pin\">PIN Transaksi Anda <span class=\"text-danger\">*</span></label>
            <input type=\"password\" class=\"form-control\" id=\"transaction-pin\" name=\"transaction-pin\" required>
            <input type=\"hidden\" id=\"code\" data-code=\"{$data['buyer_sku_code']}\">
        </div>
    ";

        return $htmlContent;
    }

    public function prosesTransaksi(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        $ref_id = substr(str_shuffle('0123456789'), 0, 12);

        $prepaidData = $this->makeRequest('/transaction', [
            'commands' => "pay-pasca",
            'username' => $this->username,
            'buyer_sku_code' => $request->code,
            'customer_no' => $request->target,
            'ref_id' => $ref_id,
            'sign' => $this->generateSignature($ref_id),
        ]);

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

        // Hitung point
        $point = $this->calculatePoint($user->point);

        if (Hash::check($request->pin, $storedPin)) {
            // Cek apakah saldo cukup
            if ($user->saldo < $prepaidData['data']['selling_price']) {
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
                    User::where('id', $user->id)->update(['saldo' => $user->saldo - $prepaidData['data']['selling_price'], 'point' => $point]);
                    Mutation::create([
                        'username' => $user->name,
                        'type' => '-',
                        'amount' => strip_tags($prepaidData['data']['selling_price']),
                        'note' => "Trx :: $ref_id",
                    ]);

                    $resultDesc = $prepaidData['data']['desc']['detail'][0];
                    $note = $resultDesc['periode'] . '/' . $resultDesc['nilai_tagihan'] . '/' . $resultDesc['admin'];

                    // Simpan data transaksi ke database
                    TrxPpob::create([
                        'user_id' => $user->id,
                        'id_order' => $ref_id,
                        'code' => $product->code,
                        'name' => $product->name,
                        'data' => $request->target,
                        'price' => strip_tags($prepaidData['data']['selling_price']),
                        'profit' => strip_tags($prepaidData['data']['selling_price'] - $prepaidData['data']['price']),
                        'point' => ($user->role === 'customer') ? 0 : $point,
                        'refund' => '0',
                        'note' => $prepaidData['data']['message'] ?? 'Unknown',
                        'status' => $prepaidData['data']['status'] ?? 'Unknown',
                        'from' => request()->ip(),
                        'type' => 'postpaid',
                        'sn' => $note ?? '',
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Transaksi berhasil diproses.',
                    ]);

                case '01': // Timeout
                case '02': // Transaksi Gagal
                    return response()->json([
                        'success' => false,
                        'rc' => $rc,
                        'message' => 'Transaksi tidak berhasil. Status: ' . $prepaidData['data']['message'],
                    ]);

                default: // RC lainnya
                    return response()->json([
                        'success' => false,
                        'rc' => $rc,
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
}
