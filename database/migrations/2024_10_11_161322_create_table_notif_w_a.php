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
        Schema::create('notifikasi_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->text('trx_pending');
            $table->text('deposit_pending');
            $table->text('trx_success');
            $table->text('deposit_success');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_whatsapp');
    }
};
