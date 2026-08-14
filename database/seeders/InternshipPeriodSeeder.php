<?php

namespace Database\Seeders;

use App\Models\InternshipPeriod;
use Illuminate\Database\Seeder;

class InternshipPeriodSeeder extends Seeder
{
    public function run(): void
    {
        InternshipPeriod::firstOrCreate(
            ['nama_periode' => 'MagangHub 2026'],
            [
                'tanggal_mulai' => '2026-08-01',
                'tanggal_selesai' => '2026-10-31',
                'status' => 'aktif',
            ]
        );
    }
}