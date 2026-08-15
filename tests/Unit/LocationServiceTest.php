<?php

namespace Tests\Unit;

use App\Models\OfficeSetting;
use App\Services\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LocationServiceTest extends TestCase
{
    use RefreshDatabase;

    private LocationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new LocationService();
    }

    public function test_calculate_distance_titik_sama_menghasilkan_nol(): void
    {
        $distance = $this->service->calculateDistance(-6.2000, 106.8167, -6.2000, 106.8167);

        $this->assertEquals(0.0, $distance);
    }

    public function test_calculate_distance_jakarta_ke_bandung_kurang_lebih_benar(): void
    {
        // Monas Jakarta ke Gedung Sate Bandung, jarak lurus (great-circle)
        // sekitar 115-120 km — dipakai untuk memastikan formula secara umum benar,
        // bukan mengejar presisi meter demi meter.
        $distance = $this->service->calculateDistance(-6.1754, 106.8272, -6.9024, 107.6186);

        $this->assertGreaterThan(110000, $distance);
        $this->assertLessThan(125000, $distance);
    }

    public function test_accuracy_diterima_jika_dalam_batas(): void
    {
        config(['attendance.max_accuracy_meters' => 100]);

        $this->assertTrue($this->service->isAccuracyAcceptable(50));
        $this->assertTrue($this->service->isAccuracyAcceptable(100));
    }

    public function test_accuracy_ditolak_jika_melebihi_batas(): void
    {
        config(['attendance.max_accuracy_meters' => 100]);

        $this->assertFalse($this->service->isAccuracyAcceptable(150));
    }

    public function test_validate_location_berhasil_dalam_radius(): void
    {
        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        // Titik sangat dekat (~11 meter dari kantor)
        $result = $this->service->validateLocation(-6.20005, 106.81675, 20);

        $this->assertArrayHasKey('distance', $result);
        $this->assertLessThanOrEqual(100, $result['distance']);
    }

    public function test_validate_location_gagal_di_luar_radius(): void
    {
        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        // Titik jauh (~1.2 km dari kantor)
        $this->service->validateLocation(-6.2100, 106.8167, 20);
    }

    public function test_validate_location_gagal_jika_accuracy_buruk(): void
    {
        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $this->service->validateLocation(-6.2000, 106.8167, 500);
    }

    public function test_validate_location_gagal_jika_office_belum_dikonfigurasi(): void
    {
        $this->expectException(ValidationException::class);

        $this->service->validateLocation(-6.2000, 106.8167, 20);
    }
}