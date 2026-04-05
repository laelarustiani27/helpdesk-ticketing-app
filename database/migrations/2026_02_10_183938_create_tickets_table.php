<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('nama_tempat')->nullable();
            $table->text('alamat')->nullable();
            $table->string('koordinat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('no_lain')->nullable();
            $table->string('email')->nullable();
            $table->string('jenis_pemesanan')->nullable();
            $table->enum('status', ['open', 'in_progress', 'critical', 'warning', 'resolved'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('location');
            $table->string('reported_by');
            $table->string('assigned_to')->nullable();
            $table->timestamp('reported_at');
            $table->timestamp('resolved_at')->nullable();
            $table->boolean('is_active')->default(true); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
