@props(['status'])

@php
    $map = [
        'aktif' => ['bg-green-100 text-green-700', 'Aktif'],
        'selesai' => ['bg-slate-100 text-slate-600', 'Selesai'],
        'nonaktif' => ['bg-red-100 text-red-700', 'Nonaktif'],
        'hadir' => ['bg-green-100 text-green-700', 'Hadir'],
        'terlambat' => ['bg-amber-100 text-amber-700', 'Terlambat'],
        'izin' => ['bg-blue-100 text-blue-700', 'Izin'],
        'sakit' => ['bg-purple-100 text-purple-700', 'Sakit'],
        'alpa' => ['bg-red-100 text-red-700', 'Alpa'],
        'pending' => ['bg-amber-100 text-amber-700', 'Menunggu'],
        'approved' => ['bg-green-100 text-green-700', 'Disetujui'],
        'rejected' => ['bg-red-100 text-red-700', 'Ditolak'],
    ];

    [$classes, $label] = $map[$status] ?? ['bg-slate-100 text-slate-600', ucfirst($status)];
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $classes }}">
    {{ $label }}
</span>