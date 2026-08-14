<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\User;
use App\Models\WorkSchedule;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceTest extends TestCase
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
    }

    private function loginAsIntern(): Intern
    {
        $user = User::factory()->create();
        $user->assignRole('intern');

        $intern = Intern::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        return $intern;
    }

    public function test_peserta_dapat_check_in(): void
    {
        $intern = $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-in'));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
    'intern_id' => $intern->id,
]);

$attendance = Attendance::first();
$this->assertTrue($attendance->tanggal->isToday());
    }

    public function test_peserta_tidak_dapat_check_in_dua_kali(): void
    {
        $intern = $this->loginAsIntern();

        $this->post(route('intern.attendance.check-in'));
        $response = $this->post(route('intern.attendance.check-in'));

        $response->assertSessionHasErrors('check_in');
        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_peserta_dapat_check_out(): void
    {
        $intern = $this->loginAsIntern();

        $this->post(route('intern.attendance.check-in'));
        $response = $this->post(route('intern.attendance.check-out'));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('attendances', [
            'intern_id' => $intern->id,
        ]);

        $attendance = Attendance::first();
        $this->assertNotNull($attendance->waktu_pulang);
    }

    public function test_peserta_tidak_dapat_check_out_sebelum_check_in(): void
    {
        $this->loginAsIntern();

        $response = $this->post(route('intern.attendance.check-out'));

        $response->assertSessionHasErrors('check_out');
        $this->assertDatabaseCount('attendances', 0);
    }

    public function test_peserta_tidak_dapat_mengakses_absensi_peserta_lain(): void
    {
        $this->loginAsIntern();

        $internLain = Intern::factory()->create();
        $attendanceLain = Attendance::factory()->create(['intern_id' => $internLain->id]);

        $response = $this->get(route('attendance.show', $attendanceLain));

        $response->assertForbidden();
    }
}