<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/deposit/new', [DepositController::class, 'index'])->name('deposit.new');
Route::post('/deposit/new', [DepositController::class, 'store'])->name('deposit.store');
Route::get('/deposit/invoice/{topup_id}', [DepositController::class, 'invoice'])->name('deposit.invoice');
Route::get('/deposit/new/getMethod', [DepositController::class, 'getDepositMethod']);
Route::get('/deposit/new/calculate-fee', [DepositController::class, 'calculateFee']);
Route::get('/deposit/cancel/{topup_id}', [DepositController::class, 'depositCancel'])->name('deposit.cancel');

// page announcement
Route::get('/announcement/{slug}', [DashboardController::class, 'announcement'])->name('announcement');
