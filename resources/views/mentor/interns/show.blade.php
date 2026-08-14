<x-app-layout title="Detail Peserta Bimbingan">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">{{ $intern->user->name }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $intern->user->email }}</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <x-card title="Informasi Magang" class="lg:col-span-2">
            <dl class="grid grid-cols-2 gap-y-4 text-sm">
                <div><dt class="text-slate-500 mb-1">Universitas</dt><dd class="font-medium text-slate-800">{{ $intern->universitas }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Jurusan</dt><dd class="font-medium text-slate-800">{{ $intern->jurusan }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Periode</dt><dd class="font-medium text-slate-800">{{ $intern->internshipPeriod->nama_periode }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Status</dt><dd><x-badge :status="$intern->status" /></dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Mulai</dt><dd class="font-medium text-slate-800">{{ $intern->tanggal_mulai->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Tanggal Selesai</dt><dd class="font-medium text-slate-800">{{ $intern->tanggal_selesai->translatedFormat('d F Y') }}</dd></div>
                <div><dt class="text-slate-500 mb-1">Nomor HP</dt><dd class="font-medium text-slate-800">{{ $intern->user->profile?->nomor_hp ?? '-' }}</dd></div>
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

    <div class="mt-6">
        <a href="{{ route('mentor.interns.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali</a>
    </div>
</x-app-layout>