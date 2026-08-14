<?php

namespace Tests\Feature;

use App\Models\Intern;
use App\Models\LeaveRequest;
use App\Models\Mentor;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LeaveRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        Storage::fake('private');
    }

    public function test_peserta_dapat_mengajukan_izin(): void
    {
        $user = User::factory()->create();
        $user->assignRole('intern');
        $intern = Intern::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('intern.leave-requests.store'), [
            'jenis' => 'izin',
            'tanggal_mulai' => now()->addDay()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDay()->format('Y-m-d'),
            'alasan' => 'Ada keperluan keluarga yang mendesak.',
            'bukti' => UploadedFile::fake()->image('bukti.jpg'),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('leave_requests', [
            'intern_id' => $intern->id,
            'jenis' => 'izin',
            'status' => 'pending',
        ]);

        Storage::disk('private')->assertExists(LeaveRequest::first()->bukti);
    }

    public function test_mentor_dapat_approve_izin_peserta_bimbingannya(): void
    {
        $mentorUser = User::factory()->create();
        $mentorUser->assignRole('mentor');
        $mentor = Mentor::factory()->create(['user_id' => $mentorUser->id]);

        $intern = Intern::factory()->create(['mentor_id' => $mentor->id]);
        $leaveRequest = LeaveRequest::factory()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($mentorUser)->put(
            route('mentor.leave-requests.update-status', $leaveRequest),
            ['status' => 'approved']
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'approved',
            'approved_by' => $mentorUser->id,
        ]);
    }

    public function test_mentor_dapat_reject_izin_dengan_alasan(): void
    {
        $mentorUser = User::factory()->create();
        $mentorUser->assignRole('mentor');
        $mentor = Mentor::factory()->create(['user_id' => $mentorUser->id]);

        $intern = Intern::factory()->create(['mentor_id' => $mentor->id]);
        $leaveRequest = LeaveRequest::factory()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($mentorUser)->put(
            route('mentor.leave-requests.update-status', $leaveRequest),
            [
                'status' => 'rejected',
                'catatan_approval' => 'Bukti pendukung kurang jelas.',
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'rejected',
            'catatan_approval' => 'Bukti pendukung kurang jelas.',
        ]);
    }

    public function test_reject_tanpa_alasan_ditolak_validasi(): void
    {
        $mentorUser = User::factory()->create();
        $mentorUser->assignRole('mentor');
        $mentor = Mentor::factory()->create(['user_id' => $mentorUser->id]);

        $intern = Intern::factory()->create(['mentor_id' => $mentor->id]);
        $leaveRequest = LeaveRequest::factory()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($mentorUser)->put(
            route('mentor.leave-requests.update-status', $leaveRequest),
            ['status' => 'rejected']
        );

        $response->assertSessionHasErrors('catatan_approval');
        $this->assertDatabaseHas('leave_requests', [
            'id' => $leaveRequest->id,
            'status' => 'pending',
        ]);
    }

    public function test_mentor_tidak_dapat_approve_izin_bukan_bimbingannya(): void
    {
        $mentorUser = User::factory()->create();
        $mentorUser->assignRole('mentor');
        Mentor::factory()->create(['user_id' => $mentorUser->id]);

        // Intern ini dibimbing mentor LAIN, bukan $mentorUser
        $internLain = Intern::factory()->create();
        $leaveRequest = LeaveRequest::factory()->create(['intern_id' => $internLain->id]);

        $response = $this->actingAs($mentorUser)->put(
            route('mentor.leave-requests.update-status', $leaveRequest),
            ['status' => 'approved']
        );

        $response->assertForbidden();
    }
}