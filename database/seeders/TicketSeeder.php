<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ticket::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $admin = User::where('username', 'admin')->first()->id;

        $tickets = [
            [
                'title'       => 'Server Down - Main Database',
                'description' => 'Database server tidak dapat diakses. Semua aplikasi terdampak.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Data Center A - Rack 5',
                'reported_by' => $admin,
                'assigned_to' => null, 
                'reported_at' => Carbon::now()->subHours(2),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Slow Network Connection',
                'description' => 'Koneksi internet di lantai 3 sangat lambat.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Lantai 3 - Ruang Meeting',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(5),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Printer Tidak Berfungsi',
                'description' => 'Printer Canon di ruang IT tidak bisa print.',
                'status'      => 'open',
                'priority'    => 'low',
                'location'    => 'Lantai 2 - IT Room',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subDays(1),
                'resolved_at' => null,
            ],
            [
                'title'       => 'WiFi Not Connected',
                'description' => 'WiFi di area kantin tidak dapat terhubung.',
                'status'      => 'open',
                'priority'    => 'medium',
                'location'    => 'Lantai 1 - Kantin',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subMinutes(30),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Email Server Error',
                'description' => 'Email tidak bisa terkirim sejak pagi.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Server Room',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(3),
                'resolved_at' => null,
            ],
            [
                'title'       => 'CCTV Offline',
                'description' => 'Kamera CCTV lantai 2 tidak merekam.',
                'status'      => 'open',
                'priority'    => 'medium',
                'location'    => 'Lantai 2',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(6),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Komputer Tidak Bisa Booting',
                'description' => 'PC di meja resepsionis tidak mau menyala.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Lantai 1 - Resepsionis',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(1),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Backup Data Gagal',
                'description' => 'Proses backup otomatis gagal malam tadi.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Data Center',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(8),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Switch Port Rusak',
                'description' => 'Port 12 di switch utama tidak terdeteksi.',
                'status'      => 'open',
                'priority'    => 'medium',
                'location'    => 'Ruang Server',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subHours(4),
                'resolved_at' => null,
            ],
            [
                'title'       => 'Software Crash Berulang',
                'description' => 'Aplikasi ERP crash setiap 30 menit.',
                'status'      => 'open',
                'priority'    => 'high',
                'location'    => 'Lantai 3 - Finance',
                'reported_by' => $admin,
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subDays(2),
                'resolved_at' => null,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}