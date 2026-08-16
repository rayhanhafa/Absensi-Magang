<x-app-layout title="Dashboard">
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
            <p class="text-slate-500 mt-1">Semoga aktivitas magang Anda hari ini berjalan lancar.</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <x-card title="Status Absensi Hari Ini" class="lg:col-span-2 shadow-sm border-0 bg-gradient-to-br from-white to-slate-50/50">
            @if ($absensiHariIni)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <x-dynamic-icon name="arrow-left" class="w-6 h-6 rotate-45" />
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-0.5">Jam Masuk</p>
                            <p class="text-lg font-bold text-slate-800 leading-none">{{ $absensiHariIni->waktu_masuk?->format('H:i') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                            <x-dynamic-icon name="arrow-left" class="w-6 h-6 -rotate-[135deg]" />
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-0.5">Jam Pulang</p>
                            <p class="text-lg font-bold text-slate-800 leading-none">{{ $absensiHariIni->waktu_pulang?->format('H:i') ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-600 flex items-center justify-center flex-shrink-0">
                            <x-dynamic-icon name="shield-check" class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1">Status</p>
                            <x-badge :status="$absensiHariIni->status" />
                        </div>
                    </div>
                </div>

                @if ($absensiHariIni->waktu_pulang)
                    <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <x-dynamic-icon name="check-circle" class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-emerald-800 font-semibold mb-1">Absensi Selesai</h4>
                            <p class="text-sm text-emerald-600 leading-relaxed">Anda telah menyelesaikan absensi untuk hari ini. Selamat beristirahat!</p>
                        </div>
                    </div>
                @else
                    @include('intern.attendance.partials.flow', [
                        'action' => route('intern.attendance.check-out'),
                        'photoFieldName' => 'foto_check_out',
                        'buttonLabel' => 'Check Out Sekarang',
                        'buttonVariant' => 'danger',
                    ])
                @endif
            @else
                <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h4 class="text-blue-900 font-semibold mb-1">Belum Check In</h4>
                        <p class="text-sm text-blue-700">Silakan lakukan absensi masuk untuk merekam kehadiran Anda hari ini.</p>
                    </div>
                </div>
                @include('intern.attendance.partials.flow', [
                    'action' => route('intern.attendance.check-in'),
                    'photoFieldName' => 'foto_check_in',
                    'buttonLabel' => 'Check In Sekarang',
                    'buttonVariant' => 'primary',
                ])
            @endif
        </x-card>

        <x-card title="Ringkasan Bulan Ini">
            <div class="space-y-4 mt-2">
                <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50/50 border border-emerald-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <x-dynamic-icon name="check-circle" class="w-4 h-4" />
                        </div>
                        <span class="text-sm font-medium text-slate-700">Hadir</span>
                    </div>
                    <span class="text-base font-bold text-emerald-700">{{ $hadirBulanIni }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50/50 border border-amber-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                            <x-dynamic-icon name="clock" class="w-4 h-4" />
                        </div>
                        <span class="text-sm font-medium text-slate-700">Terlambat</span>
                    </div>
                    <span class="text-base font-bold text-amber-700">{{ $terlambatBulanIni }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50/50 border border-blue-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <x-dynamic-icon name="document-text" class="w-4 h-4" />
                        </div>
                        <span class="text-sm font-medium text-slate-700">Izin</span>
                    </div>
                    <span class="text-base font-bold text-blue-700">{{ $izinBulanIni }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50/50 border border-purple-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                            <x-dynamic-icon name="users" class="w-4 h-4" />
                        </div>
                        <span class="text-sm font-medium text-slate-700">Sakit</span>
                    </div>
                    <span class="text-base font-bold text-purple-700">{{ $sakitBulanIni }}</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-red-50/50 border border-red-100/50">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                            <x-dynamic-icon name="x-circle" class="w-4 h-4" />
                        </div>
                        <span class="text-sm font-medium text-slate-700">Alpa</span>
                    </div>
                    <span class="text-base font-bold text-red-700">{{ $alpaBulanIni }}</span>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>