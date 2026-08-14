<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateLeaveRequestStatusRequest;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    /**
     * Intern: membuat pengajuan izin baru.
     */
    public function store(StoreLeaveRequestRequest $request): RedirectResponse
    {
        $intern = $request->user()->intern;
        $validated = $request->validated();

        if ($request->hasFile('bukti')) {
            $validated['bukti'] = $request->file('bukti')->store('leave-requests', 'private');
        }

        $intern->leaveRequests()->create($validated + ['status' => 'pending']);

        return back()->with('success', 'Pengajuan izin berhasil dikirim.');
    }

    /**
     * Intern: melihat riwayat pengajuan izinnya sendiri.
     */
    public function myRequests(Request $request): View
    {
        $intern = $request->user()->intern;

        $leaveRequests = $intern->leaveRequests()
            ->latest()
            ->paginate(10);

        return view('intern.leave-requests.index', ['leaveRequests' => $leaveRequests]);
    }

    /**
     * Mentor/Admin: melihat daftar pengajuan izin yang perlu ditangani.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = LeaveRequest::query()->with('intern.user');

        if ($user->hasRole('mentor')) {
            $internIds = $user->mentor->interns()->pluck('id');
            $query->whereIn('intern_id', $internIds);
        }

        $leaveRequests = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('mentor.leave-requests.index', ['leaveRequests' => $leaveRequests]);
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $this->authorize('view', $leaveRequest);

        $leaveRequest->load('intern.user', 'approver');

        return view('leave-requests.show', ['leaveRequest' => $leaveRequest]);
    }

    /**
     * Mentor/Admin: approve atau reject pengajuan izin.
     */
    public function updateStatus(UpdateLeaveRequestStatusRequest $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $this->authorize('update', $leaveRequest);

        $leaveRequest->update([
            'status' => $request->validated('status'),
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'catatan_approval' => $request->validated('catatan_approval'),
        ]);

        return back()->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }
}