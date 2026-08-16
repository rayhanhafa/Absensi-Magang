<x-app-layout title="Dashboard Mentor">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                @php
                    $hour = now()->format('H');
                    if ($hour < 12) $greeting = 'Selamat pagi';
                    elseif ($hour < 15) $greeting = 'Selamat siang';
                    elseif ($hour < 18) $greeting = 'Selamat sore';
                    else $greeting = 'Selamat malam';
                @endphp
                {{ $greeting }}, {{ explode(' ', auth()->user()->name)[0] }} 👋
            </h1>
            <p class="text-slate-500 mt-1">Pantau kehadiran peserta magang di bawah bimbingan Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <x-stat-card label="Peserta Bimbingan" :value="$totalPeserta" color="blue" icon="users" />
        <x-stat-card label="Hadir Hari Ini" :value="$hadirHariIni" color="green" icon="check-circle" />
        <x-stat-card label="Terlambat" :value="$terlambatHariIni" color="amber" icon="clock" />
        <x-stat-card label="Belum Absen" :value="$belumAbsen" color="red" icon="x-circle" />
    </div>

    <x-card title="Absensi Bimbingan Hari Ini" noPadding class="shadow-sm border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-row-hover">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/50">
                        <th class="px-5 py-3.5 font-semibold">Nama</th>
                        <th class="px-5 py-3.5 font-semibold">Universitas</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pesertaHariIni as $absensi)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="font-medium text-slate-800">{{ $absensi->intern->user->name }}</div>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $absensi->intern->universitas ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">
                                @if($absensi->waktu_masuk)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                        <x-dynamic-icon name="clock" class="w-3 h-3" />
                                        {{ $absensi->waktu_masuk->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                @if($absensi->waktu_pulang)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                        <x-dynamic-icon name="clock" class="w-3 h-3" />
                                        {{ $absensi->waktu_pulang->format('H:i') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-3"><x-badge :status="$absensi->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('attendance.show', $absensi) }}" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 font-medium text-xs bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                    Detail <x-dynamic-icon name="arrow-left" class="w-3 h-3 rotate-180" />
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
                                    <p class="text-slate-500 font-medium">Belum ada absensi hari ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>