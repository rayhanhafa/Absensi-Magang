<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\WorkSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    /**
     * Melakukan proses check-in untuk seorang intern.
     *
     * @param  array{latitude?: float, longitude?: float, foto_check_in?: string}  $data
     */
    public function checkIn(Intern $intern, array $data = []): Attendance
    {
        return DB::transaction(function () use ($intern, $data) {
            $today = Carbon::today();

            $existing = Attendance::query()
                ->forIntern($intern->id)
                ->whereDate('tanggal', $today)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw ValidationException::withMessages([
                    'check_in' => 'Anda sudah melakukan absensi masuk hari ini.',
                ]);
            }

            $now = Carbon::now();
            $schedule = $this->getActiveSchedule();

            [$status, $keterlambatan] = $this->determineAttendanceStatus($now, $schedule);

            return Attendance::create([
                'intern_id' => $intern->id,
                'tanggal' => $today,
                'waktu_masuk' => $now->format('H:i:s'),
                'status' => $status,
                'keterlambatan' => $keterlambatan,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'foto_check_in' => $data['foto_check_in'] ?? null,
                'accuracy_check_in' => $data['accuracy_check_in'] ?? null,
                'distance_check_in' => $data['distance_check_in'] ?? null,
                'location_status_check_in' => $data['location_status_check_in'] ?? null,
            ]);
        });
    }

    /**
     * Melakukan proses check-out untuk seorang intern.
     *
     * @param  array{latitude?: float, longitude?: float, foto_check_out?: string}  $data
     */
    public function checkOut(Intern $intern, array $data = []): Attendance
    {
        return DB::transaction(function () use ($intern, $data) {
            $today = Carbon::today();

            $attendance = Attendance::query()
                ->forIntern($intern->id)
                ->whereDate('tanggal', $today)
                ->lockForUpdate()
                ->first();

            if (! $attendance) {
                throw ValidationException::withMessages([
                    'check_out' => 'Anda belum melakukan check-in.',
                ]);
            }

            if ($attendance->waktu_pulang) {
                throw ValidationException::withMessages([
                    'check_out' => 'Anda sudah melakukan absensi pulang hari ini.',
                ]);
            }

            $attendance->update([
                'waktu_pulang' => Carbon::now()->format('H:i:s'),
                'latitude' => $data['latitude'] ?? $attendance->latitude,
                'longitude' => $data['longitude'] ?? $attendance->longitude,
                'foto_check_out' => $data['foto_check_out'] ?? null,
                'accuracy_check_out' => $data['accuracy_check_out'] ?? $attendance->accuracy_check_out,
                'distance_check_out' => $data['distance_check_out'] ?? $attendance->distance_check_out,
                'location_status_check_out' => $data['location_status_check_out'] ?? $attendance->location_status_check_out,
            ]);

            return $attendance->fresh();
        });
    }

    /**
     * Menentukan status kehadiran (hadir/terlambat) dan menit keterlambatan
     * berdasarkan waktu check-in dan jadwal kerja aktif.
     *
     * @return array{0: string, 1: int} [status, keterlambatan_dalam_menit]
     */
    public function determineAttendanceStatus(\Carbon\Carbon $checkInTime, WorkSchedule $schedule): array
    {
        $jamMasuk = Carbon::parse($schedule->jam_masuk->format('H:i:s'))
            ->setDateFrom($checkInTime);

        $batasToleransi = $jamMasuk->copy()->addMinutes($schedule->toleransi_keterlambatan);

        if ($checkInTime->lessThanOrEqualTo($batasToleransi)) {
            return ['hadir', 0];
        }

        $keterlambatan = (int) $jamMasuk->diffInMinutes($checkInTime);

        return ['terlambat', $keterlambatan];
    }

    /**
     * Mengambil jadwal kerja aktif yang berlaku saat ini.
     *
     * MVP: jadwal bersifat global (satu jadwal untuk semua intern),
     * jadi cukup ambil record pertama yang tersedia.
     */
    public function getActiveSchedule(): WorkSchedule
{
    $schedule = WorkSchedule::where('is_active', true)->first();

    if (! $schedule) {
        throw ValidationException::withMessages([
            'schedule' => 'Jadwal kerja aktif belum dikonfigurasi. Hubungi admin.',
        ]);
    }

    return $schedule;
}
}