<x-app-layout title="Pengajuan Izin Saya">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Pengajuan Izin Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat dan status pengajuan izin/sakit Anda.</p>
        </div>
        <a href="{{ route('intern.leave-requests.create') }}">
            <x-button variant="primary">+ Ajukan Izin</x-button>
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Jenis</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Alasan</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveRequests as $leaveRequest)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800 capitalize">{{ $leaveRequest->jenis }}</td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $leaveRequest->tanggal_mulai->translatedFormat('d M Y') }}
                                @if (! $leaveRequest->tanggal_mulai->isSameDay($leaveRequest->tanggal_selesai))
                                    &mdash; {{ $leaveRequest->tanggal_selesai->translatedFormat('d M Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600 max-w-xs truncate">{{ $leaveRequest->alasan }}</td>
                            <td class="px-5 py-3"><x-badge :status="$leaveRequest->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('leave-requests.show', $leaveRequest) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat</a>
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