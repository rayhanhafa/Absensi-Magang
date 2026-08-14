<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $mentor = $request->user()->mentor;

        abort_if(! $mentor, 403, 'Akun Anda belum terhubung dengan data mentor.');

        $internIds = $mentor->interns()->aktif()->pluck('id');
        $today = Carbon::today();

        $rekapHariIni = Attendance::query()
            ->whereIn('intern_id', $internIds)
            ->whereDate('tanggal', $today)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $internBelumAbsen = $mentor->interns()
            ->aktif()
            ->whereDoesntHave('attendances', function ($query) use ($today) {
                $query->whereDate('tanggal', $today);
            })
            ->count();

        $pesertaHariIni = Attendance::query()
            ->with('intern.user')
            ->whereIn('intern_id', $internIds)
            ->whereDate('tanggal', $today)
            ->get();

        return view('mentor.dashboard', [
            'totalPeserta' => $internIds->count(),
            'hadirHariIni' => $rekapHariIni->get('hadir', 0),
            'terlambatHariIni' => $rekapHariIni->get('terlambat', 0),
            'izinHariIni' => $rekapHariIni->get('izin', 0),
            'belumAbsen' => $internBelumAbsen,
            'pesertaHariIni' => $pesertaHariIni,
        ]);
    }
}