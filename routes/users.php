<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\HistoryOrderController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\HasRoleCustomer;
use App\Http\Middleware\NotCustomerAccess;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\HasBlocked;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(HasBlocked::class)->group(function () {
        // profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // deposit
        Route::get('/deposit', [DepositController::class, 'index'])->name('deposit');
        Route::get('/deposit/get', [DepositController::class, 'getData'])->name('deposit.get');
        Route::get('/deposit/new', [DepositController::class, 'request'])->name('deposit.new');
        Route::post('/deposit/new', [DepositController::class, 'store'])->name('deposit.store');
        Route::get('/deposit/invoice/{topup_id}', [DepositController::class, 'invoice'])->name('deposit.invoice');
        Route::get('/deposit/new/getMethod', [DepositController::class, 'getDepositMethod']);
        Route::get('/deposit/new/calculate-fee', [DepositController::class, 'calculateFee']);
        Route::get('/deposit/cancel/{topup_id}', [DepositController::class, 'depositCancel'])->name('deposit.cancel');
        Route::get('/deposit/cancelMidtrans/{topup_id}', [DepositController::class, 'depositCancelMidtrans'])->name('deposit.cancelMidtrans');
        Route::middleware(NotCustomerAccess::class)->group(function () {
            Route::get('/deposit/send-saldo', [DepositController::class, 'sendSaldo'])->name('send.saldo');
            Route::post('/deposit/send-saldo', [DepositController::class, 'sendSaldoStore'])->name('send.saldo');
        });
        Route::get('/getDataKirimSaldo', [DepositController::class, 'getKirimSaldo'])->name('getDataKirimSaldo');
        Route::post('/profile/tukar-point', [DepositController::class, 'tukarPoint'])->name('profile.tukarPoint');

        Route::get('/order/history', [HistoryOrderController::class, 'historyTransaksi'])->name('order.history');
        Route::get('/order/get-history', [HistoryOrderController::class, 'getData'])->name('order.get-history');
        Route::get('/order/get-detail-trx/{id}', [HistoryOrderController::class, 'getDetailTrx'])->name('getDetailTrx');

        Route::middleware(HasRoleCustomer::class)->group(function () {
            Route::get('/upgrade', [DashboardController::class, 'upgradeMitra'])->name('upgrade.mitra');
            Route::post('/upgrade', [DashboardController::class, 'saveUpgradeMitra'])->name('upgrade.mitra');
        });

        // Route tanpa middleware untuk hasil upgrade
        Route::get('/upgrade/result', [DashboardController::class, 'upgradeStatus'])->name('upgrade.result');

        // program referral
        Route::get('/program-referral', [ReferralController::class, 'index'])->name('program.referral');
        Route::get('/referral/get', [ReferralController::class, 'getData'])->name('referral.getData');
        Route::get('/referral/{id}', [ReferralController::class, 'detail'])->name('referral.detail');

        // ticket
        Route::get('/ticket', [TicketController::class, 'index'])->name('ticket');
        Route::get('/ticket/create', [TicketController::class, 'create'])->name('ticket.create');
        Route::post('/ticket', [TicketController::class, 'store'])->name('ticket.store');
        Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
        Route::post('/ticket/{id}/reply', [TicketController::class, 'reply'])->name('ticket.reply');

        // laporan trx
        Route::get('/laporan/prabayar', [LaporanController::class, 'prabayar'])->name('laporan.prabayar');
        Route::get('/laporan/mutation', [LaporanController::class, 'mutation'])->name('laporan.mutation');
        Route::get('/laporan/prabayar/detail/{id}', [LaporanController::class, 'prabayarDetail'])->name('laporan.prabayar.detail');

        // order
        require __DIR__ . '/order.php';
    });
});


// page announcement
Route::get('/announcement/{slug}', [DashboardController::class, 'announcement'])->name('announcement');
