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
            $table->string('fullname')->after('name');
            $table->enum('gender', ['Laki - laki', 'Perempuan'])->nullable()->after('email');
            $table->string('full_address')->nullable()->after('image');
            $table->string('selfie_ktp')->nullable()->after('image');
            $table->string('no_ktp')->nullable()->after('image');
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
