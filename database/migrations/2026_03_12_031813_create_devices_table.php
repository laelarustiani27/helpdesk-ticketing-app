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
            Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('ip_address');
            $table->string('lokasi')->nullable();
            $table->enum('status', ['up', 'down'])->default('up');
            $table->timestamp('last_down_at')->nullable();
            $table->timestamp('last_up_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
