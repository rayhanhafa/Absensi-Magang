<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function leaveRequestEvidence(LeaveRequest $leaveRequest): StreamedResponse
    {
        $this->authorize('view', $leaveRequest);

        abort_unless($leaveRequest->bukti, 404);
        abort_unless(Storage::disk('private')->exists($leaveRequest->bukti), 404);

        return Storage::disk('private')->response($leaveRequest->bukti);
    }
}