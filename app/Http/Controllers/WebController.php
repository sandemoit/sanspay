<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductPpob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebController extends Controller
{
    public function index()
    {
        $title = 'Sans Pay Pulsa dan PPOB Termurah, Terlengkap & Terpercaya - Distributor Pulsa Termurah dan Terlengkap';

        return view('welcome', compact('title'));
    }

    public function daftarHarga(Request $request)
    {
        $title = 'Daftar Harga';

        // Cache key berdasarkan parameter filter
        $selectedBrand = $request->input('brand');
        $cacheKey = 'products_' . ($selectedBrand ?? 'all');

        // Query untuk kategori dengan cache
        $productsByBrand = Cache::remember('categories', 3600, function () {
            return Category::select('brand', 'name')
                ->groupBy('brand', 'name')
                ->orderBy('name', 'asc')
                ->get();
        });

        // Query untuk produk dengan cache dan eager loading
        $products = Cache::remember($cacheKey, 1800, function () use ($selectedBrand) {
            $query = ProductPpob::select(
                'name',
                'mitra_price',
                'cust_price',
                'healthy',
                'price',
                'brand'
            )
                ->when($selectedBrand, function ($query) use ($selectedBrand) {
                    return $query->where('brand', $selectedBrand);
                })
                ->orderBy('name', 'asc');

            return $query->get();
        });

        return view('harga', compact('products', 'productsByBrand', 'title', 'selectedBrand'));
    }

    public function instalApp()
    {
        $title = 'Transaksi Jadi Lebih Mudah - Instal Aplikasi di HP Anda';

        return view('install-app', compact('title'));
    }
}
