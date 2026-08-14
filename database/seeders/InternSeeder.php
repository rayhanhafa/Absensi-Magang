<?php

namespace Database\Seeders;

use App\Models\Intern;
use App\Models\InternshipPeriod;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InternSeeder extends Seeder
{
    public function run(): void
    {
        $period = InternshipPeriod::where('nama_periode', 'MagangHub 2026')->first();
        $mentors = Mentor::all();

        if (! $period || $mentors->isEmpty()) {
            $this->command->warn('Periode magang atau mentor belum tersedia. Jalankan InternshipPeriodSeeder & MentorSeeder terlebih dahulu.');
            return;
        }

        $internsData = [
            ['name' => 'Andi Wijaya', 'universitas' => 'Universitas Indonesia', 'jurusan' => 'Teknik Informatika'],
            ['name' => 'Dewi Lestari', 'universitas' => 'Institut Teknologi Bandung', 'jurusan' => 'Sistem Informasi'],
            ['name' => 'Rian Pratama', 'universitas' => 'Universitas Gadjah Mada', 'jurusan' => 'Ilmu Komputer'],
            ['name' => 'Putri Ayu', 'universitas' => 'Universitas Diponegoro', 'jurusan' => 'Teknik Informatika'],
            ['name' => 'Fajar Nugroho', 'universitas' => 'Universitas Airlangga', 'jurusan' => 'Sistem Informasi'],
        ];

        // User dummy khusus untuk login cepat saat testing
        $mainInternUser = User::firstOrCreate(
            ['email' => 'intern@example.com'],
            [
                'name' => 'Peserta Magang Utama',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $mainInternUser->assignRole('intern');

        Intern::firstOrCreate(
            ['user_id' => $mainInternUser->id],
            [
                'mentor_id' => $mentors->first()->id,
                'internship_period_id' => $period->id,
                'universitas' => 'Universitas Indonesia',
                'jurusan' => 'Teknik Informatika',
                'tanggal_mulai' => $period->tanggal_mulai,
                'tanggal_selesai' => $period->tanggal_selesai,
                'status' => 'aktif',
            ]
        );

        foreach ($internsData as $index => $data) {
            $email = 'intern' . ($index + 2) . '@example.com';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('intern');

            Intern::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'mentor_id' => $mentors->random()->id,
                    'internship_period_id' => $period->id,
                    'universitas' => $data['universitas'],
                    'jurusan' => $data['jurusan'],
                    'tanggal_mulai' => $period->tanggal_mulai,
                    'tanggal_selesai' => $period->tanggal_selesai,
                    'status' => 'aktif',
                ]
            );
        }
    }
}