<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsApp;
use App\Models\Deposit;
use App\Models\Mutation;
use App\Models\User;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;

        $order = Deposit::with(['user', 'depositmethod'])->where('topup_id', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $order->update(['status' => 'pending']);
                    } else {
                        $order->update(['status' => 'settlement']);
                        $this->saveMutationAndBalance($order);
                    }
                }
                break;
            case 'settlement':
                $order->update(['status' => 'settlement']);
                $this->saveMutationAndBalance($order);
                $this->sendNotifCallback($order, 'Success');
                break;
            case 'pending':
                $order->update(['status' => 'pending']);
                break;
            case 'deny':
                $order->update(['status' => 'failed']);
                $this->sendNotifCallback($order, 'Failed');
                break;
            case 'expire':
                $order->update(['status' => 'expired']);
                $this->sendNotifCallback($order, 'Expired');
                break;
            case 'cancel':
                $order->update(['status' => 'canceled']);
                $this->sendNotifCallback($order, 'Canceled');
                break;
            default:
                $order->update(['status' => 'unknown']);
                break;
        }

        return response()->json(['status' => 'success', 'message' => 'Callback received successfully']);
    }

    private function sendNotifCallback(Deposit $order, $status)
    {
        $user = $order->user;
        $method = $order->depositpayment;
        $amount = nominal($order->amount);
        dd($method);
        $target = "$user->number|$user->name|$amount|$method->name|$status|TOTAL SALDO KAMU: $user->saldo";
        $sendWa = WhatsApp::sendMessage($target, formatNotif('deposit_wa')->value);

        if ($sendWa['success'] == false) {
            return redirect()->back()->with('error', __($sendWa['message']));
        }
    }

    public function saveMutationAndBalance($order)
    {
        $user = $order->user;

        if (!$user) {
            return false; // Return jika user tidak ditemukan
        }

        // Simpan mutasi
        Mutation::create([
            'username' => $user->name,
            'type' => '+',
            'amount' => $order->amount,
            'note' => 'Deposit :: ' . uniqid(),
        ]);

        // Update saldo user
        $user->increment('saldo', $order->amount);
    }
}
