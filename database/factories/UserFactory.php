<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'nama_lengkap' => $this->faker->name(),  // ganti 'name' jadi 'nama_lengkap'
            'tanggal_lahir' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // default password
            'role' => 'teknisi',
            'no_telepon' => $this->faker->phoneNumber(),
            'is_active' => true,
            'remember_token' => Str::random(10),
        ];
    }
}
