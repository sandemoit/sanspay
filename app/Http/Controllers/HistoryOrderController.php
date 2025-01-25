<?php

namespace App\Http\Controllers;

use App\Models\TrxPpob;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class HistoryOrderController extends Controller
{
    public function historyTransaksi()
    {
        $currentId = Auth::id();
        $statuses = [
            'pending' => TrxPpob::where('status', 'Pending')->where('user_id', $currentId)->whereDate('created_at', date('Y-m-d'))->count(),
            'success' => TrxPpob::where('status', 'Sukses')->where('user_id', $currentId)->whereDate('created_at', date('Y-m-d'))->count(),
            'cancel' => TrxPpob::where('status', 'Gagal')->where('user_id', $currentId)->whereDate('created_at', date('Y-m-d'))->count(),
            'total_price' => TrxPpob::where('user_id', $currentId)->where('status', '=', 'Sukses')->whereDate('created_at', date('Y-m-d'))->sum('price'),
        ];

        $title = 'History Transaksi';
        return view('users.orders.history', compact('title', 'statuses'));
    }

    public function getData()
    {
        $currentId = Auth::id();
        $transaksiPpob = TrxPpob::select(['id', 'id_order', 'user_id', 'status', 'updated_at', 'name', 'sn', 'price', 'note', 'refund', 'data'])->where('user_id', $currentId)->whereDate('created_at', date('Y-m-d'))->get();

        return DataTables::of($transaksiPpob)
            ->addColumn('trx_id', function ($row) {
                return '<a href="javascript:;" id="detailTrx" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#detailTrxModal">' . $row->id_order . ' <ion-icon name="eye-outline"></ion-icon></a>';
            })
            ->addColumn('trx_refund', function ($row) {
                return $row->refund == 1 ? '<span class="badge bg-danger">Refund</span>' : '<span class="badge bg-success">Tidak Refund</span>';
            })
            ->addColumn('trx_price', function ($row) {
                return 'Rp ' . nominal($row->price);
            })
            ->addColumn('sn', function ($row) {
                $parts = explode('/', $row->sn);
                return count($parts) >= 2 ? $parts[0] . '/' . $parts[1] : $row->sn;
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'Pending') {
                    return '<span class="badge bg-warning">' . ucfirst($row->status) . '</span>';
                } elseif ($row->status == 'Gagal') {
                    return '<span class="badge bg-danger">' . ucfirst($row->status) . '</span>';
                } else {
                    return '<span class="badge bg-success">' . ucfirst($row->status) . '</span>';
                }
            })
            ->rawColumns(['trx_id', 'trx_refund', 'trx_price', 'status'])
            ->make(true);
    }

    public function getDetailTrx($id)
    {
        try {
            // Ambil data kategori berdasarkan ID
            $trx = TrxPpob::with('user')->where('user_id', Auth::id())->findOrFail($id);

            // Kembalikan data kategori sebagai JSON
            return response()->json([
                'order_id' => $trx->id_order,
                'pengirim' => $trx->user->fullname,
                'code' => $trx->code,
                'name' => $trx->name,
                'data' => $trx->data,
                'status' => $trx->status,
                'note' => $trx->note,
                'sn' => implode('/', array_slice(explode('/', $trx->sn), 0, 2)),
                'price' => nominal($trx->price),
                'created_at' => tanggalTrx($trx->created_at),
                'updated_at' => tanggalTrx($trx->updated_at),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }
}
