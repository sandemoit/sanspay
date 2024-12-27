<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit');
    Route::get('/deposit/get', [DepositController::class, 'getData'])->name('deposit.get');
    Route::get('/deposit/new', [DepositController::class, 'request'])->name('deposit.new');
    Route::post('/deposit/new', [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/invoice/{topup_id}', [DepositController::class, 'invoice'])->name('deposit.invoice');
    Route::get('/deposit/new/getMethod', [DepositController::class, 'getDepositMethod']);
    Route::get('/deposit/new/calculate-fee', [DepositController::class, 'calculateFee']);
    Route::get('/deposit/cancel/{topup_id}', [DepositController::class, 'depositCancel'])->name('deposit.cancel');
    Route::get('/deposit/cancelMidtrans/{topup_id}', [DepositController::class, 'depositCancelMidtrans'])->name('deposit.cancelMidtrans');

    // order Page
    Route::get('/order/paket-telepon', [OrderController::class, 'newOrder'])->name('order.paket-telepon');
    Route::get('/order/pulsa-reguler', [OrderController::class, 'newOrder'])->name('order.pulsa-reguler');
    Route::get('/order/paket-internet', [OrderController::class, 'newOrder'])->name('order.paket-internet');
    Route::get('/order/pulsa-transfer', [OrderController::class, 'newOrder'])->name('order.pulsa-transfer');
    Route::post('/filter-products', [OrderController::class, 'filterProducts'])->name('filter.products');

    Route::post('/check-provider', [OrderController::class, 'checkProvider'])->name('order.checkProvider');
    Route::get('/confirm/{code}', [OrderController::class, 'confirmOrder'])->name('order.confirm');
    Route::post('/order/new', [OrderController::class, 'prosesTransaksi'])->name('order.new');
    Route::get('/order/history', [OrderController::class, 'historyTransaksi'])->name('order.history');
});

// page announcement
Route::get('/announcement/{slug}', [DashboardController::class, 'announcement'])->name('announcement');
