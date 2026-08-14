<x-app-layout title="Detail Pengajuan Izin">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Detail Pengajuan Izin</h1>
        <p class="text-sm text-slate-500 mt-1">Diajukan oleh {{ $leaveRequest->intern->user->name }}</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <x-card title="Informasi Pengajuan" class="lg:col-span-2">
            <dl class="grid grid-cols-2 gap-y-4 text-sm mb-5">
                <div><dt class="text-slate-500 mb-1">Jenis</dt><dd class="font-medium text-slate-800 capitalize">{{ $leaveRequest->jenis }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Status</dt><dd><x-badge :status="$leaveRequest->status" /></dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Mulai</dt><dd class="font-medium text-slate-800">{{ $leaveRequest->tanggal_mulai->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Selesai</dt><dd class="font-medium text-slate-800">{{ $leaveRequest->tanggal_selesai->translatedFormat('d F Y') }}</dd></div>
            </dl>

            <div class="mb-5">
                <dt class="text-slate-500 mb-1 text-sm">Alasan</dt>
                <dd class="text-sm text-slate-800">{{ $leaveRequest->alasan }}</dd>
            </div>

            @if ($leaveRequest->bukti)
    <div class="mb-5">
        <dt class="text-slate-500 mb-1 text-sm">Bukti Pendukung</dt>
        <a href="{{ route('files.leave-requests.evidence', $leaveRequest) }}" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            <x-dynamic-icon name="document" class="w-4 h-4" />
            Lihat File Bukti
        </a>
    </div>
@endif

            @if ($leaveRequest->status !== 'pending')
                <div class="pt-4 border-t border-slate-100">
                    <dt class="text-slate-500 mb-1 text-sm">
                        {{ $leaveRequest->status === 'approved' ? 'Disetujui' : 'Ditolak' }} oleh
                    </dt>
                    <dd class="text-sm text-slate-800">
                        {{ $leaveRequest->approver?->name ?? '-' }}
                        @if ($leaveRequest->approved_at)
                            &middot; {{ $leaveRequest->approved_at->translatedFormat('d F Y, H:i') }}
                        @endif
                    </dd>
                    @if ($leaveRequest->catatan_approval)
                        <dd class="text-sm text-slate-600 mt-2 bg-slate-50 rounded-lg px-3 py-2">{{ $leaveRequest->catatan_approval }}</dd>
                    @endif
                </div>
            @endif
        </x-card>

        <div class="space-y-6">
            @if ($leaveRequest->status === 'pending' && auth()->user()->hasAnyRole(['admin', 'mentor']))
                <x-card title="Tindakan">
    <form method="POST" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.leave-requests.update-status' : 'mentor.leave-requests.update-status', $leaveRequest) }}" class="mb-3">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="approved">
        <x-button type="submit" variant="success" class="w-full">Setujui</x-button>
    </form>

    <form method="POST" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.leave-requests.update-status' : 'mentor.leave-requests.update-status', $leaveRequest) }}" x-data="{ showReason: false }">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="rejected">

        <div x-show="showReason" x-cloak class="mb-3">
            <textarea name="catatan_approval" rows="3" class="block w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Alasan penolakan (wajib)"></textarea>
        </div>

        <x-button type="button" @click="showReason ? $el.closest('form').submit() : showReason = true" variant="danger" class="w-full" x-text="showReason ? 'Kirim Penolakan' : 'Tolak'"></x-button>
    </form>
</x-card>
            @endif

            <x-card title="Data Peserta">
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-slate-500 mb-1">Nama</dt><dd class="font-medium text-slate-800">{{ $leaveRequest->intern->user->name }}</dd></div>
                    <div><dt class="text-slate-500 mb-1">Universitas</dt><dd class="font-medium text-slate-800">{{ $leaveRequest->intern->universitas }}</dd></div>
                    <div><dt class="text-slate-500 mb-1">Mentor</dt><dd class="font-medium text-slate-800">{{ $leaveRequest->intern->mentor?->user?->name ?? '-' }}</dd></div>
                </dl>
            </x-card>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    </div>
</x-app-layout>