<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_jadwal' => 'Jadwal Reguler',
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'toleransi_keterlambatan' => 15,
            'is_active' => true,
        ];
    }
}