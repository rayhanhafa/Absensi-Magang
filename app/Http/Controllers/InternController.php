<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\StoreInternRequest;
use App\Http\Requests\UpdateInternRequest;
use App\Models\Intern;
use App\Models\InternshipPeriod;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InternController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'permission:manage interns',
        ];
    }

    public function index(Request $request): View
    {
        $interns = Intern::query()
            ->with(['user', 'mentor.user', 'internshipPeriod'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('mentor_id'), fn ($q) => $q->where('mentor_id', $request->mentor_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$request->search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.interns.index', [
            'interns' => $interns,
            'mentors' => Mentor::with('user')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.interns.create', [
            'mentors' => Mentor::with('user')->get(),
            'periods' => InternshipPeriod::aktif()->get(),
        ]);
    }

    public function store(StoreInternRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ]);
            $user->assignRole('intern');

            $user->profile()->create([
                'nomor_induk' => $validated['nomor_induk'] ?? null,
                'nomor_hp' => $validated['nomor_hp'] ?? null,
            ]);

            $user->intern()->create([
                'mentor_id' => $validated['mentor_id'] ?? null,
                'internship_period_id' => $validated['internship_period_id'],
                'universitas' => $validated['universitas'],
                'jurusan' => $validated['jurusan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.interns.index')
            ->with('success', 'Data peserta magang berhasil ditambahkan.');
    }

    public function show(Intern $intern): View
    {
        $this->authorize('view', $intern);

        $intern->load(['user.profile', 'mentor.user', 'internshipPeriod']);

        $rekapBulanIni = $intern->attendances()
            ->thisMonth()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.interns.show', [
            'intern' => $intern,
            'rekapBulanIni' => $rekapBulanIni,
        ]);
    }

    public function edit(Intern $intern): View
    {
        $intern->load('user.profile');

        return view('admin.interns.edit', [
            'intern' => $intern,
            'mentors' => Mentor::with('user')->get(),
            'periods' => InternshipPeriod::all(),
        ]);
    }

    public function update(UpdateInternRequest $request, Intern $intern): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $intern) {
            $intern->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $intern->user->profile()->updateOrCreate([], [
                'nomor_induk' => $validated['nomor_induk'] ?? null,
                'nomor_hp' => $validated['nomor_hp'] ?? null,
            ]);

            $intern->update([
                'mentor_id' => $validated['mentor_id'] ?? null,
                'internship_period_id' => $validated['internship_period_id'],
                'universitas' => $validated['universitas'],
                'jurusan' => $validated['jurusan'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.interns.index')
            ->with('success', 'Data peserta magang berhasil diperbarui.');
    }

    public function destroy(Intern $intern): RedirectResponse
    {
        DB::transaction(function () use ($intern) {
            $user = $intern->user;
            $intern->delete();
            $user?->delete();
        });

        return redirect()->route('admin.interns.index')
            ->with('success', 'Data peserta magang berhasil dihapus.');
    }
}