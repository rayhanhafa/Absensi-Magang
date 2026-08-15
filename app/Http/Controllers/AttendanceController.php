<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Http\Requests\CheckOutRequest;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\LocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
    protected AttendanceService $attendanceService,
    protected LocationService $locationService
) {}

    public function checkIn(CheckInRequest $request): RedirectResponse
{
    $intern = $request->user()->intern;
    $validated = $request->validated();

    $locationData = [];

    if (config('attendance.require_location')) {
        $locationResult = $this->locationService->validateLocation(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $validated['accuracy']
        );

        $locationData = [
            'accuracy_check_in' => (int) round($locationResult['accuracy']),
            'distance_check_in' => $locationResult['distance'],
            'location_status_check_in' => 'valid',
        ];
    }

    if ($request->hasFile('foto_check_in')) {
        $validated['foto_check_in'] = $request->file('foto_check_in')->store(
            'attendance-photos/' . $intern->id . '/' . now()->format('Y-m-d'),
            'private'
        );
    }

    $this->attendanceService->checkIn($intern, array_merge($validated, $locationData));

    return back()->with('success', 'Check-in berhasil dicatat.');
}

    public function checkOut(CheckOutRequest $request): RedirectResponse
{
    $intern = $request->user()->intern;
    $validated = $request->validated();

    $locationData = [];

    if (config('attendance.require_location')) {
        $locationResult = $this->locationService->validateLocation(
            (float) $validated['latitude'],
            (float) $validated['longitude'],
            (float) $validated['accuracy']
        );

        $locationData = [
            'accuracy_check_out' => (int) round($locationResult['accuracy']),
            'distance_check_out' => $locationResult['distance'],
            'location_status_check_out' => 'valid',
        ];
    }

    if ($request->hasFile('foto_check_out')) {
        $validated['foto_check_out'] = $request->file('foto_check_out')->store(
            'attendance-photos/' . $intern->id . '/' . now()->format('Y-m-d'),
            'private'
        );
    }

    $this->attendanceService->checkOut($intern, array_merge($validated, $locationData));

    return back()->with('success', 'Check-out berhasil dicatat.');
}

    public function history(Request $request): View
    {
        $intern = $request->user()->intern;

        $attendances = $intern->attendances()
            ->when($request->filled('bulan'), function ($query) use ($request) {
                $date = \Carbon\Carbon::parse($request->input('bulan'));
                $query->whereMonth('tanggal', $date->month)
                    ->whereYear('tanggal', $date->year);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            })
            ->latest('tanggal')
            ->paginate(15)
            ->withQueryString();

        return view('intern.attendance.history', [
            'attendances' => $attendances,
        ]);
    }

    public function show(Attendance $attendance): View
    {
        $this->authorize('view', $attendance);

        $attendance->load('intern.user', 'intern.mentor.user');

        return view('attendance.show', [
            'attendance' => $attendance,
        ]);
    }

    public function adminIndex(Request $request): View
{
    $attendances = Attendance::query()
        ->with('intern.user', 'intern.mentor.user')
        ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal', $request->tanggal))
        ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
        ->when($request->filled('intern_id'), fn ($q) => $q->where('intern_id', $request->intern_id))
        ->latest('tanggal')
        ->paginate(15)
        ->withQueryString();

    return view('admin.attendances.index', [
        'attendances' => $attendances,
        'interns' => \App\Models\Intern::with('user')->get(),
    ]);
}

public function mentorIndex(Request $request): View
{
    $mentor = $request->user()->mentor;
    abort_if(! $mentor, 403);

    $internIds = $mentor->interns()->pluck('id');

    $attendances = Attendance::query()
        ->with('intern.user')
        ->whereIn('intern_id', $internIds)
        ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal', $request->tanggal))
        ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
        ->latest('tanggal')
        ->paginate(15)
        ->withQueryString();

    return view('mentor.attendances.index', ['attendances' => $attendances]);
}

public function update(Request $request, Attendance $attendance): RedirectResponse
{
    $this->authorize('update', $attendance);

    $validated = $request->validate([
        'status' => ['required', 'in:hadir,terlambat,izin,sakit,alpa'],
        'waktu_masuk' => ['nullable', 'date_format:H:i'],
        'waktu_pulang' => ['nullable', 'date_format:H:i'],
        'catatan' => ['nullable', 'string', 'max:500'],
    ]);

    $attendance->update($validated);

    return back()->with('success', 'Data absensi berhasil diperbarui.');
}
}