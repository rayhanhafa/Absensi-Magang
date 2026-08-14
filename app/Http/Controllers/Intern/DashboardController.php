<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $intern = $request->user()->intern;

        abort_if(! $intern, 403, 'Akun Anda belum terhubung dengan data peserta magang.');

        $absensiHariIni = $intern->attendances()
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $rekapBulanIni = $intern->attendances()
            ->thisMonth()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('intern.dashboard', [
            'intern' => $intern,
            'absensiHariIni' => $absensiHariIni,
            'hadirBulanIni' => $rekapBulanIni->get('hadir', 0),
            'terlambatBulanIni' => $rekapBulanIni->get('terlambat', 0),
            'izinBulanIni' => $rekapBulanIni->get('izin', 0),
            'sakitBulanIni' => $rekapBulanIni->get('sakit', 0),
            'alpaBulanIni' => $rekapBulanIni->get('alpa', 0),
        ]);
    }
}