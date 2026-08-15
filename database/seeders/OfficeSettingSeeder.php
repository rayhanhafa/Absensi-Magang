<?php

namespace Database\Seeders;

use App\Models\OfficeSetting;
use Illuminate\Database\Seeder;

class OfficeSettingSeeder extends Seeder
{
    public function run(): void
    {
        OfficeSetting::firstOrCreate(
            ['name' => 'Kantor Pusat'],
            [
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius_meter' => 100,
                'is_active' => true,
            ]
        );
    }
}