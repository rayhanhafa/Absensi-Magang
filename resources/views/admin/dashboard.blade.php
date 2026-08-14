<x-app-layout title="Dashboard Admin">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Dashboard Admin</h1>
        <p class="text-sm text-slate-500 mt-1">Ringkasan aktivitas absensi hari ini.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="Peserta Aktif" :value="$totalInternAktif" color="indigo" />
        <x-stat-card label="Total Mentor" :value="$totalMentor" color="blue" />
        <x-stat-card label="Hadir Hari Ini" :value="$hadirHariIni" color="green" />
        <x-stat-card label="Terlambat Hari Ini" :value="$terlambatHariIni" color="amber" />
        <x-stat-card label="Izin Hari Ini" :value="$izinHariIni" color="blue" />
        <x-stat-card label="Sakit Hari Ini" :value="$sakitHariIni" color="purple" />
        <x-stat-card label="Alpa Hari Ini" :value="$alpaHariIni" color="red" />
    </div>

    <x-card title="Absensi Hari Ini">
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Jam Masuk</th>
                        <th class="px-5 py-3 font-medium">Jam Pulang</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($absensiHariIni as $absensi)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $absensi->intern->user->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $absensi->waktu_masuk?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $absensi->waktu_pulang?->format('H:i') ?? '-' }}</td>
                            <td class="px-5 py-3"><x-badge :status="$absensi->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada data absensi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($absensiHariIni->hasPages())
            <div class="mt-4 px-5">{{ $absensiHariIni->links() }}</div>
        @endif
    </x-card>
</x-app-layout>