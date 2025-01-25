<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use App\Models\TrxPpob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function prabayar(Request $request)
    {
        $query = TrxPpob::where('user_id', Auth::id())->where('type', 'prepaid');

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : null;

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        } else {
            // Default: filter untuk hari ini
            $today = Carbon::today();
            $query->whereDate('created_at', $today);
        }

        // Clone query untuk total transaksi sukses dan gagal
        $totalSukses = (clone $query)->where('status', 'Sukses')->count();
        $totalGagal = (clone $query)->where('status', 'Gagal')->count();
        $totalPenjualan = $query->sum('price');

        // Ambil data untuk DataTables
        $data = $query->get();

        return view('users.laporan.prabayar', compact('totalSukses', 'totalGagal', 'totalPenjualan', 'data'));
    }

    public function mutation(Request $request)
    {
        // Ambil username yang sedang login
        $user = Auth::user();

        // Buat query untuk filter berdasarkan username
        $query = Mutation::where('username', $user->name);

        // Filter berdasarkan range tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Ambil data mutasi
        $mutasi = $query->orderBy('created_at', 'desc')->get();

        // Hitung total mutasi masuk dan keluar
        // Kita perlu clone query untuk menghindari query builder yang sama
        $totalMasuk = (clone $query)->where('type', '+')->sum('amount');
        $totalKeluar = (clone $query)->where('type', '-')->sum('amount');

        // Hitung sisa saldo
        $sisaSaldo = $user->saldo;

        return view('users.laporan.mutation', compact('mutasi', 'totalMasuk', 'totalKeluar', 'sisaSaldo'));
    }
}
