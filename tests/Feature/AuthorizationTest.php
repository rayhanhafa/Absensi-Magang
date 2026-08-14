<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_dapat_mengakses_halaman_admin(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
    }

    public function test_intern_tidak_dapat_mengakses_halaman_admin(): void
    {
        $intern = User::factory()->create();
        $intern->assignRole('intern');

        $response = $this->actingAs($intern)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    public function test_mentor_tidak_dapat_mengakses_halaman_admin(): void
    {
        $mentor = User::factory()->create();
        $mentor->assignRole('mentor');

        $response = $this->actingAs($mentor)->get('/admin/dashboard');

        $response->assertForbidden();
    }
}