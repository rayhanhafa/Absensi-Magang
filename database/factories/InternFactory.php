<?php

namespace Database\Factories;

use App\Models\InternshipPeriod;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternFactory extends Factory
{
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('-2 months', 'now');

        return [
            'user_id' => User::factory(),
            'mentor_id' => Mentor::factory(),
            'internship_period_id' => InternshipPeriod::factory(),
            'universitas' => fake()->randomElement(['Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada']),
            'jurusan' => fake()->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer']),
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => fake()->dateTimeBetween($mulai, '+3 months'),
            'status' => 'aktif',
        ];
    }
}