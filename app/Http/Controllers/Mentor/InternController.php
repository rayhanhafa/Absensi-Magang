<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternController extends Controller
{
    public function index(Request $request): View
    {
        $mentor = $request->user()->mentor;
        abort_if(! $mentor, 403);

        $interns = $mentor->interns()->with('user', 'internshipPeriod')->paginate(15);

        return view('mentor.interns.index', ['interns' => $interns]);
    }

    public function show(Request $request, Intern $intern): View
    {
        $this->authorize('view', $intern);

        $intern->load('user.profile', 'internshipPeriod');

        $rekapBulanIni = $intern->attendances()
            ->thisMonth()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('mentor.interns.show', [
            'intern' => $intern,
            'rekapBulanIni' => $rekapBulanIni,
        ]);
    }
}