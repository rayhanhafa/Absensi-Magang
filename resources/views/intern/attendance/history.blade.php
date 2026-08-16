<x-app-layout title="Riwayat Absensi">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Rekam jejak kehadiran Anda.</p>
        </div>
    </div>

    <x-card class="mb-6 shadow-sm border-slate-200/80">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="w-full sm:w-48">
                <x-input
                    type="month"
                    name="bulan"
                    value="{{ request('bulan') }}"
                />
            </div>
            <div class="w-full sm:w-48">
                <x-select name="status" :options="[
                    'hadir' => 'Hadir',
                    'terlambat' => 'Terlambat',
                    'izin' => 'Izin',
                    'sakit' => 'Sakit',
                    'alpa' => 'Alpa'
                ]" :selected="request('status')" placeholder="Semua Status" />
            </div>
            <div class="w-full sm:w-auto">
                <x-button type="submit" variant="primary" class="w-full sm:w-auto">Filter</x-button>
            </div>
            
            @if(request()->hasAny(['bulan', 'status']))
                <div class="w-full sm:w-auto sm:ml-auto">
                    <a href="{{ route('intern.attendance.history') }}">
                        <x-button type="button" variant="ghost" class="w-full sm:w-auto text-slate-500">Reset</x-button>
                    </a>
                </div>
            @endif
        </form>
    </x-card>

    <x-card noPadding class="shadow-sm border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-row-hover">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/50">
                        <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3.5 font-semibold">Keterlambatan</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $attendance->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-slate-600">
                                @if($attendance->waktu_masuk)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                        <x-dynamic-icon name="clock" class="w-3 h-3" />
                                        {{ $attendance->waktu_masuk->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                @if($attendance->waktu_pulang)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                        <x-dynamic-icon name="clock" class="w-3 h-3" />
                                        {{ $attendance->waktu_pulang->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                @if($attendance->keterlambatan > 0)
                                    <span class="text-amber-600 font-medium">{{ $attendance->keterlambatan }} menit</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3"><x-badge :status="$attendance->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('attendance.show', $attendance) }}" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 font-medium text-xs bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                    Lihat <x-dynamic-icon name="arrow-left" class="w-3 h-3 rotate-180" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <x-dynamic-icon name="calendar" class="w-6 h-6" />
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada riwayat absensi</p>
                                    <p class="text-slate-400 text-xs mt-1">Data absensi Anda akan muncul di sini setelah Anda melakukan check in/out.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $attendances->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>