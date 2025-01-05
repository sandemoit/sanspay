<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DigiflazzController;
use App\Http\Middleware\XSS;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
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

    // Route::post('/webhook', [DigiflazzController::class, 'handle']);
    Route::post('/webhook', function (Request $request) {
        $secret = 'sanspaysecret'; // Ganti dengan secret yang sesuai

        $post_data = file_get_contents('php://input');
        $signature = hash_hmac('sha1', $post_data, $secret);

        // Cek signature
        if ($request->header('X-Hub-Signature') == 'sha1=' . $signature) {
            // Proses data transaksi
            Log::info(json_decode($request->getContent(), true));
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'unauthorized'], 401);
        }
    });

    require __DIR__ . '/auth.php';
    require __DIR__ . '/admin.php';
    require __DIR__ . '/users.php';
    require __DIR__ . '/job.php';
});
