<?php

use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/admin/getSaldoDigi', function () {
    Artisan::call('get:saldodigi');
})->name('admin.getSaldoDigi');

Route::get('/admin/pulsa-ppob/product/pull-product', function () {
    Artisan::call('get:product');
})->name('pulsa-ppob.product.pull');

Route::post('/midtrans-callback', [MidtransController::class, 'callback']);
