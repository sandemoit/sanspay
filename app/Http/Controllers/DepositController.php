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
use Yajra\DataTables\Facades\DataTables;

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
        return view('users.deposit.index');
    }

    public function getData()
    {
        $deposit = Deposit::with('depositmethod')->where('member_id', Auth::id())->get();

        return DataTables::of($deposit)
            ->addColumn('INVDate', function ($row) {
                // return "#$row->topup_id<br><small class=\"text-primary\">$row->update_at</small>";
                return '<a href="' . route('deposit.invoice', ['topup_id' => $row->topup_id]) . '">#' . $row->topup_id . '</a><br>' . tanggal($row->created_at);
            })
            ->addColumn('Method', function ($row) {
                return $row->depositmethod->name;
            })
            ->addColumn('TotalTransfer', function ($row) {
                return 'Rp. ' . nominal($row->total_transfer);
            })
            ->addColumn('Amount', function ($row) {
                return 'Rp. ' . nominal($row->amount);
            })
            ->addColumn('Status', function ($row) {
                switch ($row->status) {
                    case 'settlement':
                        return '<span class="badge bg-success">Paid</span>';
                    case 'cancel':
                        return '<span class="badge bg-danger">Canceled</span>';
                    case 'deny':
                        return '<span class="badge bg-danger">Deny</span>';
                    case 'expired':
                        return '<span class="badge bg-danger">Expired</span>';
                    case 'pending';
                        if (in_array($row->depositmethod->type_payment, ['va', 'gopay', 'shopeepay', 'qris'])) {
                            return '<a class="btn btn-sm btn-warning accept-btn" href="' . $row->redirect_url . '"><ion-icon name="cash-outline"></ion-icon>Pay</a>';
                        } else {
                            return '
                                <a class="btn btn-sm btn-warning accept-btn" href="' . route('deposit.invoice', ['topup_id' => $row->topup_id]) . '"><ion-icon name="cash-outline"></ion-icon>Pay</a>
                            ';
                        }
                }
            })

            ->rawColumns(['INVDate', 'Status'])
            ->make(true);
    }

    public function request()
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

        return view('users.deposit.request');
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
        $user = Auth::user(); // Hindari query ulang user

        // Validasi input dan metode pembayaran
        $method = DepositMethod::where('code', $request->methodpayment)->firstOrFail(); // Fail jika metode tidak ditemukan

        $validatedData = $request->validate([
            'typepayment' => 'required|in:0,1',
            'methodpayment' => 'required',
            'nominalDeposit' => 'required|numeric|min:' . $method->minimum,
        ]);

        // Generate data untuk deposit
        $total_transfer = (int)str_replace('.', '', $request->total_transfer) + rand(100, 999);
        $amount = (int)str_replace('.', '', $request->saldo_recived);

        try {
            // Simpan data deposit
            $deposit = Deposit::create([
                'topup_id' => 'INV' . time(),
                'member_id' => $user->id,
                'payment_method' => $request->methodpayment,
                'amount' => $amount,
                'total_transfer' => $total_transfer,
                'status' => 'pending',
            ]);

            // Kirim notifikasi WhatsApp
            $amountFormatted = nominal($amount);
            $target = "{$user->number}|{$user->name}|{$deposit->topup_id}|{$method->name}|$amountFormatted|PENDING";

            $sendWa = WhatsApp::sendMessage($target, formatNotif('create_deposit_wa')->value);
            if (!$sendWa['success']) {
                return redirect()->back()->with('error', __($sendWa['message']));
            }

            // Proses pembayaran jika typepayment adalah Midtrans
            if ($request->typepayment == 1) {
                $this->processMidtrans($request, $deposit, $user);
            } else {
                return redirect()->route('deposit.invoice', $deposit->topup_id)->with('success', 'Request Payment Success.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran. Silahkan coba lagi.');
        }
    }

    /**
     * Proses pembayaran melalui Midtrans
     */
    private function processMidtrans(Request $request, $deposit, $user)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $deposit->topup_id,
                'gross_amount' => $deposit->total_transfer,
            ],
            'customer_details' => [
                'first_name' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->number,
            ],
            'callbacks' => [
                'finish' => route('deposit.invoice', ['topup_id' => $deposit->topup_id]),
            ],
        ];

        // Atur metode pembayaran
        $enabledPayments = match ($request->methodpayment) {
            'bni_va', 'bri_va', 'mandiri_va', 'cimb_va', 'permata_va', 'bca_va' => ["$request->methodpayment"],
            'gopay' => ['gopay'],
            'shopeepay' => ['shopeepay'],
            'qris' => ['other_qris'],
            default => throw new \Exception('Metode pembayaran tidak valid.'),
        };

        $params['enabled_payments'] = $enabledPayments;
        $params['payment_type'] = $enabledPayments[0];

        // Request snap token
        $snapToken = Snap::getSnapToken($params);
        $redirectUrl = Snap::createTransaction($params)->redirect_url;

        // Update data deposit dengan informasi pembayaran
        $deposit->update([
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
            'payment_expiry' => now()->addDay(),
        ]);

        return redirect()->away($redirectUrl);
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
        $target = "$user->number|$user->name|$deposit->topup_id|Bank $deposit->payment_method|$amount|CANCELED";
        $sendWa = WhatsApp::sendMessage($target, formatNotif('create_deposit_wa')->value);

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

    public function depositCancelMidtrans($topup_id)
    {
        $baseUrl = "https://api.sandbox.midtrans.com/v2/$topup_id/cancel";
        $serverKey = config('midtrans.server_key');
        $encodedKey = base64_encode($serverKey . ':');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $encodedKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post($baseUrl);

        $responseBody = json_decode($response->body());

        if ($response->successful() && $responseBody->status_code == 200) {
            return redirect()->route('deposit.new')->with('success', $responseBody->status_message);
        } else {
            $errorMessage = $responseBody->status_message ?? 'Something went wrong';
            return redirect()->back()->with('error', $errorMessage);
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
