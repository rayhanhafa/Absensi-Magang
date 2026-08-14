<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Admin dapat melihat semua absensi.
     * Mentor dapat melihat absensi peserta yang dibimbingnya.
     * Intern hanya dapat melihat absensinya sendiri.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('mentor')) {
            return $user->mentor
                && $attendance->intern->mentor_id === $user->mentor->id;
        }

        if ($user->hasRole('intern')) {
            return $user->intern
                && $attendance->intern_id === $user->intern->id;
        }

        return false;
    }

    /**
     * Hanya admin yang dapat mengoreksi/mengubah data absensi
     * (sesuai permission "manage attendances").
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->hasRole('admin');
    }
}