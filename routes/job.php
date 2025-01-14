<?php

use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/admin/getSaldoDigi', function () {
    Artisan::call('get:saldodigi');
});

Route::get('/sitemap/generate', function () {
    Artisan::call('sitemap:generate');
});

Route::get('/admin/pulsa-ppob/product/pull-product', function () {
    Artisan::call('get:product');
})->name('pulsa-ppob.product.pull');

Route::post('/midtrans-callback', [MidtransController::class, 'callback']);
