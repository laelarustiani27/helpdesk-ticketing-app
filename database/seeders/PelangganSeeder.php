<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        Pelanggan::create([
            'no_pelanggan' => 'PLG-0001',
            'nama'         => 'Budi Santoso',
            'no_telepon'   => '081234567890',
            'email'        => 'budi@email.com',
            'alamat'       => 'Jl. Contoh No. 1',
            'password'     => Hash::make('pelanggan123'),
        ]);
    }
}