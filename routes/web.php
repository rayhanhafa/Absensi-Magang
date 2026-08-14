<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\Intern\DashboardController as InternDashboardController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\InternshipPeriodController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\WorkScheduleController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';   // ← pindah ke sini, di luar group manapun

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');

    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Detail absensi — dipakai lintas role (proteksi lewat AttendancePolicy)
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // Detail pengajuan izin — dipakai lintas role (proteksi lewat LeaveRequestPolicy)
    Route::get('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'show'])
        ->name('leave-requests.show');

    Route::get('/files/leave-requests/{leaveRequest}/evidence', [FileController::class, 'leaveRequestEvidence'])
        ->name('files.leave-requests.evidence');

    /*
    |--------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('interns', InternController::class);
        Route::resource('mentors', MentorController::class);
        Route::resource('periods', InternshipPeriodController::class)->except(['show']);
        Route::resource('schedules', WorkScheduleController::class)->except(['show']);

        Route::get('/attendances', [AttendanceController::class, 'adminIndex'])->name('attendances.index');
        Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

        Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');

        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'updateStatus'])
            ->name('leave-requests.update-status');
    });

    /*
    |--------------------------------------------------------------------
    | MENTOR
    |--------------------------------------------------------------------
    */
    Route::middleware('role:mentor')->prefix('mentor')->name('mentor.')->group(function () {

        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');

        Route::get('/interns', [App\Http\Controllers\Mentor\InternController::class, 'index'])->name('interns.index');
        Route::get('/interns/{intern}', [App\Http\Controllers\Mentor\InternController::class, 'show'])->name('interns.show');

        Route::get('/attendances', [AttendanceController::class, 'mentorIndex'])->name('attendances.index');

        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'updateStatus'])
            ->name('leave-requests.update-status');
    });

    /*
    |--------------------------------------------------------------------
    | INTERN
    |--------------------------------------------------------------------
    */
    Route::middleware('role:intern')->prefix('intern')->name('intern.')->group(function () {

        Route::get('/dashboard', [InternDashboardController::class, 'index'])->name('dashboard');

        Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])
            ->name('attendance.check-in');
        Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])
            ->name('attendance.check-out');
        Route::get('/attendance/history', [AttendanceController::class, 'history'])
            ->name('attendance.history');

        Route::get('/leave-requests', [LeaveRequestController::class, 'myRequests'])
            ->name('leave-requests.index');
        Route::get('/leave-requests/create', function () {
            return view('intern.leave-requests.create');
        })->name('leave-requests.create');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])
            ->name('leave-requests.store');
    });
});