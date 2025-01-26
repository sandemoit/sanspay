<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\WhatsApp;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\DepositPayment;
use App\Models\Mutation;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DepositController extends Controller
{
    public function index()
    {
        return view('admin.deposit.management');
    }

    public function getData()
    {
        $product = Deposit::with(['user', 'depositmethod', 'depositpayment'])->get();

        return DataTables::of($product)
            ->addColumn('name', function ($row) {
                return $row->user->fullname;
            })
            ->addColumn('amount', function ($row) {
                return 'Rp. ' . nominal($row->amount);
            })
            ->addColumn('total_transfer', function ($row) {
                return 'Rp. ' . nominal($row->total_transfer);
            })
            ->addColumn('payment_method', function ($row) {
                return $row->depositmethod->name ?? $row->payment_method;
            })
            ->addColumn('tanggal', function ($row) {
                return tanggal($row->created_at);
            })
            ->addColumn('action', function ($row) {
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
                        if (in_array($row->depositmethod->type_payment, ['va', 'gopay', 'shopeepay', 'other_qris', 'cstore', 'akulaku', 'kredivo', 'alfamart', 'indomaret'])) {
                            return '<span class="badge bg-warning">Pending</span>';
                        } else {
                            return '
                                <a class="btn btn-sm btn-success accept-btn" href="' . url('/admin/deposit/management/' . base64_encode($row->id) . '/accept') . '"><ion-icon name="checkmark-outline"></ion-icon></a>

                                <a class="btn btn-sm btn-danger decline-btn" href="' . url('/admin/deposit/management/' . base64_encode($row->id) . '/decline') . '"><ion-icon name="close-outline"></ion-icon></a>
                            ';
                        }
                }
            })

            ->rawColumns(['action', 'name', 'total_transfer', 'amount', 'action', 'tanggal'])
            ->make(true);
    }

    public function actionDeposit($id, $action)
    {
        $id = base64_decode($id);
        $deposit = Deposit::find($id);
        $user = User::where('id', $deposit->member_id)->first();

        // parameter notif whatsapp
        $amount = nominal($deposit->amount);

        if ($action == 'accept') {
            $deposit->update(['status' => 'settlement']);

            Mutation::create([
                'username' => $user->name,
                'type' => '+',
                'amount' => $deposit->total_transfer,
                'note' => 'Deposit :: ' . uniqid(6),
            ]);

            $this->notifWhatsApp($user, $amount, $deposit, 'SUCCESS');

            $isuser = $user->update(['saldo' => $user->saldo + $deposit->total_transfer]);

            if ($isuser) {
                return response()->json([
                    'result' => true,
                    'message' => 'Deposit successfully sent'
                ]);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Deposit failed'
                ]);
            }
        } elseif ($action == 'decline') {
            $deposit->update(['status' => 'deny']);

            $this->notifWhatsApp($user, $amount, $deposit, 'DITOLAK');

            return response()->json([
                'result' => true,
                'message' => 'Deposit has been declined'
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => 'Invalid action'
        ], 400);
    }

    private function notifWhatsApp($user, $amount, $deposit, $status)
    {
        $target = "$user->number|$user->name|$deposit->topup_id|Bank $deposit->payment_method|$amount|$status";

        if ($status == 'SUCCESS') {
            $sendWa = WhatsApp::sendMessage($target, formatNotif('done_deposit_wa')->value);
        } else {
            $sendWa = WhatsApp::sendMessage($target, formatNotif('create_deposit_wa')->value);
        }

        if ($sendWa['success'] == false) {
            return redirect()->back()->with('error', __($sendWa['message']));
        }
    }

    public function methodDeposit()
    {
        $payment = DepositPayment::get();

        $data = [
            'payments' => $payment,
        ];

        return view('admin.deposit.method', $data);
    }

    public function getDataMethod()
    {
        $methodDepo = DepositMethod::get();

        return DataTables::of($methodDepo)
            ->addColumn('rate_method', function ($row) {
                return  "$row->rate %";
            })
            ->addColumn('fee_method', function ($row) {
                if ($row->xfee == '-') {
                    return "$row->xfee $row->fee";
                } else {
                    return  "$row->fee $row->xfee";
                }
            })
            ->addColumn('min_order', function ($row) {
                return  'Rp. ' . nominal($row->minimum);
            })
            ->addColumn('action', function ($row) {
                return '
                <a class="btn btn-sm btn-primary edit-btn" data-id="' . base64_encode($row->id) . '" data-bs-toggle="modal" data-bs-target="#editModal"><ion-icon name="pencil-outline"></ion-icon></a>
                <a class="btn btn-sm btn-danger delete-btn" href="' . url('/admin/deposit/methode/delete/' . base64_encode($row->id)) . '" data-id="' . base64_encode($row->id) . '"><ion-icon name="trash-outline"></ion-icon></a>
                ';
            })

            ->rawColumns(['action', 'rate_method', 'fee_method', 'min_order'])
            ->make(true);
    }

    public function methodStore(Request $request)
    {
        // Validasi request
        $request->validate([
            'code' => 'required|string',
            'accountname' => 'nullable|string', // Opsional jika auto
            'accountnumber' => 'nullable|string|min:6', // Opsional jika auto
            'xfee' => 'required|string',
            'fee' => 'nullable|numeric',
            'rate' => 'nullable|numeric',
            'minDeposit' => 'required|numeric',
            'depositType' => 'required|string',
            'midtrans' => 'required|boolean',
        ]);

        // Ambil data payment berdasarkan method (kode payment)
        $payment = DepositPayment::where('code', $request->code)->first();

        // Cek jika data payment tidak ditemukan
        if (!$payment) {
            return back()->with('error', 'Invalid payment method selected.');
        }

        $paymentData = [
            'code' => $request->code,
            'name' => $payment->name,
            'data' => $request->accountname ? "$request->accountname A/n $request->accountnumber" : 'Midtrans A/n Midtrans',
            'rate' => $request->rate ?? 0,
            'fee' => $request->fee ?? 0,
            'xfee' => $request->xfee,
            'minimum' => $request->minDeposit ?? 0,
            'type_payment' => $payment->type,
            'is_auto' => $request->depositType,
            'is_midtrans' => $request->midtrans,
        ];

        try {
            // Simpan data ke database
            DepositMethod::create($paymentData);
            return back()->with('success', 'Deposit method successfully added.');
        } catch (\Exception $e) {
            // Tampilkan error jika terjadi kesalahan
            return back()->with('error', 'Failed to add deposit method: ' . $e->getMessage());
        }
    }

    public function getDataMethodEdit($id)
    {
        $id = base64_decode($id);
        $method = DepositMethod::find($id);

        if (!$method) {
            return response()->json(['error' => 'code not found'], 404);
        }

        // Pecah data berdasarkan "A/n" jika ada
        $dataParts = explode(' A/n ', $method->data);
        $accountName = $dataParts[0] ?? ''; // Nama sebelum "A/n"
        $accountNumber = $dataParts[1] ?? ''; // Nomor setelah "A/n"

        // Format data untuk dikirim sebagai JSON ke form edit
        $response = [
            'code' => $method->code,
            'accountname' => $accountName,
            'accountnumber' => $accountNumber,
            'xfee' => $method->xfee,
            'fee' => $method->fee,
            'rate' => $method->rate,
            'minDeposit' => $method->minimum,
            'depositType' => $method->is_auto,
            'idMethod' => base64_encode($method->id),
            'midtrans' => $method->is_midtrans
        ];

        // Kirim data JSON ke AJAX
        return response()->json($response);
    }

    public function methodUpdate(Request $request)
    {
        $idMethod = base64_decode($request->idMethod);

        $request->validate([
            'code' => 'required|string',
            'accountname' => 'nullable|string',
            'accountnumber' => 'nullable|string|min:6',
            'xfee' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'rate' => 'nullable|numeric',
            'minDeposit' => 'nullable|numeric',
            'depositType' => 'required|string',
        ]);

        // Ambil data payment berdasarkan method (kode payment)
        $payment = DepositPayment::where('code', $request->code)->first();
        // Cek jika data payment tidak ditemukan
        if (!$payment) {
            return back()->with('error', 'Invalid payment method selected.');
        }

        $depositMethod = DepositMethod::find($idMethod);

        // Simpan data ke tabel DepositMethod
        $depositMethod->update([
            'code' => $request->code,
            'name' => $payment->name,
            'data' => $request->accountname ? "$request->accountname A/n $request->accountnumber" : 'Midtrans A/n Midtrans',
            'rate' => $request->rate ?? 0,
            'fee' => $request->fee ?? 0,
            'xfee' => $request->xfee,
            'minimum' => $request->minDeposit ?? 0,
            'type_payment' => $payment->type,
            'is_auto' => $request->depositType,
            'is_midtrans' => $request->midtrans,
        ]);

        // Redirect dengan pesan sukses
        return back()->with('success', 'Deposit method successfully added.');
    }

    public function deleteMethod($id)
    {
        $id = base64_decode($id);
        $method = DepositMethod::find($id);
        if ($method) {
            $method->delete();
            return response()->json(['success' => __('Method deleted successfully')]);
        }
        return response()->json(['error' => __('Method not found')], 404);
    }

    public function payment()
    {
        return view('admin.deposit.payment');
    }

    public function getPayment()
    {
        $payment = Payment::orderBy('name', 'desc')->get();

        return DataTables::of($payment)

            ->addColumn('action', function ($row) {
                return '
                <a class="btn btn-sm btn-success" href="#"><ion-icon name="pencil-outline"></ion-icon></a>

                <a class="btn btn-sm btn-danger delete-btn" href="' . url('/admin/deposit/payment/delete/' . base64_encode($row->id)) . '" data-id="' . base64_encode($row->id) . '"><ion-icon name="trash-outline"></ion-icon></a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storePayment(Request $request)
    {
        // Validasi request
        $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'type' => 'required|string',
        ]);
        $paymentData = [
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
        ];

        try {
            // Simpan data ke database
            Payment::create($paymentData);
            return back()->with('success', 'Deposit payment successfully added.');
        } catch (\Exception $e) {
            // Tampilkan error jika terjadi kesalahan
            return back()->with('error', 'Failed to add deposit method: ' . $e->getMessage());
        }
    }

    public function deletePayment($id)
    {
        $id = base64_decode($id);
        $method = Payment::find($id);
        if ($method) {
            $method->delete();
            return response()->json(['success' => __('Data deleted successfully')]);
        }
        return response()->json(['error' => __('Data not found')], 404);
    }
}
