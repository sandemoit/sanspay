<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/deposit/new', [DepositController::class, 'index'])->name('deposit.new');
Route::post('/deposit/new', [DepositController::class, 'store'])->name('deposit.store');
Route::get('/deposit/new/getMethod', [DepositController::class, 'getDepositMethod']);
Route::get('/deposit/new/calculate-fee', [DepositController::class, 'calculateFee']);
