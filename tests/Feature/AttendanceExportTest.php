<?php

namespace Tests\Feature;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class AttendanceExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_dapat_mengunduh_laporan_excel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $intern = Intern::factory()->create();
        Attendance::factory()->create([
            'intern_id' => $intern->id,
            'latitude' => -6.975,
            'longitude' => 110.395,
            'accuracy_check_in' => 20,
            'distance_check_in' => 15.5,
            'location_status_check_in' => 'valid',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_export_mengandung_kolom_lokasi(): void
    {
        $intern = Intern::factory()->create();
        Attendance::factory()->create([
            'intern_id' => $intern->id,
            'latitude' => -6.975,
            'longitude' => 110.395,
            'accuracy_check_in' => 20,
            'distance_check_in' => 15.5,
            'location_status_check_in' => 'valid',
        ]);

        $export = new AttendanceExport();

        $this->assertContains('Latitude Check-in', $export->headings());
        $this->assertContains('Distance Check-in (m)', $export->headings());
        $this->assertContains('Status Lokasi Check-in', $export->headings());
    }

    public function test_mentor_tidak_dapat_mengakses_export(): void
    {
        $mentor = User::factory()->create();
        $mentor->assignRole('mentor');

        $response = $this->actingAs($mentor)->get(route('admin.reports.export'));

        $response->assertForbidden();
    }
}