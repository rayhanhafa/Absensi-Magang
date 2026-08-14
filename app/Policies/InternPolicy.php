<?php

namespace App\Policies;

use App\Models\Intern;
use App\Models\User;

class InternPolicy
{
    /**
     * Admin dapat melihat semua data intern.
     * Mentor dapat melihat data intern yang dibimbingnya.
     * Intern hanya dapat melihat datanya sendiri.
     */
    public function view(User $user, Intern $intern): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('mentor')) {
            return $user->mentor && $intern->mentor_id === $user->mentor->id;
        }

        if ($user->hasRole('intern')) {
            return $user->intern && $intern->id === $user->intern->id;
        }

        return false;
    }
}