<?php

namespace Tests\Feature;

use App\Models\OfficeSetting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfficeSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_dapat_mengubah_office_location(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post(route('admin.office-settings.store'), [
            'name' => 'Kantor Cabang',
            'latitude' => -6.9750,
            'longitude' => 110.3958,
            'radius_meter' => 150,
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('office_settings', ['name' => 'Kantor Cabang']);
    }

    public function test_hanya_admin_dapat_mengubah_office_location(): void
    {
        $mentor = User::factory()->create();
        $mentor->assignRole('mentor');

        $response = $this->actingAs($mentor)->post(route('admin.office-settings.store'), [
            'name' => 'Kantor Cabang',
            'latitude' => -6.9750,
            'longitude' => 110.3958,
            'radius_meter' => 150,
        ]);

        $response->assertForbidden();
    }

    public function test_menyimpan_lokasi_aktif_baru_menonaktifkan_lokasi_lain(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $lokasiLama = OfficeSetting::factory()->create(['is_active' => true]);

        $this->actingAs($admin)->post(route('admin.office-settings.store'), [
            'name' => 'Kantor Baru',
            'latitude' => -6.9750,
            'longitude' => 110.3958,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $this->assertFalse($lokasiLama->fresh()->is_active);
    }
}