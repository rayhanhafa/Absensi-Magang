<?php

namespace Database\Factories;

use App\Models\Intern;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'intern_id' => Intern::factory(),
            'tanggal' => now()->format('Y-m-d'),
            'waktu_masuk' => '08:00:00',
            'waktu_pulang' => null,
            'status' => 'hadir',
            'keterlambatan' => 0,
        ];
    }

    public function terlambat(int $menit = 20): static
    {
        return $this->state(fn () => [
            'status' => 'terlambat',
            'keterlambatan' => $menit,
        ]);
    }

    public function sudahPulang(): static
    {
        return $this->state(fn () => [
            'waktu_pulang' => '16:00:00',
        ]);
    }
}