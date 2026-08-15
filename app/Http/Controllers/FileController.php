<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Attendance;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function leaveRequestEvidence(LeaveRequest $leaveRequest): StreamedResponse
    {
        $this->authorize('view', $leaveRequest);

        abort_unless($leaveRequest->bukti, 404);
        abort_unless(Storage::disk('private')->exists($leaveRequest->bukti), 404);

        return Storage::disk('private')->response($leaveRequest->bukti);
    }

    public function attendancePhoto(Request $request, Attendance $attendance): StreamedResponse
{
    $this->authorize('view', $attendance);

    $type = $request->query('type');
    $path = $type === 'check-out' ? $attendance->foto_check_out : $attendance->foto_check_in;

    abort_unless($path, 404);
    abort_unless(Storage::disk('private')->exists($path), 404);

    return Storage::disk('private')->response($path);
}
}