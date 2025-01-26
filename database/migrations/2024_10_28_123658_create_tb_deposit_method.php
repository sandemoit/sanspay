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
        Schema::create('deposit_method', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('data')->nullable(); // Nama akun atau nomor akun (opsional untuk auto)
            $table->double('rate')->default('0');
            $table->double('fee')->default('0');
            $table->enum('xfee', ['-', '%'])->default('-');
            $table->integer('minimum');
            $table->integer('maximum')->nullable();
            $table->enum('type_payment', ['va', 'gopay', 'shopeepay', 'other_qris', 'cstore', 'akulaku', 'kredivo']);
            $table->boolean('is_auto')->default(false);
            $table->boolean('is_midtrans')->default(false); // Midtrans boolean untuk tipe auto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_method');
    }
};
