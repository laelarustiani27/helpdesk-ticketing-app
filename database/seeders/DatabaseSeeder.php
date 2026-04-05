<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Akun admin
        User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'tanggal_lahir' => '1990-01-01',
            'email' => 'admin@netrespond.com',
            'no_telepon' => '081234567890',
            'role' => 'admin',
            'password' => 'admin123', 
            'is_active' => true,
        ]);

        // Akun teknisi 1
        User::create([
            'username' => 'tech1',
            'nama_lengkap' => 'Teknisi Satu',
            'tanggal_lahir' => '1995-05-05',
            'email' => 'tech1@netrespond.com',
            'no_telepon' => '081234567891',
            'role' => 'teknisi',
            'password' => 'tech123', 
            'is_active' => true,
        ]);

        // Akun teknisi 2
        User::create([
            'username' => 'tech2',
            'nama_lengkap' => 'Teknisi Dua',
            'tanggal_lahir' => '1993-08-20',
            'email' => 'tech2@netrespond.com',
            'no_telepon' => '081234567892',
            'role' => 'teknisi',
            'password' => 'tech123',
            'is_active' => true,
        ]);

        $this->call([
            TicketSeeder::class,
        ]);
    }
}