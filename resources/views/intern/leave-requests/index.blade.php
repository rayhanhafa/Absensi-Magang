<x-app-layout title="Daftar Izin/Sakit">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengajuan Izin/Sakit</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar riwayat pengajuan izin dan sakit Anda.</p>
        </div>
        <div>
            <a href="{{ route('intern.leave-requests.create') }}">
                <x-button type="button" variant="primary" class="w-full sm:w-auto shadow-sm">
                    <x-dynamic-icon name="document-text" class="w-4 h-4 mr-1.5" />
                    Ajukan Baru
                </x-button>
            </a>
        </div>
    </div>

    @if (session('success'))
        <x-alert type="success" class="mb-6">
            {{ session('success') }}
        </x-alert>
    @endif

    <x-card noPadding class="shadow-sm border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-row-hover">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/50">
                        <th class="px-5 py-3.5 font-semibold">Tanggal Pengajuan</th>
                        <th class="px-5 py-3.5 font-semibold">Jenis</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal Mulai</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal Selesai</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($leaveRequests as $request)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $request->created_at->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-slate-600 capitalize">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium {{ $request->jenis === 'sakit' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $request->jenis }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $request->tanggal_mulai->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $request->tanggal_selesai->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3"><x-badge :status="$request->status" /></td>
                            <td class="px-5 py-3">
                                @if($request->bukti_path)
                                    <a href="{{ Storage::url($request->bukti_path) }}" target="_blank" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 font-medium text-xs bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <x-dynamic-icon name="document-text" class="w-6 h-6" />
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada pengajuan</p>
                                    <p class="text-slate-400 text-xs mt-1">Anda belum pernah mengajukan izin atau sakit.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($leaveRequests->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>