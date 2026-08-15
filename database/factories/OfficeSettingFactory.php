<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Kantor Pusat',
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ];
    }
}