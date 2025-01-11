<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductPpob;

class WebController extends Controller
{
    public function index()
    {
        $title = 'Dapatkan Pulsa dan PPOB Termurah, Terlengkap & Terpercaya di Sanspay - Distributor Pulsa Termurah dan Terlengkap';

        return view('welcome', compact('title'));
    }

    public function daftarHarga()
    {
        $title = 'Daftar Harga';
        $productsByBrand = Category::select('brand', 'name')->groupBy('brand', 'name')->get();
        $products = ProductPpob::select('name', 'mitra_price', 'cust_price', 'healthy')->get();
        return view('harga', compact('products', 'productsByBrand', 'title'));
    }

    public function instalApp()
    {
        $title = 'Transaksi Jadi Lebih Mudah - Instal Aplikasi di HP Anda';

        return view('install-app', compact('title'));
    }
}
