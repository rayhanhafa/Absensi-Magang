<x-app-layout title="Detail Absensi">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Detail Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $attendance->tanggal->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <x-card title="Informasi Kehadiran" class="lg:col-span-2">
            <dl class="grid grid-cols-2 gap-y-4 text-sm">
                <div><dt class="text-slate-500 mb-1">Peserta</dt><dd class="font-medium text-slate-800">{{ $attendance->intern->user->name }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Mentor</dt><dd class="font-medium text-slate-800">{{ $attendance->intern->mentor?->user?->name ?? '-' }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Jam Masuk</dt><dd class="font-medium text-slate-800">{{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Jam Pulang</dt><dd class="font-medium text-slate-800">{{ $attendance->waktu_pulang?->format('H:i') ?? '-' }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Keterlambatan</dt><dd class="font-medium text-slate-800">{{ $attendance->keterlambatan }} menit</dd></div>
                <div><dt class="text-slate-500 mb-1">Status</dt><dd><x-badge :status="$attendance->status" /></dd></div>
                @if ($attendance->catatan)
                    <div class="col-span-2"><dt class="text-slate-500 mb-1">Catatan</dt><dd class="font-medium text-slate-800">{{ $attendance->catatan }}</dd></div>
                @endif
            </dl>
        </x-card>

        <x-card title="Lokasi & Bukti Foto">
            @if ($attendance->latitude && $attendance->longitude)
                <p class="text-sm text-slate-600 mb-3">
                    Koordinat: {{ $attendance->latitude }}, {{ $attendance->longitude }}
                </p>
            @else
                <p class="text-sm text-slate-400 mb-3">Data lokasi tidak tersedia.</p>
            @endif

            @if (! $attendance->foto_check_in && ! $attendance->foto_check_out)
                <p class="text-sm text-slate-400">Belum ada foto bukti absensi.</p>
            @endif
        </x-card>
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    </div>
</x-app-layout>