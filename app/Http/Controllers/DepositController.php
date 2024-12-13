<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsApp;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\DepositPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Midtrans\Snap;
use Midtrans\Config;

class DepositController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function index()
    {
        // Ambil user yang sedang login
        $userId = Auth::id();

        // Cek deposit dengan status pending berdasarkan user ID
        $pendingDeposit = Deposit::where('member_id', $userId)
            ->where('status', 'pending')
            ->first();

        // Jika ada deposit pending, redirect ke halaman invoice
        if ($pendingDeposit) {
            return redirect()->route('deposit.invoice', ['topup_id' => $pendingDeposit->topup_id]);
        }

        return view('users.deposit.index');
    }

    public function getDepositMethod()
    {
        $type = request('type'); // Mendapatkan nilai 'type' dari request

        // Daftar kode yang hanya ditampilkan jika type == 1
        $autoCodes = ['bni_va', 'bri_va', 'shopeepay', 'gopay', 'alfamart', 'indomaret', 'permata_va', 'cimb_va', 'mandiri_va', 'qris'];

        // Query untuk mendapatkan metode deposit berdasarkan kondisi
        $methods = DepositMethod::when($type == 1, function ($query) use ($autoCodes) {
            // Jika type = 1, ambil hanya metode dengan kode yang ada di dalam $autoCodes
            $query->whereIn('code', $autoCodes)->where('is_auto', 1);
        })
            ->when($type != 1, function ($query) use ($autoCodes) {
                // Jika type bukan 1, ambil metode yang tidak ada di dalam $autoCodes dan is_auto = 0
                $query->whereNotIn('code', $autoCodes)->where('is_auto', 0);
            })
            ->get();

        return response()->json($methods);
    }

    public function calculateFee(Request $request)
    {
        // Validasi input
        $request->validate([
            'nominalDeposit' => 'required|numeric',
            'methodpayment' => 'required|exists:deposit_method,code',
        ]);

        $nominalDeposit = request('nominalDeposit');
        $methodPayment = DepositMethod::where('code', request('methodpayment'))->first();

        // Hitung fee berdasarkan metode pembayaran
        if ($methodPayment) {
            $fee = $methodPayment->fee; // Ambil fee dari DepositMethod
            $feeType = $methodPayment->xfee; // Tipe fee: 'mines' atau 'percent'

            // Inisialisasi $calculatedFee sebagai 0 agar tidak undefined

            // Hitung fee berdasarkan tipe fee
            if ($feeType === '-') {
                // Jika fee berupa jumlah tetap
                $calculatedFee = $fee;
            } elseif ($feeType === '%') {
                // Jika fee berupa persentase
                $calculatedFee = ($nominalDeposit * $fee) / 100;
            }

            // Total transfer dan saldo yang diterima
            $totalTransfer = $nominalDeposit + $calculatedFee;
            $saldoReceived = $totalTransfer - $calculatedFee;

            return response()->json([
                'fee' => $feeType === '%' ? $fee . '%' : nominal($fee),
                'total_transfer' => nominal($totalTransfer),
                'saldo_received' => nominal($saldoReceived),
            ]);
        }

        return response()->json(['error' => 'Method payment not found.'], 404);
    }

    public function store(Request $request)
    {
        // Ambil data metode pembayaran
        $method = DepositMethod::where('code', $request->methodpayment)->first();
        $member_id = Auth::user()->id;
        $user = User::where('id', $member_id)->first();

        // Pastikan metode ditemukan
        if (!$method) {
            return back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'typepayment' => 'required|in:0,1',
            'methodpayment' => 'required',
            'nominalDeposit' => 'required|numeric|min:' . $method->minimum,
        ]);

        if ($validator->fails()) {
            $messages = $validator->messages()->all();
            return back()->with('error', implode('. ', $messages), 422);
        }

        $total_transfer = (int)str_replace('.', '', $request->total_transfer) + rand(100, 999);
        $amount = (int)str_replace('.', '', $request->saldo_recived);

        // Data deposit untuk disimpan
        $deposit = Deposit::create([
            'topup_id' => 'INV' . time(),  // Contoh ID topup unik
            'member_id' => $member_id,
            'payment_method' => $request->methodpayment,
            'amount' => $amount,
            'total_transfer' => $total_transfer,
            'status' => 'pending',
        ]);

        $amount = nominal($amount);
        $target = "$user->number|$user->name|$amount|$method->name|Pending|";
        $sendWa = WhatsApp::sendMessage($target, formatNotif('deposit_wa')->value);

        if ($sendWa['success'] == false) {
            return redirect()->back()->with('error', __($sendWa['message']));
        }

        try {
            if ($request->typepayment == 1) {
                // Persiapkan data Midtrans
                $params = [
                    'transaction_details' => [
                        'order_id' => $deposit->topup_id,
                        'gross_amount' => $deposit->total_transfer,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->number,
                    ],
                    'callbacks' => [
                        'finish' => route('deposit.invoice', ['topup_id' => $deposit->topup_id]),
                    ],
                ];

                // Konfigurasi parameter berdasarkan methodpayment
                switch ($request->methodpayment) {
                    case 'bni_va':
                    case 'bri_va':
                    case 'mandiri_va':
                    case 'cimb_va':
                    case 'permata_va':
                    case 'bca_va':
                        $params['enabled_payments'] = ["$request->methodpayment"];
                        $params['payment_type'] = "bank_transfer";
                        break;
                    case 'gopay':
                        $params['enabled_payments'] = ["gopay"];
                        $params['payment_type'] = "gopay";
                        break;
                    case 'shopeepay':
                        $params['enabled_payments'] = ["shopeepay"];
                        $params['payment_type'] = "shopeepay";
                        break;
                    case 'qris':
                        $params['enabled_payments'] = ["other_qris"];
                        $params['payment_type'] = "other_qris";
                        // $this->qrisMethod($deposit->total_transfer, $deposit->topup_id);
                        break;

                    default:
                        return back()->with('error', 'Metode pembayaran tidak valid.');
                }

                // Request snap token dari Midtrans
                $snapToken = Snap::getSnapToken($params);
                $redirectUrl = Snap::createTransaction($params)->redirect_url;

                $deposit->snap_token = $snapToken;
                $deposit->redirect_url = $redirectUrl;
                $deposit->payment_expiry = now()->addDays(1);
                $deposit->save();

                return redirect()->away($redirectUrl);
            } else if ($request->typepayment == 0) {
                return redirect()->route('deposit.invoice', $deposit->topup_id)->with('success', 'Request Payment Success.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran. Silahkan coba lagi.');
        }
    }

    public function invoice($topup_id)
    {
        $deposit = Deposit::with('depositMethod')
            ->where('topup_id', $topup_id)
            ->where('member_id', Auth::user()->id)
            ->first();

        if (!$deposit) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        // Ambil detail nomor rekening dari tabel DepositMethod
        $method = DepositMethod::where('code', $deposit->payment_method)->first();

        if (!$method) {
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
        }

        return view('users.deposit.invoice', [
            'deposit' => $deposit,
            'method' => $method,
        ]);

        // Return ke view invoice
    }

    public function depositCancel($topup_id)
    {
        $deposit = Deposit::where('topup_id', $topup_id)->first();
        $user = User::where('id', $deposit->member_id)->first();

        $amount = nominal($deposit->amount);
        $target = "$user->number|$user->name|$amount|$deposit->payment_method|Canceled";
        $sendWa = WhatsApp::sendMessage($target, formatNotif('deposit_wa')->value);

        if ($sendWa['success'] == false) {
            return redirect()->back()->with('error', __($sendWa['message']));
        }

        if (!$deposit) {
            return redirect()->back()->with('error', 'Deposit not found.', 404);
        }

        try {
            $deposit->update(['status' => 'cancel']);

            return redirect()->route('deposit.new')->with('success', 'Deposit has been declined');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while cancelling the deposit: ' . $e->getMessage());
        }
    }

    private function qrisMethod($nominalDeposit, $orderId)
    {
        $baseUrl = 'https://api.sandbox.midtrans.com/v2/charge';

        $response = Http::withHeaders([
            'body' => '{"payment_type":"qris","transaction_details":{"order_id":"' . $orderId . '","gross_amount":' . $nominalDeposit . '}}',
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ])->post($baseUrl);

        return $response->getBody();
    }
}
