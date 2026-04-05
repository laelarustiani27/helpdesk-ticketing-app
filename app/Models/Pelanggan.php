<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'no_pelanggan',
        'nama',
        'no_telepon',
        'email',
        'alamat',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function laporan()
    {
        return $this->hasMany(LaporanPelanggan::class);
    }
}