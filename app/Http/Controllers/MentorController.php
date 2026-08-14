<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreMentorRequest;
use App\Http\Requests\UpdateMentorRequest;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class MentorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'permission:manage mentors',
        ];
    }

    public function index(): View
    {
        $mentors = Mentor::with('user')
            ->withCount('interns')
            ->latest()
            ->paginate(15);

        return view('admin.mentors.index', ['mentors' => $mentors]);
    }

    public function create(): View
    {
        return view('admin.mentors.create');
    }

    public function store(StoreMentorRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('mentor');

            $user->mentor()->create([
                'nip' => $validated['nip'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'bagian' => $validated['bagian'] ?? null,
            ]);
        });

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Data mentor berhasil ditambahkan.');
    }

    public function show(Mentor $mentor): View
    {
        $mentor->load(['user', 'interns.user']);

        return view('admin.mentors.show', ['mentor' => $mentor]);
    }

    public function edit(Mentor $mentor): View
    {
        $mentor->load('user');

        return view('admin.mentors.edit', ['mentor' => $mentor]);
    }

    public function update(UpdateMentorRequest $request, Mentor $mentor): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $mentor) {
            $mentor->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $mentor->update([
                'nip' => $validated['nip'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'bagian' => $validated['bagian'] ?? null,
            ]);
        });

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Data mentor berhasil diperbarui.');
    }

    public function destroy(Mentor $mentor): RedirectResponse
    {
        if ($mentor->interns()->exists()) {
            return back()->withErrors([
                'mentor' => 'Mentor tidak dapat dihapus karena masih membimbing peserta magang aktif. Pindahkan peserta ke mentor lain terlebih dahulu.',
            ]);
        }

        DB::transaction(function () use ($mentor) {
            $user = $mentor->user;
            $mentor->delete();
            $user?->delete();
        });

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Data mentor berhasil dihapus.');
    }
}