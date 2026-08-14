<x-app-layout title="Detail Peserta Magang">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $intern->user->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $intern->user->email }}</p>
        </div>
        <a href="{{ route('admin.interns.edit', $intern) }}">
            <x-button variant="secondary">Edit Data</x-button>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <x-card title="Informasi Magang" class="lg:col-span-2">
            <dl class="grid grid-cols-2 gap-y-4 text-sm">
                <div><dt class="text-slate-500 mb-1">Universitas</dt><dd class="font-medium text-slate-800">{{ $intern->universitas }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Jurusan</dt><dd class="font-medium text-slate-800">{{ $intern->jurusan }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Mentor</dt><dd class="font-medium text-slate-800">{{ $intern->mentor?->user?->name ?? '-' }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Periode</dt><dd class="font-medium text-slate-800">{{ $intern->internshipPeriod->nama_periode }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Mulai</dt><dd class="font-medium text-slate-800">{{ $intern->tanggal_mulai->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Selesai</dt><dd class="font-medium text-slate-800">{{ $intern->tanggal_selesai->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Nomor Induk</dt><dd class="font-medium text-slate-800">{{ $intern->user->profile?->nomor_induk ?? '-' }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Status</dt><dd><x-badge :status="$intern->status" /></dd></div>
            </dl>
        </x-card>

        <x-card title="Rekap Bulan Ini">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-500">Hadir</dt><dd class="font-medium text-slate-800">{{ $rekapBulanIni->get('hadir', 0) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Terlambat</dt><dd class="font-medium text-slate-800">{{ $rekapBulanIni->get('terlambat', 0) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Izin</dt><dd class="font-medium text-slate-800">{{ $rekapBulanIni->get('izin', 0) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Sakit</dt><dd class="font-medium text-slate-800">{{ $rekapBulanIni->get('sakit', 0) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-500">Alpa</dt><dd class="font-medium text-slate-800">{{ $rekapBulanIni->get('alpa', 0) }}</dd></div>
            </dl>
        </x-card>
    </div>
</x-app-layout>