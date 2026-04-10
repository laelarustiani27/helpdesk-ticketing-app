<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE teknisi MODIFY spesialisasi ENUM('Networking','Hardware','Software','CCTV','Lainnya') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE teknisi MODIFY spesialisasi ENUM('Networking','Hardware','Software','CCTV') NOT NULL");
    }
};