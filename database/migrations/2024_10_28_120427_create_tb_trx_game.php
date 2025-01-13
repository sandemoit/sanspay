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
        Schema::create('trx_game', function (Blueprint $table) {
            $table->id();
            $table->string('id_order');
            $table->integer('user_id');
            $table->string('code');
            $table->string('name');
            $table->string('data');
            $table->string('sn');
            $table->integer('price');
            $table->float('profit')->default(0);
            $table->integer('point');
            $table->enum('refund', [1, 0])->default(0);
            $table->string('note');
            $table->string('status');
            $table->string('from');
            $table->enum('type', ['pospaid', 'prepaid']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trx_game');
    }
};
