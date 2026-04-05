<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_laporan')->unique();
            $table->string('nama_pelapor');
            $table->string('no_telepon');
            $table->string('email')->nullable();
            $table->string('alamat');
            $table->string('jenis_masalah'); 
            $table->text('deskripsi');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'diproses', 'selesai'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_pelanggan');
    }
};