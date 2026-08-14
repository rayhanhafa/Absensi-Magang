<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Intern;
use App\Models\Mentor;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalInternAktif = Intern::aktif()->count();
        $totalMentor = Mentor::count();

        $today = Carbon::today();

        $rekapHariIni = Attendance::query()
            ->whereDate('tanggal', $today)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $absensiHariIni = Attendance::query()
            ->with('intern.user')
            ->whereDate('tanggal', $today)
            ->latest('waktu_masuk')
            ->paginate(10);

        return view('admin.dashboard', [
            'totalInternAktif' => $totalInternAktif,
            'totalMentor' => $totalMentor,
            'hadirHariIni' => $rekapHariIni->get('hadir', 0),
            'terlambatHariIni' => $rekapHariIni->get('terlambat', 0),
            'izinHariIni' => $rekapHariIni->get('izin', 0),
            'sakitHariIni' => $rekapHariIni->get('sakit', 0),
            'alpaHariIni' => $rekapHariIni->get('alpa', 0),
            'absensiHariIni' => $absensiHariIni,
        ]);
    }
}