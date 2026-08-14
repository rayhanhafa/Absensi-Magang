<?php

namespace Database\Seeders;

use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
       WorkSchedule::firstOrCreate(
    ['nama_jadwal' => 'Jadwal Reguler'],
    [
        'jam_masuk' => '08:00:00',
        'jam_pulang' => '16:00:00',
        'toleransi_keterlambatan' => 15,
        'is_active' => true,
    ]
);
    }
}