<?php

namespace Database\Factories;

use App\Models\Intern;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        $mulai = now()->addDays(fake()->numberBetween(1, 10));

        return [
            'intern_id' => Intern::factory(),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $mulai,
            'jenis' => fake()->randomElement(['izin', 'sakit']),
            'alasan' => fake()->sentence(10),
            'status' => 'pending',
        ];
    }
}