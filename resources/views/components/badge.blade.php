@props(['status'])

@php
    $map = [
        'aktif'    => ['bg-emerald-100 text-emerald-700 border border-emerald-200', '●', 'Aktif'],
        'selesai'  => ['bg-slate-100 text-slate-600 border border-slate-200', '●', 'Selesai'],
        'nonaktif' => ['bg-red-100 text-red-600 border border-red-200', '●', 'Nonaktif'],
        'hadir'    => ['bg-emerald-100 text-emerald-700 border border-emerald-200', '●', 'Hadir'],
        'terlambat'=> ['bg-amber-100 text-amber-700 border border-amber-200', '●', 'Terlambat'],
        'izin'     => ['bg-blue-100 text-blue-700 border border-blue-200', '●', 'Izin'],
        'sakit'    => ['bg-purple-100 text-purple-700 border border-purple-200', '●', 'Sakit'],
        'alpa'     => ['bg-red-100 text-red-600 border border-red-200', '●', 'Alpa'],
        'pending'  => ['bg-amber-100 text-amber-700 border border-amber-200', '●', 'Menunggu'],
        'approved' => ['bg-emerald-100 text-emerald-700 border border-emerald-200', '●', 'Disetujui'],
        'rejected' => ['bg-red-100 text-red-600 border border-red-200', '●', 'Ditolak'],
        'valid'    => ['bg-emerald-100 text-emerald-700 border border-emerald-200', '●', 'Valid'],
        'invalid'  => ['bg-red-100 text-red-600 border border-red-200', '●', 'Tidak Valid'],
    ];

    [$classes, $dot, $label] = $map[$status] ?? ['bg-slate-100 text-slate-600 border border-slate-200', '●', ucfirst($status)];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $classes }}">
    <span class="text-[8px] leading-none">{{ $dot }}</span>
    {{ $label }}
</span>