<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipPeriodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_periode' => 'Periode ' . fake()->year(),
            'tanggal_mulai' => now()->subMonth(),
            'tanggal_selesai' => now()->addMonths(2),
            'status' => 'aktif',
        ];
    }
}