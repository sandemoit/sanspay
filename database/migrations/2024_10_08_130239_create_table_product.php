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
        Schema::create('products_ppob', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('brand');
            $table->integer('price');
            $table->integer('mitra_price');
            $table->integer('cust_price');
            $table->integer('discount')->nullable();
            $table->integer('quota')->nullable();
            $table->string('note')->nullable();
            $table->enum('status', ['empty', 'available'])->default('available');
            $table->string('provider');
            $table->string('type');
            $table->string('label');
            $table->string('token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_ppob');
    }
};
