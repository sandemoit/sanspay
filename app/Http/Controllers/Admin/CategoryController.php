<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.pulsa-ppob.category');
    }

    public function getData(Request $request)
    {
        $category = Category::query()
            ->when(Auth::user()->role !== 'admin')
            ->get();

        return DataTables::of($category)
            ->addColumn('type_category', function ($row) {
                $types = [
                    'pulsa-reguler'       => 'Pulsa Reguler',
                    'pulsa-transfer'       => 'Pulsa Transfer',
                    'voucher-game'        => 'Voucher Game',
                    'pulsa-internasional' => 'Pulsa Internasional',
                    'streaming-tv'        => 'Streaming & TV',
                    'aktivasi-voucher'    => 'Aktivasi Voucher',
                    'aktivasi-perdana'    => 'Aktivasi Perdana',
                    'paket-internet'      => 'Paket Internet',
                    'saldo-emoney'        => 'Saldo E-Money',
                    'paket-telepon'       => 'Paket Telepon dan SMS',
                    'paket-lainnya'       => 'Kategori Lainnya',
                    'token-pln'           => 'Token Listrik (PLN)',
                    'pascabayar'          => 'Pascabayar',
                    'masa-aktif'          => 'Masa Aktif',
                ];

                $type = $types[$row->type] ?? 'Unknown';

                return Str::of($type)->append(" [{$row->type}]");
            })

            ->addColumn('action', function ($row) {
                return '
                            <a class="btn btn-sm btn-primary edit-btn" data-id="' . base64_encode($row->id) . '" data-bs-toggle="modal" data-bs-target="#editModal"><ion-icon name="pencil-outline"></ion-icon></a>
                            <a class="btn btn-sm btn-danger delete-btn" href="' . url('/admin/pulsa-ppob/delete/' . base64_encode($row->id)) . '" data-id="' . base64_encode($row->id) . '"><ion-icon name="trash-outline"></ion-icon></a>
                        ';
            })
            ->rawColumns(['type_category', 'action'])
            ->make(true);
    }

    public function getCategory($id)
    {
        try {
            // Decode ID dari base64
            $decodedId = base64_decode($id);

            // Ambil data kategori berdasarkan ID
            $category = Category::findOrFail($decodedId);

            // Kembalikan data kategori sebagai JSON
            return response()->json([
                'name' => $category->name,
                'code' => $category->code,
                'type' => $category->type,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            // Decode ID dari base64
            $decodedId = base64_decode($id);

            // Validasi input (opsional, tambahkan aturan sesuai kebutuhan)
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Cari kategori berdasarkan ID
            $category = Category::findOrFail($decodedId);

            // Update data kategori
            $category->name = $request->input('name');
            $category->save();

            // Kembalikan respons sukses
            return response()->json(['success' => __('Data updated successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Failed to update data')], 500);
        }
    }

    public function deleteCategory($id)
    {
        // Decode base64 ID
        $decodedId = base64_decode($id);

        // Cari dan hapus data
        $category = Category::findOrFail($decodedId);
        if ($category) {
            $category->delete();
            return response()->json(['success' => __('Category deleted successfully')]);
        }

        return response()->json(['error' => __('Category not found')], 404);
    }
}
