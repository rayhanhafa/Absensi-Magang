<x-app-layout title="Pengajuan Izin">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Pengajuan Izin</h1>
        <p class="text-sm text-slate-500 mt-1">
            @if (auth()->user()->hasRole('admin'))
                Kelola seluruh pengajuan izin peserta magang.
            @else
                Kelola pengajuan izin peserta bimbingan Anda.
            @endif
        </p>
    </div>

    <x-card class="mb-6">
        <form method="GET" class="flex gap-4">
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') == 'pending')>Menunggu</option>
                <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
                <option value="rejected" @selected(request('status') == 'rejected')>Ditolak</option>
            </select>
            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>
    </x-card>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Jenis</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveRequests as $leaveRequest)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $leaveRequest->intern->user->name }}</td>
                            <td class="px-5 py-3 text-slate-600 capitalize">{{ $leaveRequest->jenis }}</td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $leaveRequest->tanggal_mulai->translatedFormat('d M Y') }}
                                @if (! $leaveRequest->tanggal_mulai->isSameDay($leaveRequest->tanggal_selesai))
                                    &mdash; {{ $leaveRequest->tanggal_selesai->translatedFormat('d M Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-3"><x-badge :status="$leaveRequest->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('leave-requests.show', $leaveRequest) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    {{ $leaveRequest->status === 'pending' ? 'Tinjau' : 'Lihat' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada pengajuan izin.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($leaveRequests->hasPages())
            <div class="mt-4 px-5">{{ $leaveRequests->links() }}</div>
        @endif
    </x-card>
</x-app-layout>