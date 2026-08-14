<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('mentor')) {
            return $user->mentor
                && $leaveRequest->intern->mentor_id === $user->mentor->id;
        }

        if ($user->hasRole('intern')) {
            return $user->intern
                && $leaveRequest->intern_id === $user->intern->id;
        }

        return false;
    }

    /**
     * Menyetujui/menolak izin — hanya admin, atau mentor yang membimbing
     * intern bersangkutan.
     */
    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('mentor')) {
            return $user->mentor
                && $leaveRequest->intern->mentor_id === $user->mentor->id;
        }

        return false;
    }
}