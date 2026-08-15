<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission Spatie agar tidak ada data stale
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
                // Dashboard
                'view dashboard',

                // User & Master Data
                'manage users',
                'manage interns',
                'manage mentors',
                'manage internship periods',
                'manage work schedules',
                'manage office settings',   // BARU

                // Attendance
                'view attendances',
                'manage attendances',
                'check in',
                'check out',

                // Leave Request
                'create leave request',
                'view leave requests',
                'manage leave requests',

                // Report
                'view reports',
                'export reports',
            ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $mentor = Role::firstOrCreate(['name' => 'mentor', 'guard_name' => 'web']);
        $intern = Role::firstOrCreate(['name' => 'intern', 'guard_name' => 'web']);

        $admin->syncPermissions(Permission::all());

        $mentor->syncPermissions([
            'view dashboard',
            'view attendances',
            'view leave requests',
            'manage leave requests',
            'view reports',
        ]);

        $intern->syncPermissions([
            'view dashboard',
            'check in',
            'check out',
            'create leave request',
        ]);
    }
}