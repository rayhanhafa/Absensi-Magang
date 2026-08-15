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
        <div class="mb-4 text-sm space-y-2">
            <div class="flex justify-between">
                <span class="text-slate-500">Koordinat</span>
                <span class="font-medium text-slate-800">{{ $attendance->latitude }}, {{ $attendance->longitude }}</span>
            </div>
            @if ($attendance->accuracy_check_in)
                <div class="flex justify-between">
                    <span class="text-slate-500">Akurasi Check-in</span>
                    <span class="font-medium text-slate-800">±{{ $attendance->accuracy_check_in }}m</span>
                </div>
            @endif
            @if ($attendance->distance_check_in !== null)
                <div class="flex justify-between">
                    <span class="text-slate-500">Jarak Check-in</span>
                    <span class="font-medium text-slate-800">{{ $attendance->distance_check_in }}m</span>
                </div>
            @endif
            @if ($attendance->location_status_check_in)
                <div class="flex justify-between">
                    <span class="text-slate-500">Status Lokasi Check-in</span>
                    <x-badge :status="$attendance->location_status_check_in === 'valid' ? 'hadir' : 'alpa'" />
                </div>
            @endif
            @if ($attendance->distance_check_out !== null)
                <div class="flex justify-between">
                    <span class="text-slate-500">Jarak Check-out</span>
                    <span class="font-medium text-slate-800">{{ $attendance->distance_check_out }}m</span>
                </div>
            @endif
        </div>
    @else
        <p class="text-sm text-slate-400 mb-4">Data lokasi tidak tersedia.</p>
    @endif

    @if ($attendance->foto_check_in)
        <div class="mb-4">
            <p class="text-xs text-slate-500 mb-1.5">Selfie Check-in</p>
            <img src="{{ route('files.attendance.photo', [$attendance, 'type' => 'check-in']) }}" class="w-full rounded-lg aspect-square object-cover" alt="Selfie check-in">
        </div>
    @endif

    @if ($attendance->foto_check_out)
        <div>
            <p class="text-xs text-slate-500 mb-1.5">Selfie Check-out</p>
            <img src="{{ route('files.attendance.photo', [$attendance, 'type' => 'check-out']) }}" class="w-full rounded-lg aspect-square object-cover" alt="Selfie check-out">
        </div>
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