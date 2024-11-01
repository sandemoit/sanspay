<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\ProductPpobController;
use App\Http\Controllers\Admin\ProfitController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Middleware\HasRoleAdmin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', HasRoleAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // provider
    Route::get('/admin/provider', [ProviderController::class, 'index'])->name('provider');

    // order
    Route::get('/admin/order', [OrderController::class, 'index'])->name('order');
    Route::get('/admin/order/data', [OrderController::class, 'getData'])->name('order.data');

    // product pulsa ppob
    Route::get('/admin/pulsa-ppob/product', [ProductPpobController::class, 'index'])->name('pulsa-ppob.product');
    Route::get('/admin/pulsa-ppob/product/get-product', [ProductPpobController::class, 'getData']);
    Route::get('/admin/pulsa-ppob/product/get-brands', [ProductPpobController::class, 'getBrands']);
    Route::post('/admin/pulsa-ppob/product/add-product', [ProductPpobController::class, 'addProduct']);
    Route::get('/admin/pulsa-ppob/product/delete/{id}', [ProductPpobController::class, 'deleteProduct']);
    Route::get('/admin/pulsa-ppob/product/get-product/{id}', [ProductPpobController::class, 'getProduct']);
    Route::put('/admin/pulsa-ppob/product/update-product/{id}', [ProductPpobController::class, 'updateProduct']);
    Route::get('/admin/pulsa-ppob/product/deleteAllProduct', [ProductPpobController::class, 'deleteAllProduct'])->name('pulsa-ppob.product.deleteAll');

    // category pulsa ppob
    Route::get('/admin/pulsa-ppob/category', [CategoryController::class, 'index'])->name('pulsa-ppob.category');
    Route::get('/admin/pulsa-ppob/get-category', [CategoryController::class, 'getData']);
    Route::get('/admin/pulsa-ppob/get-category/{id}', [CategoryController::class, 'getCategory']);
    Route::put('/admin/pulsa-ppob/update-category/{id}', [CategoryController::class, 'updateCategory']);
    Route::get('/admin/pulsa-ppob/delete/{id}', [CategoryController::class, 'deleteCategory']);

    // profit pulsa ppob
    Route::get('/admin/pulsa-ppob/profit', [ProfitController::class, 'index'])->name('pulsa-ppob.profit');
    Route::put('/admin/pulsa-ppob/profit', [ProfitController::class, 'update'])->name('pulsa-ppob.profit');

    // point pulsa ppob
    Route::get('/admin/pulsa-ppob/point', [PointController::class, 'index'])->name('pulsa-ppob.point');
    Route::put('/admin/pulsa-ppob/point', [PointController::class, 'update'])->name('pulsa-ppob.point');

    // get saldo dan configuration
    Route::get('/admin/configurationDIgiFlazz', [ProviderController::class, 'configurationDIgiFlazz'])->name('configurationDIgiFlazz');

    // deposit
    Route::get('/admin/deposit/management', [DepositController::class, 'index'])->name('admin.deposit');
    Route::get('/admin/deposit/management/get', [DepositController::class, 'getData'])->name('admin.deposit.get');
    Route::get('/admin/deposit/management/{id}/{action}', [DepositController::class, 'actionDeposit']);
    Route::get('/admin/deposit/methode', [DepositController::class, 'methodDeposit'])->name('admin.deposit.metode');
    Route::get('/admin/deposit/methode/get', [DepositController::class, 'getDataMethod'])->name('admin.deposit.metode.get');
    Route::post('/admin/deposit/methode', [DepositController::class, 'methodStore'])->name('deposit.method.store');
    Route::get('/admin/deposit/methode/delete/{id}', [DepositController::class, 'deleteMethod'])->name('deposit.method.delete');
    Route::get('/admin/deposit/methode/get/{id}', [DepositController::class, 'getDataMethodEdit']);
    Route::put('/admin/deposit/methode/update/', [DepositController::class, 'methodUpdate'])->name('deposit.method.update');

    // ticket helpdesk
    Route::get('/admin/ticket', [TicketController::class, 'index'])->name('admin.ticket');
    Route::get('/admin/ticket/get', [TicketController::class, 'getData'])->name('admin.ticket.get');
    Route::get('/admin/ticket/{id}', [TicketController::class, 'detail'])->name('admin.ticket.detail');
});
