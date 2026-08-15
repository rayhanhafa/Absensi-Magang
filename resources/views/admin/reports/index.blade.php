<x-app-layout title="Laporan Absensi">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Laporan Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Rekap kehadiran peserta magang dengan filter periode.</p>
        </div>
        <a href="{{ route('admin.reports.export', request()->query()) }}">
            <x-button variant="success">
                <x-dynamic-icon name="document" class="w-4 h-4" />
                Export Excel
            </x-button>
        </a>
    </div>

    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] }}" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Peserta</label>
                <select name="intern_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Peserta</option>
                    @foreach ($interns as $intern)
                        <option value="{{ $intern->id }}" @selected($filters['intern_id'] == $intern->id)>{{ $intern->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Mentor</label>
                <select name="mentor_id" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Mentor</option>
                    @foreach ($mentors as $mentor)
                        <option value="{{ $mentor->id }}" @selected($filters['mentor_id'] == $mentor->id)>{{ $mentor->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
                <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="hadir" @selected($filters['status'] == 'hadir')>Hadir</option>
                    <option value="terlambat" @selected($filters['status'] == 'terlambat')>Terlambat</option>
                    <option value="izin" @selected($filters['status'] == 'izin')>Izin</option>
                    <option value="sakit" @selected($filters['status'] == 'sakit')>Sakit</option>
                    <option value="alpa" @selected($filters['status'] == 'alpa')>Alpa</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="w-full">Terapkan</x-button>
                @if (collect($filters)->filter()->isNotEmpty())
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-slate-500 hover:text-slate-700 whitespace-nowrap">Reset</a>
                @endif
            </div>
        </form>
    </x-card>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Jam Masuk</th>
                    <th class="px-5 py-3 font-medium">Jam Pulang</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Keterlambatan</th>
                    <th class="px-5 py-3 font-medium">Lokasi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $attendance->intern->user->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_pulang?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3"><x-badge :status="$attendance->status" /></td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->keterlambatan > 0 ? $attendance->keterlambatan . ' menit' : '-' }}</td>
                            <td class="px-5 py-3">
                                @if ($attendance->location_status_check_in === 'valid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Valid</span>
                                @elseif ($attendance->location_status_check_in)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Invalid</span>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data yang sesuai dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="mt-4 px-5">{{ $attendances->links() }}</div>
        @endif
    </x-card>
</x-app-layout>