<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            try {
                $table->dropForeign(['assigned_to']);
            } catch (\Exception $e) {}

            $table->unsignedBigInteger('assigned_to')->nullable()->change();
            $table->foreign('assigned_to')
                ->references('id')
                ->on('teknisi')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->dropForeign(['assigned_to']);

            $table->integer('assigned_to')->nullable()->change();
        });
    }
};