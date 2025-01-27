<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductPpob;
use App\Models\Profit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductPpobController extends Controller
{
    public function index()
    {
        $type = Category::select('real')
            ->groupBy('real')
            ->get();

        return view('admin.pulsa-ppob.product', compact('type'));
    }

    public function getBrands()
    {
        $real = request('type');

        // Ambil data brand yang sesuai dengan type dan cache hasilnya
        $brands = cache()->remember('brands_' . $real, 60, function () use ($real) {
            return Category::where('real', $real)
                ->select('real', 'name')
                ->orderBy('name')
                ->distinct()
                ->get();
        });

        return response()->json($brands);
    }

    public function getData()
    {
        $product = ProductPpob::select('id', 'brand', 'code', 'provider', 'type', 'name', 'status', 'healthy', 'price', 'mitra_price', 'cust_price')
            ->orderBy('brand', 'asc')
            ->get();

        return DataTables::of($product)
            ->addColumn('product_name', function ($row) {
                $statusLabel = $row->status != 'empty' ? '<font class="text-success">[available]</font>' : '<font class="text-danger">[empty]</font>';
                $isHealthy = $row->healthy == 1 ? '<font class="text-success">[active]</font>' : '<font class="text-danger">[disruption]</font>';
                return $row->brand . '<br>' . '<small class="text-primary">' . $row->name . '<br>' . $statusLabel . '<br>' . $isHealthy . '</small>';
            })
            ->addColumn('product_price', function ($row) {
                return '<td class="focus">
                <li class="mt-1">Rp. ' . nominal($row->price, 'IDR') . ' [Source]</li>
                <li>Rp. ' . nominal($row->mitra_price, 'IDR') . ' [Mitra]</li>
                <li>Rp. ' . nominal($row->cust_price, 'IDR') . ' [Customer]</li>
                </td>';
            })
            ->addColumn('action', function ($row) {
                return '
                            <a class="btn btn-sm btn-primary edit-btn" data-id="' . base64_encode($row->id) . '" data-bs-toggle="modal" data-bs-target="#editModal"><ion-icon name="pencil-outline"></ion-icon></a>
                            <a class="btn btn-sm btn-danger delete-btn" href="' . url('/admin/pulsa-ppob/product/delete/' . base64_encode($row->id)) . '" data-id="' . base64_encode($row->id) . '"><ion-icon name="trash-outline"></ion-icon></a>
                        ';
            })
            ->rawColumns(['action', 'product_name', 'product_price'])
            ->make(true);
    }

    public function getProduct($id)
    {
        // Decode base64 ID
        $decodedId = base64_decode($id);
        // Ambil produk berdasarkan ID
        $product = ProductPpob::findOrFail($decodedId);

        return response()->json([
            'provider' => $product->provider,
            'code' => $product->code,
            'type' => $product->type,
            'brand' => $product->brand,
            'name' => $product->name,
            'note' => $product->note,
            'price' => $product->price,
            'status' => $product->status
        ]);
    }

    public function addProduct(Request $request)
    {
        // Validasi input form
        $validatedData = $request->validate([
            'provider' => 'required|string',
            'code' => 'required|string|max:50',
            'type' => 'required|string',
            'brand' => 'required|string',
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'required|string|in:empty,available',
        ]);

        $profits = Profit::whereIn('key', ['customer', 'agemitrant'])->pluck('value', 'key');

        $priceMitraMargin = $validatedData['price'] * (1 + $profits['mitra'] / 100);
        $priceBasicMargin = $validatedData['price'] * (1 + $profits['customer'] / 100);

        // Buat produk baru di database
        $product = ProductPpob::create([
            'provider' => $validatedData['provider'],
            'code' => $validatedData['code'],
            'type' => $validatedData['type'],
            'brand' => $validatedData['brand'],
            'name' => $validatedData['name'],
            'note' => $validatedData['note'],
            'price' => $validatedData['price'],
            'mitra_price' => $priceMitraMargin,
            'cust_price' => $priceBasicMargin,
            'status' => $validatedData['status'],
        ]);

        return response()->json(['success' => 'Product added successfully!']);
    }

    public function deleteProduct($id)
    {
        // Decode base64 ID
        $decodedId = base64_decode($id);

        // Cari dan hapus data
        $prodcut = ProductPpob::findOrFail($decodedId);
        if ($prodcut) {
            $prodcut->delete();
            return response()->json(['success' => __('Prodcut deleted successfully')]);
        }

        return response()->json(['error' => __('Prodcut not found')], 404);
    }

    public function updateProduct(Request $request, $id)
    {
        $decodedId = base64_decode($id);

        // Validasi input form
        $validatedData = $request->validate([
            'provider' => 'required|string',
            'code' => 'required|string|max:50',
            'type' => 'required|string',
            'brand' => 'required|string',
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'price' => 'required|numeric',
            'status' => 'required|string|in:empty,available',
        ]);

        // Cari produk berdasarkan ID
        $product = ProductPpob::findOrFail($decodedId);

        $profits = Profit::whereIn('key', ['customer', 'mitra'])->pluck('value', 'key');

        $priceMitraMargin = $validatedData['price'] * (1 + $profits['mitra'] / 100);
        $priceBasicMargin = $validatedData['price'] * (1 + $profits['customer'] / 100);

        // Update produk di database
        $product->update([
            'provider' => $validatedData['provider'],
            'code' => $validatedData['code'],
            'type' => $validatedData['type'],
            'brand' => $validatedData['brand'],
            'name' => $validatedData['name'],
            'note' => $validatedData['note'],
            'price' => $validatedData['price'],
            'mitra_price' => $priceMitraMargin,
            'cust_price' => $priceBasicMargin,
            'status' => $validatedData['status'],
        ]);

        return response()->json(['success' => 'Product updated successfully!']);
    }

    public function deleteAllProduct(Request $request)
    {
        ProductPpob::truncate();
        return response()->json(['success' => __('All Product deleted successfully')]);
    }
}
