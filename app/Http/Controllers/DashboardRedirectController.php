<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match (true) {
            $user->hasRole('admin') => redirect()->route('admin.dashboard'),
            $user->hasRole('mentor') => redirect()->route('mentor.dashboard'),
            $user->hasRole('intern') => redirect()->route('intern.dashboard'),
            default => redirect()->route('login')->withErrors([
                'email' => 'Akun Anda belum memiliki role yang valid. Hubungi admin.',
            ]),
        };
    }
}