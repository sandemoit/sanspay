<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\TrxPpob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{

    public function index()
    {
        $statuses = [
            'pending' => TrxPpob::where('status_trx', 'pending')->count(),
            'in_process' => TrxPpob::where('status_trx', 'in_process')->count(),
            'success' => TrxPpob::where('status_trx', 'success')->count(),
            'canceled' => TrxPpob::where('status_trx', 'canceled')->count(),
        ];

        return view('admin.order', compact('statuses'));
    }

    public function getData()
    {
        // Cek apakah user adalah admin
        $user = Auth::user();

        // Jika user adalah admin, ambil semua data orders tanpa filter
        if ($user->role === 'admin') {
            $orders = TrxPpob::with('user', 'product')->get();
        } else {
            // Jika bukan admin, hanya ambil data berdasarkan user_id
            $orders = TrxPpob::with('user', 'product')
                ->where('user_id', $user->id)
                ->get();
        }

        return DataTables::of($orders)
            ->addColumn('date', function ($row) {
                return tanggal($row->updated_at);
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : 'Tidak ada user';
            })
            ->addColumn('product_name', function ($row) {
                return $row->product ? $row->product->name : 'Tidak ada product';
            })
            ->addColumn('total_payment', function ($row) {
                return currency($row->total_payment, 'IDR');
            })
            ->addColumn('status', function ($row) {
                if ($row->status_trx == 'cancel') {
                    return '<span class="badge bg-danger">Cancel</span>';
                } elseif ($row->status_trx == 'pending') {
                    return '<span class="badge bg-warning">Pending</span>';
                } elseif ($row->status_trx == 'success') {
                    return '<span class="badge bg-success">Success</span>';
                }
            })
            ->addColumn('product', function ($row) {
                return $row->product->name . '<br><small class="text-primary">' . $row->product->provider . ': ' . $row->id_order . '</small>';
            })
            ->rawColumns(['product', 'status'])
            ->make(true);
    }
}
