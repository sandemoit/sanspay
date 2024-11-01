<?php

namespace App\Http\Controllers;

use App\Models\DepositMethod;
use App\Models\DepositPayment;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        return view('customer.deposit.index');
    }

    public function getDepositMethod()
    {
        $type = request('type'); // Mendapatkan nilai 'type' dari request

        // Daftar kode yang hanya ditampilkan jika type == 1
        $autoCodes = ['bni_va', 'bri_va', 'qris', 'gopay', 'alfamart', 'indomaret', 'permata_va', 'cimb_va', 'mandiri_va'];

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
            $uniqCode = rand(100, 999);

            // Inisialisasi $calculatedFee sebagai 0 agar tidak undefined
            $calculatedFee = 0;

            // Hitung fee berdasarkan tipe fee
            if ($feeType === '-') {
                // Jika fee berupa jumlah tetap
                $calculatedFee = $fee;
            } elseif ($feeType === '%') {
                // Jika fee berupa persentase
                $calculatedFee = ($nominalDeposit * $fee) / 100;
            }

            // Total transfer dan saldo yang diterima
            $totalTransfer = $nominalDeposit + $calculatedFee + $uniqCode;
            $saldoReceived = $nominalDeposit - $calculatedFee - $uniqCode;

            return response()->json([
                'fee' => nominal($fee),
                'code_unique' => $uniqCode,
                'total_transfer' => nominal($totalTransfer),
                'saldo_received' => nominal($saldoReceived),
            ]);
        }

        return response()->json(['error' => 'Method payment not found.'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'typepayment' => 'required',
            'methodpayment' => 'required',
            'nominalDeposit' => 'required',
        ]);
    }
}
