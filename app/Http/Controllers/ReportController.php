<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['permission:view reports'];
    }

    public function index(Request $request): View
    {
        $filters = $this->resolveFilters($request);

        $attendances = Attendance::query()
            ->with('intern.user', 'intern.mentor.user')
            ->when($filters['tanggal_mulai'], fn ($q, $v) => $q->whereDate('tanggal', '>=', $v))
            ->when($filters['tanggal_selesai'], fn ($q, $v) => $q->whereDate('tanggal', '<=', $v))
            ->when($filters['intern_id'], fn ($q, $v) => $q->where('intern_id', $v))
            ->when($filters['mentor_id'], fn ($q, $v) => $q->whereHas('intern', fn ($iq) => $iq->where('mentor_id', $v)))
            ->when($filters['status'], fn ($q, $v) => $q->where('status', $v))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.index', [
            'attendances' => $attendances,
            'interns' => Intern::with('user')->get(),
            'mentors' => Mentor::with('user')->get(),
            'filters' => $filters,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $this->resolveFilters($request);

        $fileName = 'absensi-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new AttendanceExport($filters), $fileName);
    }

    /**
     * @return array{tanggal_mulai: ?string, tanggal_selesai: ?string, intern_id: ?string, mentor_id: ?string, status: ?string}
     */
    private function resolveFilters(Request $request): array
    {
        return $request->only([
            'tanggal_mulai',
            'tanggal_selesai',
            'intern_id',
            'mentor_id',
            'status',
        ]) + [
            'tanggal_mulai' => null,
            'tanggal_selesai' => null,
            'intern_id' => null,
            'mentor_id' => null,
            'status' => null,
        ];
    }
}