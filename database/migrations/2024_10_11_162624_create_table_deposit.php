<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposit', function (Blueprint $table) {
            $table->id();
            $table->string('topup_id')->unique(); // ID topup unik untuk setiap transaksi
            $table->foreignId('member_id')->constrained('users')->onDelete('cascade'); // User yang melakukan deposit
            $table->string('payment_method'); // Metode pembayaran yang digunakan (contoh: "Midtrans - BNI VA")
            $table->integer('amount'); // Jumlah yang di-top-up oleh user
            $table->integer('total_transfer'); // Total yang harus ditransfer (amount + code_unique + fee, jika ada)
            $table->string('status')->default('pending'); // Status transaksi ('pending', 'success', 'failed')
            $table->string('snap_token')->nullable(); // URL untuk user melakukan pembayaran melalui Midtrans
            $table->string('redirect_url')->nullable(); // URL untuk user melakukan pembayaran melalui Midtrans
            $table->timestamp('payment_expiry')->nullable(); // Waktu kedaluwarsa pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit');
    }
};
