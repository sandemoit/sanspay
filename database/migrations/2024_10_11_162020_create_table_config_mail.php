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
        Schema::create('mail_config', function (Blueprint $table) {
            $table->id();
            $table->string('SMTPHost');
            $table->string('SMTPPassword');
            $table->string('SMTPUsername');
            $table->string('SMTPPort');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_config');
    }
};
