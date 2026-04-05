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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();

            // ID pelanggan (boleh null jika belum terhubung langsung)
            $table->unsignedBigInteger('customer_id')->nullable();

            // Jenis gangguan hasil algoritma (diperlukan oleh network:monitor)
            $table->string('issue_type');

            // Prioritas gangguan (contoh: LOW, MEDIUM, HIGH, CRITICAL)
            $table->string('priority');

            // Tingkat keparahan (diperlukan oleh incident:scan)
            $table->string('severity')->nullable();

            // Status penanganan tiket (OPEN, ON_PROGRESS, DONE)
            $table->string('status')->default('OPEN');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};