<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\WorkSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class WorkScheduleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'permission:manage work schedules',
        ];
    }

    public function index(): View
    {
        $schedules = WorkSchedule::latest()->paginate(15);

        return view('admin.schedules.index', ['schedules' => $schedules]);
    }

    public function create(): View
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request): RedirectResponse
{
    $validated = $this->validated($request);

    if ($validated['is_active'] ?? false) {
        WorkSchedule::query()->update(['is_active' => false]);
    }

    WorkSchedule::create($validated);

    return redirect()->route('admin.schedules.index')
        ->with('success', 'Jadwal kerja berhasil ditambahkan.');
}

    public function edit(WorkSchedule $schedule): View
    {
        return view('admin.schedules.edit', ['schedule' => $schedule]);
    }

    public function update(Request $request, WorkSchedule $schedule): RedirectResponse
{
    $validated = $this->validated($request);

    if ($validated['is_active'] ?? false) {
        WorkSchedule::query()->where('id', '!=', $schedule->id)->update(['is_active' => false]);
    }

    $schedule->update($validated);

    return redirect()->route('admin.schedules.index')
        ->with('success', 'Jadwal kerja berhasil diperbarui.');
}

    public function destroy(WorkSchedule $schedule): RedirectResponse
    {
        if (WorkSchedule::count() <= 1) {
            return back()->withErrors([
                'schedule' => 'Tidak dapat menghapus satu-satunya jadwal kerja yang tersisa.',
            ]);
        }

        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal kerja berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return Validator::make($request->all(), [
            'nama_jadwal' => ['required', 'string', 'max:255'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i', 'after:jam_masuk'],
            'toleransi_keterlambatan' => ['required', 'integer', 'min:0', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
        ])->validate();
    }
}