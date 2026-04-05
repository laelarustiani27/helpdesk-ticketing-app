<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'nama_lengkap' => 'Administrator',
                'tanggal_lahir' => '1990-01-01',
                'email' => 'admin@example.com',
                'no_telepon' => '081234567890',
                'is_active' => true,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'teknisi1',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'nama_lengkap' => 'Teknisi Satu',
                'tanggal_lahir' => '1995-05-05',
                'email' => 'teknisi1@example.com',
                'no_telepon' => '081234567891',
                'is_active' => true,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
