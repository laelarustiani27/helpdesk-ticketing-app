<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notif_enabled')->default(true)->after('is_active');
            $table->boolean('notif_ticket')->default(true)->after('notif_enabled');
            $table->boolean('notif_assign')->default(true)->after('notif_ticket');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notif_enabled', 'notif_ticket', 'notif_assign']);
        });
    }
};