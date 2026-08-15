<x-app-layout title="Dashboard">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Selamat datang, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <x-card title="Status Absensi Hari Ini" class="lg:col-span-2">
    @if ($absensiHariIni)
        <div class="grid grid-cols-3 gap-4 mb-5">
            <div>
                <p class="text-xs text-slate-500 mb-1">Jam Masuk</p>
                <p class="font-semibold text-slate-800">{{ $absensiHariIni->waktu_masuk?->format('H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Jam Pulang</p>
                <p class="font-semibold text-slate-800">{{ $absensiHariIni->waktu_pulang?->format('H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Status</p>
                <x-badge :status="$absensiHariIni->status" />
            </div>
        </div>

        @if ($absensiHariIni->waktu_pulang)
            <p class="text-sm text-slate-500 bg-slate-50 rounded-lg px-4 py-3">Absensi hari ini selesai.</p>
        @else
            @include('intern.attendance.partials.flow', [
                'action' => route('intern.attendance.check-out'),
                'photoFieldName' => 'foto_check_out',
                'buttonLabel' => 'Check Out',
                'buttonVariant' => 'danger',
            ])
        @endif
    @else
        <p class="text-sm text-slate-500 mb-4">Anda belum melakukan absensi hari ini.</p>
        @include('intern.attendance.partials.flow', [
            'action' => route('intern.attendance.check-in'),
            'photoFieldName' => 'foto_check_in',
            'buttonLabel' => 'Check In',
            'buttonVariant' => 'primary',
        ])
    @endif
</x-card>

        <x-card title="Ringkasan Bulan Ini">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Hadir</dt><dd class="font-medium text-slate-800">{{ $hadirBulanIni }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Terlambat</dt><dd class="font-medium text-slate-800">{{ $terlambatBulanIni }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Izin</dt><dd class="font-medium text-slate-800">{{ $izinBulanIni }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Sakit</dt><dd class="font-medium text-slate-800">{{ $sakitBulanIni }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Alpa</dt><dd class="font-medium text-slate-800">{{ $alpaBulanIni }}</dd></div>
            </dl>
        </x-card>
    </div>
</x-app-layout>