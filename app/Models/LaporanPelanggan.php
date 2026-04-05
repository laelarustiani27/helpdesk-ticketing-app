<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPelanggan extends Model
{
    protected $table = 'laporan_pelanggan';

    protected $fillable = [
        'pelanggan_id',
        'nomor_laporan',
        'nama_pelapor',
        'no_telepon',
        'email',
        'alamat',
        'jenis_masalah',
        'deskripsi',
        'status',
        'catatan_admin',
        'ticket_id',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function ticket()
    {
        return $this->belongsTo(\App\Models\Ticket::class);
    }

    public static function generateNomor(): string
    {
        $prefix = 'RPT-' . date('Ymd') . '-';
        $last   = static::where('nomor_laporan', 'like', $prefix . '%')
                        ->orderByDesc('nomor_laporan')
                        ->first();
        $urut   = $last ? ((int) substr($last->nomor_laporan, -4)) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }
}