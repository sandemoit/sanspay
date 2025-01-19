<?php

use App\Http\Controllers\Orders\EmoneyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Orders\GamesController;
use App\Http\Controllers\Orders\OrderHpController;
use App\Http\Controllers\Orders\PascabayarController;
use App\Http\Controllers\Orders\PertagasController;
use App\Http\Controllers\Orders\TokenController;
use App\Http\Controllers\Orders\VoucherController;
use App\Http\Middleware\VerifyRecaptcha;

// index
Route::get('/confirm/{code}/{target?}', [OrderHpController::class, 'confirmOrder'])->name('order.confirm');

// games
Route::get('/order/games', [GamesController::class, 'index'])->name('order.games');
Route::post('/order/priceGames', [GamesController::class, 'priceGames'])->name('priceGames');
Route::post('/order/orderGames', [GamesController::class, 'prosesTransaksi'])->name('orderGames');

// order pulsa, paket, kuota, transfer Page
Route::get('/order/paket-telepon', [OrderHpController::class, 'newOrder'])->name('order.paket-telepon');
Route::get('/order/pulsa-reguler', [OrderHpController::class, 'newOrder'])->name('order.pulsa-reguler');
Route::get('/order/paket-internet', [OrderHpController::class, 'newOrder'])->name('order.paket-internet');
Route::get('/order/pulsa-transfer', [OrderHpController::class, 'newOrder'])->name('order.pulsa-transfer');
Route::post('/filter-products', [OrderHpController::class, 'filterProducts'])->name('filter.products');
Route::post('/check-provider', [OrderHpController::class, 'checkProvider'])->name('order.checkProvider');
Route::post('/order/proses', [OrderHpController::class, 'prosesTransaksi'])->name('order.proses');

// order emoney
Route::get('/order/emonney', [EmoneyController::class, 'index'])->name('order.emonney');
Route::post('/order/priceEmoney', [EmoneyController::class, 'priceEmoney'])->name('priceEmoney');
Route::post('/order/orderEmoney', [EmoneyController::class, 'prosesTransaksi'])->name('orderEmoney');

// order token-pln
Route::get('/order/token-pln', [TokenController::class, 'index'])->name('order.token-pln');
Route::post('/order/priceToken', [TokenController::class, 'priceToken'])->name('priceToken');
Route::post('/order/orderToken', [TokenController::class, 'prosesTransaksi'])->name('orderToken');

// voucher
Route::get('/order/voucher', [VoucherController::class, 'index'])->name('order.voucher');
Route::post('/order/priceVoucher', [VoucherController::class, 'priceVoucher'])->name('priceVoucher');
Route::post('/order/orderVoucher', [VoucherController::class, 'prosesTransaksi'])->name('orderVoucher');

// pertagas
Route::get('/order/pertagas', [PertagasController::class, 'index'])->name('order.pertagas');
Route::post('/order/pricePertagas', [PertagasController::class, 'pricePertagas'])->name('pricePertagas');
Route::post('/order/orderPertagas', [PertagasController::class, 'prosesTransaksi'])->name('orderPertagas');

// pascabayar
Route::get('/order/pascabayar/{brand}', [PascabayarController::class, 'index'])->name('order.pascabayar');
Route::post('/order/check-bill', [PascabayarController::class, 'checkBill'])->name('check-bill');
Route::post('/order/pascabayar/pascaTrx', [PascabayarController::class, 'prosesTransaksi'])->name('pascaTrx');
