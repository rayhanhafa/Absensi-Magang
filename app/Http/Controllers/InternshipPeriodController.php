<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\InternshipPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class InternshipPeriodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'permission:manage internship periods',
        ];
    }
    public function index(): View
    {
        $periods = InternshipPeriod::withCount('interns')->latest()->paginate(15);

        return view('admin.periods.index', ['periods' => $periods]);
    }

    public function create(): View
    {
        return view('admin.periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        InternshipPeriod::create($validated);

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode magang berhasil ditambahkan.');
    }

    public function edit(InternshipPeriod $period): View
    {
        return view('admin.periods.edit', ['period' => $period]);
    }

    public function update(Request $request, InternshipPeriod $period): RedirectResponse
    {
        $validated = $this->validated($request);

        $period->update($validated);

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode magang berhasil diperbarui.');
    }

    public function destroy(InternshipPeriod $period): RedirectResponse
    {
        if ($period->interns()->exists()) {
            return back()->withErrors([
                'period' => 'Periode tidak dapat dihapus karena masih memiliki data peserta magang terkait.',
            ]);
        }

        $period->delete();

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode magang berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return Validator::make($request->all(), [
            'nama_periode' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'status' => ['required', 'in:aktif,selesai'],
        ])->validate();
    }
}