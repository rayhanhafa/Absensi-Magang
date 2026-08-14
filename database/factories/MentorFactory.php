<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MentorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nip' => fake()->unique()->numerify('##################'),
            'jabatan' => fake()->jobTitle(),
            'bagian' => fake()->randomElement(['Divisi IT', 'Divisi HR', 'Divisi Finance']),
        ];
    }
}