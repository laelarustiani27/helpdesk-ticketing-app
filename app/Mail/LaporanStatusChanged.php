<?php

namespace App\Mail;

use App\Models\LaporanPelanggan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaporanStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $laporan;

    public function __construct(LaporanPelanggan $laporan)
    {
        $this->laporan = $laporan;
    }

    public function build()
    {
        return $this->subject('Update Status Laporan #' . $this->laporan->nomor_laporan)
                    ->view('emails.laporan-status');
    }
}