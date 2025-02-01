<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DigiflazzController;
use App\Http\Controllers\WebController;
use App\Http\Middleware\HasBlocked;
use App\Http\Middleware\Maintenance;;

use App\Http\Middleware\XSS;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'index']);
Route::get('/harga-produk', [WebController::class, 'daftarHarga'])->name('harga-produk');
Route::get('/instal-app', [WebController::class, 'instalApp'])->name('instal-app');

Route::middleware(XSS::class)->group(function () {
    Route::post('/webhook', [DigiflazzController::class, 'handle'])->name('webhook');
    require __DIR__ . '/admin.php';
    require __DIR__ . '/auth.php';

    Route::middleware(Maintenance::class)->group(function () {
        require __DIR__ . '/users.php';
        require __DIR__ . '/job.php';
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "Cache cleared!";
});
