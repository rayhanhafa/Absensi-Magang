<?php

namespace Database\Seeders;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MentorSeeder extends Seeder
{
    public function run(): void
    {
        $mentorsData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'mentor@example.com',
                'nip' => '198501012010011001',
                'jabatan' => 'Staff Senior',
                'bagian' => 'Divisi IT',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'mentor2@example.com',
                'nip' => '199003152015022002',
                'jabatan' => 'Supervisor',
                'bagian' => 'Divisi HR',
            ],
        ];

        foreach ($mentorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('mentor');

            Mentor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nip' => $data['nip'],
                    'jabatan' => $data['jabatan'],
                    'bagian' => $data['bagian'],
                ]
            );
        }
    }
}