<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DigiflazzController;
use App\Http\Middleware\XSS;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(XSS::class)->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::post('/webhook', [DigiflazzController::class, 'handle'])->name('webhook');

    require __DIR__ . '/auth.php';
    require __DIR__ . '/admin.php';
    require __DIR__ . '/users.php';
    require __DIR__ . '/job.php';
});
