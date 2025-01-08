<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\ProductPpobController;
use App\Http\Controllers\Admin\ProfitController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\UsermanagementController;
use App\Http\Middleware\HasRoleAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', HasRoleAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // provider
    Route::get('/admin/provider', [ProviderController::class, 'index'])->name('provider');

    // order
    Route::get('/admin/order', [OrderController::class, 'index'])->name('order');
    Route::get('/admin/order/data', [OrderController::class, 'getData'])->name('order.data');
    Route::get('/admin/order/get-detail-trx/{id}', [OrderController::class, 'getDetailTrx'])->name('getDetailTrx');

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
    Route::post('/admin/configDigiFlazz', [ProviderController::class, 'configDigiFlazz'])->name('configDigiFlazz');
    Route::post('/admin/configMidtrans', [ProviderController::class, 'configMidtrans'])->name('configMidtrans');

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
    Route::get('/admin/deposit/payment', [DepositController::class, 'payment'])->name('admin.deposit.payment');
    Route::get('/admin/deposit/payment/get', [DepositController::class, 'getPayment']);
    Route::post('/admin/deposit/payment', [DepositController::class, 'storePayment'])->name('deposit.payment.store');
    Route::get('/admin/deposit/payment/delete/{id}', [DepositController::class, 'deletePayment'])->name('deposit.payment.delete');

    // ticket helpdesk
    Route::get('/admin/ticket', [TicketController::class, 'index'])->name('admin.ticket');
    Route::get('/admin/ticket/get', [TicketController::class, 'getData'])->name('admin.ticket.get');
    Route::get('/admin/ticket/{id}', [TicketController::class, 'detail'])->name('admin.ticket.detail');
    Route::put('/ticket/{id}', [TicketController::class, 'update'])->name('admin.ticket.update');

    // configuration
    Route::get('/admin/configure/website', [ConfigController::class, 'website'])->name('admin.configure.website');
    Route::put('/admin/configure/website', [ConfigController::class, 'updateWebsite'])->name('admin.configure.website');
    Route::get('/admin/configure/contact', [ConfigController::class, 'contact'])->name('admin.configure.contact');
    Route::put('/admin/configure/contact', [ConfigController::class, 'updateContact'])->name('admin.configure.contact');
    Route::get('/admin/configure/mail', [ConfigController::class, 'mail'])->name('admin.configure.mail');
    Route::put('/admin/configure/mail', [ConfigController::class, 'updateMail'])->name('admin.configure.mail');
    Route::get('/admin/configure/wa', [ConfigController::class, 'wa'])->name('admin.configure.wa');
    Route::put('/admin/configure/wa', [ConfigController::class, 'updateWa'])->name('admin.configure.wa');
    Route::post('/admin/configure/testwa', [ConfigController::class, 'testWa'])->name('admin.configure.testwa');

    // announcement
    Route::get('/admin/announcement', [AnnouncementController::class, 'index'])->name('admin.announcement');
    Route::get('/admin/announcement/get', [AnnouncementController::class, 'getData']);
    Route::post('/admin/announcement', [AnnouncementController::class, 'store'])->name('admin.announcement.store');
    Route::get('/admin/announcement/delete/{id}', [AnnouncementController::class, 'destroy']);

    // format notification
    Route::get('/admin/notification', [NotificationController::class, 'index'])->name('admin.notification');
    Route::put('/admin/notification', [NotificationController::class, 'updateNotification'])->name('admin.notification');

    // management user
    Route::get('/admin/upgrade-mitra', [UsermanagementController::class, 'upgradeMitra'])->name('admin.upgrade-mitra');
    Route::get('/getUpgradeMitra', [UsermanagementController::class, 'getUpgradeMitra'])->name('getUpgradeMitra');
    Route::get('/pengajuan/detail/{id}', [UsermanagementController::class, 'getDetailUpgrade'])->name('pengajuan.detail');
    Route::put('/pengajuan/update-status/{id}', [UsermanagementController::class, 'updateStatusUpgrade'])->name('pengajuan.update-status');
});
