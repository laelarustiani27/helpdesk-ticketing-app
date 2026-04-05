<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $tickets = [
            [
                'title' => 'Server Down - Main Database',
                'description' => 'Database server tidak dapat diakses. Semua aplikasi terdampak.',
                'status' => 'critical',
                'priority' => 'high',
                'location' => 'Data Center A - Rack 5',
                'reported_by' => 'Admin System',
                'assigned_to' => 'Teknisi Satu',
                'reported_at' => Carbon::now()->subHours(2),
                'resolved_at' => null,
            ],
            [
                'title' => 'Slow Network Connection',
                'description' => 'Koneksi internet di lantai 3 sangat lambat.',
                'status' => 'warning',
                'priority' => 'medium',
                'location' => 'Lantai 3 - Ruang Meeting',
                'reported_by' => 'Staff HR',
                'assigned_to' => 'Teknisi Dua',
                'reported_at' => Carbon::now()->subHours(5),
                'resolved_at' => null,
            ],
            [
                'title' => 'Printer Tidak Berfungsi',
                'description' => 'Printer Canon di ruang IT tidak bisa print.',
                'status' => 'resolved',
                'priority' => 'low',
                'location' => 'Lantai 2 - IT Room',
                'reported_by' => 'IT Support',
                'assigned_to' => 'Teknisi Satu',
                'reported_at' => Carbon::now()->subDays(1),
                'resolved_at' => Carbon::now()->subHours(3),
            ],
            [
                'title' => 'WiFi Not Connected',
                'description' => 'WiFi di area kantin tidak dapat terhubung.',
                'status' => 'warning',
                'priority' => 'medium',
                'location' => 'Lantai 1 - Kantin',
                'reported_by' => 'Staff Finance',
                'assigned_to' => null,
                'reported_at' => Carbon::now()->subMinutes(30),
                'resolved_at' => null,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}