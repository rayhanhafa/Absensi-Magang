<x-app-layout title="Detail Absensi">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Informasi lengkap kehadiran pada tanggal {{ $attendance->tanggal->translatedFormat('d F Y') }}.</p>
        </div>
        <div>
            <button onclick="history.back()" class="w-full sm:w-auto inline-flex justify-center items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-lg text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 active:bg-slate-100 transition-colors shadow-sm">
                <x-dynamic-icon name="arrow-left" class="w-4 h-4" />
                Kembali
            </button>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Informasi Peserta" class="shadow-sm border-slate-200/80">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Nama</p>
                        <p class="font-semibold text-slate-800">{{ $attendance->intern->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Instansi/Universitas</p>
                        <p class="font-semibold text-slate-800">{{ $attendance->intern->instansi ?? $attendance->intern->universitas ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Tanggal</p>
                        <p class="font-semibold text-slate-800">{{ $attendance->tanggal->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Status Kehadiran</p>
                        <div class="mt-0.5"><x-badge :status="$attendance->status" /></div>
                    </div>
                    @if ($attendance->catatan)
                        <div class="sm:col-span-2 mt-2">
                            <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Catatan</p>
                            <p class="text-slate-700 text-sm bg-slate-50 p-3 rounded-lg border border-slate-100">{{ $attendance->catatan }}</p>
                        </div>
                    @endif
                </div>
            </x-card>

            <x-card title="Waktu & Lokasi" class="shadow-sm border-slate-200/80">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 divide-y md:divide-y-0 md:divide-x divide-slate-100">
                    {{-- Check In --}}
                    <div class="pt-4 md:pt-0 first:pt-0 pr-0 md:pr-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <x-dynamic-icon name="arrow-left" class="w-4 h-4 rotate-45" />
                            </div>
                            <h3 class="font-semibold text-slate-800">Check In</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Waktu</p>
                                <p class="font-semibold text-slate-800">{{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}</p>
                            </div>
                            
                            @if($attendance->keterlambatan > 0)
                                <div>
                                    <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Keterlambatan</p>
                                    <span class="text-amber-600 font-semibold">{{ $attendance->keterlambatan }} menit</span>
                                </div>
                            @endif
                            
                            @if ($attendance->latitude && $attendance->longitude)
                                <div>
                                    <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Lokasi GPS</p>
                                    <div class="text-sm font-medium text-slate-700 flex flex-col gap-1 mt-1">
                                        <span class="flex items-center gap-1.5"><x-dynamic-icon name="map-pin" class="w-4 h-4 text-slate-400" /> {{ $attendance->latitude }}, {{ $attendance->longitude }}</span>
                                        @if ($attendance->distance_check_in !== null)
                                            <span class="text-slate-500 text-xs pl-5">Jarak: {{ $attendance->distance_check_in }}m</span>
                                        @endif
                                        @if ($attendance->location_status_check_in)
                                            <div class="pl-5 mt-1">
                                                <x-badge :status="$attendance->location_status_check_in === 'valid' ? 'valid' : 'invalid'" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($attendance->foto_check_in)
                                <div>
                                    <p class="text-xs text-slate-500 font-medium mb-2 uppercase tracking-wider">Foto Check In</p>
                                    <img src="{{ route('files.attendance.photo', [$attendance, 'type' => 'check-in']) }}" alt="Foto Check In" class="rounded-xl w-full aspect-square object-cover border border-slate-200 shadow-sm cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Check Out --}}
                    <div class="pt-6 md:pt-0 pl-0 md:pl-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                                <x-dynamic-icon name="arrow-left" class="w-4 h-4 -rotate-[135deg]" />
                            </div>
                            <h3 class="font-semibold text-slate-800">Check Out</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Waktu</p>
                                <p class="font-semibold text-slate-800">{{ $attendance->waktu_pulang?->format('H:i') ?? '-' }}</p>
                            </div>
                            
                            @if ($attendance->distance_check_out !== null)
                                <div>
                                    <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Lokasi GPS</p>
                                    <div class="text-sm font-medium text-slate-700 flex flex-col gap-1 mt-1">
                                        <span class="text-slate-500 text-xs">Jarak: {{ $attendance->distance_check_out }}m</span>
                                    </div>
                                </div>
                            @endif

                            @if($attendance->foto_check_out)
                                <div>
                                    <p class="text-xs text-slate-500 font-medium mb-2 uppercase tracking-wider">Foto Check Out</p>
                                    <img src="{{ route('files.attendance.photo', [$attendance, 'type' => 'check-out']) }}" alt="Foto Check Out" class="rounded-xl w-full aspect-square object-cover border border-slate-200 shadow-sm cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <div>
            @if(in_array($attendance->status, ['izin', 'sakit']) && $attendance->leaveRequest)
                <x-card title="Keterangan Izin/Sakit" class="shadow-sm border-slate-200/80 sticky top-24">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-wider">Alasan</p>
                            <p class="text-slate-700 text-sm bg-slate-50 p-3 rounded-lg border border-slate-100">{{ $attendance->leaveRequest->alasan }}</p>
                        </div>
                        @if($attendance->leaveRequest->bukti_path)
                            <div>
                                <p class="text-xs text-slate-500 font-medium mb-2 uppercase tracking-wider">Bukti Lampiran</p>
                                <a href="{{ Storage::url($attendance->leaveRequest->bukti_path) }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors group">
                                    <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                                        <x-dynamic-icon name="document" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 group-hover:text-primary-600 transition-colors">Lihat Dokumen</p>
                                        <p class="text-xs text-slate-500">Klik untuk membuka</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>