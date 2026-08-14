<x-app-layout title="Riwayat Absensi">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Riwayat Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">Rekam jejak kehadiran Anda.</p>
    </div>

    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <input
                type="month"
                name="bulan"
                value="{{ request('bulan') }}"
                class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="hadir" @selected(request('status') == 'hadir')>Hadir</option>
                <option value="terlambat" @selected(request('status') == 'terlambat')>Terlambat</option>
                <option value="izin" @selected(request('status') == 'izin')>Izin</option>
                <option value="sakit" @selected(request('status') == 'sakit')>Sakit</option>
                <option value="alpa" @selected(request('status') == 'alpa')>Alpa</option>
            </select>
            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>
    </x-card>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Jam Masuk</th>
                        <th class="px-5 py-3 font-medium">Jam Pulang</th>
                        <th class="px-5 py-3 font-medium">Keterlambatan</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $attendance->tanggal->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_pulang?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $attendance->keterlambatan > 0 ? $attendance->keterlambatan . ' menit' : '-' }}</td>
                            <td class="px-5 py-3"><x-badge :status="$attendance->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('attendance.show', $attendance) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat absensi.</td>
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