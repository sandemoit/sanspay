<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DigiflazzController;
use App\Http\Controllers\WebController;;

use App\Http\Middleware\XSS;
use App\Models\Category;
use App\Models\ProductPpob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'index']);

Route::get('/harga-produk', function () {
    $productsByBrand = Category::select('brand', 'name')->groupBy('brand', 'name')->get();
    $products = ProductPpob::select('name', 'mitra_price', 'cust_price', 'healthy')->get();
    return view('harga', compact('products', 'productsByBrand'));
})->name('harga-produk');

Route::middleware(XSS::class)->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::post('/webhook', [DigiflazzController::class, 'handle'])->name('webhook');

    require __DIR__ . '/auth.php';
    require __DIR__ . '/admin.php';
    require __DIR__ . '/users.php';
    require __DIR__ . '/job.php';
});
Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "Cache cleared!";
});
