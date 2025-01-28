<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TrxPpob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{

    public function index()
    {
        $statuses = [
            'pending' => TrxPpob::where('status', 'Pending')->count(),
            'in_process' => TrxPpob::where('status', 'in_process')->count(),
            'success' => TrxPpob::where('status', 'Sukses')->count(),
            'cancel' => TrxPpob::where('status', 'Gagal')->count(),
            'profit' => TrxPpob::where('status', 'Sukses')->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'))->sum('profit'),
            'date' => Carbon::now()->endOfMonth()->format('d F Y'),
        ];

        return view('admin.order', compact('statuses'));
    }

    public function getData()
    {
        // Jika user adalah admin, ambil semua data orders tanpa filter
        $orders = TrxPpob::select('id', 'name as product_name', 'id_order', 'updated_at', 'price', 'status', 'data', 'user_id')
            ->with(['user' => function ($query) {
                $query->select('name', 'id');
            }, 'product' => function ($query) {
                $query->select('name', 'provider', 'id');
            }])
            ->get();

        return DataTables::of($orders)
            ->addColumn('date', function ($row) {
                return tanggal($row->updated_at);
            })
            ->addColumn('user_name', function ($row) {
                return $row->user->name ? $row->user->name : 'Tidak ada user';
            })
            ->addColumn('price_transaction', function ($row) {
                return currency($row->price, 'IDR');
            })
            ->addColumn('action', function ($row) {
                if ($row->status == 'Gagal') {
                    return '<span class="badge bg-danger">Gagal</span>';
                } elseif ($row->status == 'Pending') {
                    return '<span class="badge bg-warning">Pending</span>';
                } elseif ($row->status == 'Sukses') {
                    return '<span class="badge bg-success">Success</span>';
                }
            })
            ->addColumn('product', function ($row) {
                return $row->product_name . '<br><small class="text-primary">' . $row->product->provider . '</small>';
            })
            ->addColumn('id_ref', function ($row) {
                return '<a href="javascript:;" id="detailTrx" data-id="' . $row->id . '" class="text-primary" data-bs-toggle="modal" data-bs-target="#detailTrxModal">' . $row->id_order . '</a>';
            })
            ->rawColumns(['product', 'user_name', 'action', 'price_transaction', 'id_ref'])
            ->make(true);
    }

    public function getDetailTrx($id)
    {
        try {
            // Ambil data kategori berdasarkan ID
            $trx = TrxPpob::findOrFail($id);

            // Kembalikan data kategori sebagai JSON
            return response()->json([
                'trx' => $trx
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }
}
