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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'customer'])->after('email');
            $table->string('status')->default('active')->after('email');
            $table->string('image')->nullable()->after('email');
            $table->string('number')->nullable()->after('email');
            $table->integer('saldo')->default(0)->after('email');
            $table->integer('point')->default(0)->after('saldo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
