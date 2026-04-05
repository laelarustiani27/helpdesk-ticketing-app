<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupTicketsCsv extends Command
{
    protected $signature   = 'tickets:backup';
    protected $description = 'Backup semua data tiket ke file CSV harian';

    public function handle(): void
    {
        $filename = 'backups/tickets_' . now()->format('Y-m-d') . '.csv';

        $tickets = Ticket::with(['pelapor', 'teknisi'])
            ->orderBy('created_at', 'desc')
            ->get();

        $rows = [];

        $rows[] = implode(',', [
            'No', 'Waktu Dibuat', 'Nama Tempat', 'Alamat', 'Koordinat',
            'No Telepon', 'No Lain', 'Email', 'Jenis Pemesanan',
            'Pelapor', 'Teknisi', 'Status', 'Prioritas', 'Lokasi',
            'Waktu Perangkat Mati', 'Waktu Selesai',
        ]);

        foreach ($tickets as $i => $ticket) {
            $rows[] = implode(',', array_map(
                fn($v) => '"' . str_replace('"', '""', $v ?? '-') . '"',
                [
                    $i + 1,
                    optional($ticket->created_at)->format('d/m/Y H:i:s'),
                    $ticket->nama_tempat     ?? '-',
                    $ticket->alamat          ?? '-',
                    $ticket->koordinat       ?? '-',
                    $ticket->no_telepon      ?? '-',
                    $ticket->no_lain         ?? '-',
                    $ticket->email           ?? '-',
                    $ticket->jenis_pemesanan ?? '-',
                    optional($ticket->pelapor)->nama_lengkap ?? optional($ticket->pelapor)->name ?? '-',
                    optional($ticket->teknisi)->name          ?? '-',
                    $ticket->status          ?? '-',
                    $ticket->priority        ?? '-',
                    $ticket->location        ?? '-',
                    optional($ticket->reported_at)->format('d/m/Y H:i:s') ?? '-',
                    optional($ticket->resolved_at)->format('d/m/Y H:i:s') ?? '-',
                ]
            ));
        }

        Storage::put($filename, chr(0xEF) . chr(0xBB) . chr(0xBF) . implode("\n", $rows));

        $this->info("Backup berhasil: {$filename} ({$tickets->count()} tiket)");
    }
}