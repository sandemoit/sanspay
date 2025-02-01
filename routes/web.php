<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
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

Route::middleware(XSS::class, HasBlocked::class, Maintenance::class)->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::post('/webhook', [DigiflazzController::class, 'handle'])->name('webhook');

    require __DIR__ . '/admin.php';
    require __DIR__ . '/users.php';
    require __DIR__ . '/job.php';

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

require __DIR__ . '/auth.php';
Route::get('/clear-cache', function () {
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    return "Cache cleared!";
});
