<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsApp;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\Mutation;
use App\Models\TransferSaldo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
                return $row->depositmethod->name ?? $row->payment_method;
            })
            ->addColumn('TotalTransfer', function ($row) {
                if ($row->payment_method === 'point_exchange') {
                    return $row->total_transfer . ' Point';
                } elseif ($row->payment_method === 'transfer') {
                    return 'Rp. ' . nominal($row->total_transfer);
                }
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
                        if (in_array($row->depositmethod->type_payment, ['va', 'gopay', 'shopeepay', 'qris', 'cstore', 'akulaku', 'kredivo', 'alfamart', 'indomaret'])) {
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
        $autoCodes = ['bni_va', 'bri_va', 'shopeepay', 'gopay', 'alfamart', 'indomaret', 'permata_va', 'cimb_va', 'echannel', 'qris', 'akulaku'];

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

        $validator = Validator::make($request->all(), [
            'typepayment' => 'required|in:0,1',
            'methodpayment' => 'required',
            'nominalDeposit' => ['required', 'numeric', 'min:' . $method->minimum, function ($attribute, $value, $fail) use ($method) {
                if ($value < $method->minimum) {
                    $fail('Minimal deposit untuk metode ' . $method->name . ' adalah Rp. ' . nominal($method->minimum) . '.');
                }
            }],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Generate data untuk deposit
        $amount = (int)str_replace('.', '', $request->saldo_recived);

        if ((int)$request->typepayment === 0) {
            $total_transfer = (int)strip_tags(str_replace('.', '', $request->total_transfer)) + rand(100, 999);
        } else {
            $total_transfer = (int)strip_tags(str_replace('.', '', $request->total_transfer));
        }

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Simpan data deposit
            $deposit = Deposit::create([
                'topup_id' => 'INV' . time(),
                'member_id' => $user->id,
                'payment_method' => $request->methodpayment,
                'amount' => $request->typepayment == 0 ? $total_transfer : $amount,
                'total_transfer' => $total_transfer,
                'status' => 'pending',
            ]);

            // Kirim notifikasi WhatsApp
            $amountFormatted = nominal($amount);
            $target = "{$user->number}|{$user->name}|{$deposit->topup_id}|{$method->name}|$amountFormatted|PENDING";

            $sendWa = WhatsApp::sendMessage($target, formatNotif('create_deposit_wa')->value);
            if (!$sendWa['success']) {
                DB::rollback();
                return redirect()->back()->with('error', __($sendWa['message']));
            }

            // Proses pembayaran jika typepayment adalah Midtrans
            if ($request->typepayment == 1) {
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
                    'bni_va', 'bri_va', 'echannel', 'cimb_va', 'permata_va', 'bca_va' => [$request->methodpayment],
                    'gopay', 'shopeepay', 'qris', 'akulaku', 'kredivo', 'alfamart', 'indomaret' => [$request->methodpayment],
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

                DB::commit();
                return redirect()->away($redirectUrl);
            } else {
                DB::commit();
                return redirect()->route('deposit.invoice', $deposit->topup_id)->with('success', 'Request Payment Success.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing deposit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pembayaran. Silahkan coba lagi.');
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

        if (in_array($deposit->payment_method, ['point_exchange', 'transfer'])) {
            return redirect()->back()->with('error', 'Tukar Point atau Transfer tidak ada invoice.');
        }

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
        $deposit = Deposit::with('depositmethod')->where('topup_id', $topup_id)->first();
        $user = User::where('id', $deposit->member_id)->first();

        $amount = nominal($deposit->amount);
        $nameBank = $deposit->depositmethod->name;
        $target = "$user->number|$user->name|$deposit->topup_id|$nameBank|$amount|CANCELED";
        $sendWa = WhatsApp::sendMessage($target, formatNotif('done_deposit_wa')->value);

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
        Deposit::where('topup_id', $topup_id)->update(['status' => 'cancel']);

        $baseUrl = "https://api.sandbox.midtrans.com/v2/$topup_id/cancel";
        $serverKey = config('midtrans.server_key');
        $encodedKey = base64_encode($serverKey . ':');

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $encodedKey,
            'Accept' => 'application/json',
        ])->post($baseUrl);

        $responseBody = json_decode($response->body());

        // Batalkan transaksi menggunakan Midtrans API
        if ($response->successful() && $responseBody->status_code == 200) {
            return redirect()->route('deposit.new')->with('success', $responseBody->status_message);
        } else {
            $errorMessage = $responseBody->status_message ?? 'Something went wrong';
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function sendSaldo()
    {
        $title = 'Kirim Saldo Antar Mitra';
        return view('users.deposit.send-saldo', compact('title'));
    }

    public function sendSaldoStore(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,name',
            'nominal' => 'required|numeric|min:1',
            'pin' => 'required|digits:6',
        ]);

        $sender = Auth::user();
        $receiver = User::where('name', $request->username)->first();
        $saldo = (int) str_replace('.', '', $request->nominal);

        // validasi receiver
        if (!$receiver) {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }

        // Validasi PIN menggunakan Hash
        if (!Hash::check($request->pin, $sender->pin)) {
            return redirect()->back()->with('error', 'PIN yang dimasukkan salah');
        }

        // Cek apakah saldo mencukupi
        if ($sender->saldo < $saldo) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi');
        }

        DB::beginTransaction();
        try {
            // Kurangi saldo pengirim
            User::where('id', $sender->id)->decrement('saldo', $saldo);

            // Tambah saldo penerima
            User::where('id', $receiver->id)->increment('saldo', $saldo);

            // Catat mutasi saldo untuk pengirim
            Mutation::create([
                'username' => $sender->name,
                'type' => '-',
                'amount' => $saldo,
                'note' => 'Kirim Saldo ke ' . $receiver->fullname,
            ]);

            // Catat mutasi saldo untuk penerima
            Mutation::create([
                'username' => $receiver->name,
                'type' => '+',
                'amount' => $saldo,
                'note' => 'Terima Saldo dari ' . $sender->fullname,
            ]);

            // Catat transaksi penerima
            TransferSaldo::create([
                'id_uniq' => substr(str_shuffle('0123456789'), 0, 12),
                'sender' => $sender->fullname,
                'reciver' => $receiver->fullname,
                'nominal' => $saldo,
                'status' => 'success',
            ]);

            Deposit::create([
                'topup_id' => 'INV' . time(),
                'member_id' => $receiver->id,
                'amount' => $saldo,
                'total_transfer' => $saldo,
                'payment_method' => 'transfer',
                'status' => 'settlement',
                'payment_expiry' => now(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Saldo berhasil dikirim');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getKirimSaldo()
    {
        $transfer = TransferSaldo::get();

        return DataTables::of($transfer)
            ->addColumn('nominal_transfer', function ($row) {
                return 'Rp' . nominal($row->nominal);
            })
            ->addColumn('date', function ($row) {
                return tanggal($row->created_at);
            })
            ->addColumn('status_transfer', function ($row) {
                switch ($row->status) {
                    case 'success':
                        return '<span class="badge bg-success">Berhasil</span>';
                }
            })

            ->rawColumns(['status_transfer'])
            ->make(true);
    }

    public function tukarPoint(Request $request)
    {
        $id = Auth::id();
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'point' => 'required|integer|min:1',
            'pin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $point = strip_tags($request->point);
        $pin = strip_tags($request->pin);

        // Validasi PIN
        if (!Hash::check($pin, $user->pin)) {
            return redirect()->back()->with(['error' => 'PIN transaksi tidak valid.'])->withInput();
        }

        // Validasi jumlah point
        if ($user->point < $point) {
            return redirect()->back()->with(['error' => 'Jumlah point tidak mencukupi.'])->withInput();
        }

        // Update point dan saldo user
        $user->decrement('point', $point);
        $user->increment('saldo', $point);

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Buat entri deposit
            Deposit::create([
                'topup_id' => 'INV' . time(),
                'member_id' => $user->id,
                'payment_method' => 'point_exchange',
                'amount' => $point,
                'total_transfer' => $point,
                'status' => 'settlement',
            ]);

            // Buat entri mutation
            Mutation::create([
                'username' => $user->name,
                'type' => 'point_exchange',
                'amount' => $point,
                'note' => 'Pertukaran point ke saldo',
            ]);

            // Commit transaksi
            DB::commit();

            return redirect()->back()->with('success', 'Pertukaran point ke saldo berhasil.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return redirect()->back()->with(['error' => $e->getMessage()])->withInput();
        }
    }
}
