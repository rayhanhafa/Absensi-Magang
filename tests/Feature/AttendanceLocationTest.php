<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\OfficeSetting;
use App\Models\User;
use App\Models\WorkSchedule;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceLocationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        WorkSchedule::factory()->create([
            'jam_masuk' => '08:00:00',
            'jam_pulang' => '16:00:00',
            'toleransi_keterlambatan' => 15,
            'is_active' => true,
        ]);

        Storage::fake('private');
    }

    private function loginAsIntern(): Intern
    {
        $user = User::factory()->create();
        $user->assignRole('intern');

        $intern = Intern::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        return $intern;
    }

    public function test_check_in_tanpa_lokasi_tetap_berfungsi_saat_flag_nonaktif(): void
    {
        config(['attendance.require_location' => false]);

        $intern = $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', ['intern_id' => $intern->id]);
    }

    public function test_check_in_wajib_lokasi_saat_flag_aktif(): void
    {
        config(['attendance.require_location' => true]);

        $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'));

        $response->assertSessionHasErrors(['latitude', 'longitude', 'accuracy', 'foto_check_in']);
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_check_in_berhasil_dengan_lokasi_valid_dalam_radius(): void
    {
        config(['attendance.require_location' => true]);

        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $intern = $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'), [
            'latitude' => -6.20005,
            'longitude' => 106.81675,
            'accuracy' => 20,
            'foto_check_in' => UploadedFile::fake()->image('selfie.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $attendance = \App\Models\Attendance::first();
        $this->assertEquals('valid', $attendance->location_status_check_in);
        $this->assertNotNull($attendance->distance_check_in);
        Storage::disk('private')->assertExists($attendance->foto_check_in);
    }

    public function test_check_in_ditolak_jika_di_luar_radius(): void
    {
        config(['attendance.require_location' => true]);

        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'), [
            'latitude' => -6.2100, // ~1.2km dari kantor
            'longitude' => 106.8167,
            'accuracy' => 20,
            'foto_check_in' => UploadedFile::fake()->image('selfie.jpg'),
        ]);

        $response->assertSessionHasErrors('location');
        $this->assertDatabaseCount('attendances', 0);
        Storage::disk('private')->assertDirectoryEmpty('attendance-photos');
    }

    public function test_check_in_ditolak_jika_accuracy_terlalu_buruk(): void
    {
        config(['attendance.require_location' => true, 'attendance.max_accuracy_meters' => 100]);

        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'), [
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'accuracy' => 500,
            'foto_check_in' => UploadedFile::fake()->image('selfie.jpg'),
        ]);

        $response->assertSessionHasErrors('location');
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_check_out_menyimpan_data_lokasi(): void
    {
        config(['attendance.require_location' => true]);

        OfficeSetting::factory()->create([
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'radius_meter' => 100,
            'is_active' => true,
        ]);

        $intern = $this->loginAsIntern();

        $this->post(route('intern.attendance.check-in'), [
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'accuracy' => 20,
            'foto_check_in' => UploadedFile::fake()->image('selfie-in.jpg'),
        ]);

        $response = $this->post(route('intern.attendance.check-out'), [
            'latitude' => -6.2000,
            'longitude' => 106.8167,
            'accuracy' => 15,
            'foto_check_out' => UploadedFile::fake()->image('selfie-out.jpg'),
        ]);

        $response->assertRedirect();

        $attendance = \App\Models\Attendance::where('intern_id', $intern->id)->first();
        $this->assertEquals('valid', $attendance->location_status_check_out);
        $this->assertNotNull($attendance->foto_check_out);
    }
}