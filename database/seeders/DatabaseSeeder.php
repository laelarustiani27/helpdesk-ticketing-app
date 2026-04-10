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
         User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap'  => 'Administrator',
                'tanggal_lahir' => '1990-01-01',
                'email'         => 'admin@netrespond.com',
                'no_telepon'    => '081234567890',
                'role'          => 'admin',
                'password'      => 'admin123',
                'is_active'     => true,
            ]
        );

        $this->call([
            TicketSeeder::class,
        ]);
    }
}
