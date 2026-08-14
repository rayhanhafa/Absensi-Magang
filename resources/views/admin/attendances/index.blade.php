<x-app-layout title="Data Absensi">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Data Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola dan koreksi data absensi seluruh peserta.</p>
    </div>

    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input
                type="date"
                name="tanggal"
                value="{{ request('tanggal') }}"
                class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="hadir" @selected(request('status') == 'hadir')>Hadir</option>
                <option value="terlambat" @selected(request('status') == 'terlambat')>Terlambat</option>
                <option value="izin" @selected(request('status') == 'izin')>Izin</option>
                <option value="sakit" @selected(request('status') == 'sakit')>Sakit</option>
                <option value="alpa" @selected(request('status') == 'alpa')>Alpa</option>
            </select>
            <select name="intern_id" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Peserta</option>
                @foreach ($interns as $intern)
                    <option value="{{ $intern->id }}" @selected(request('intern_id') == $intern->id)>{{ $intern->user->name }}</option>
                @endforeach
            </select>
            <x-button type="submit" variant="secondary">Filter</x-button>
        </form>
    </x-card>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Tanggal</th>
                        <th class="px-5 py-3 font-medium">Jam Masuk</th>
                        <th class="px-5 py-3 font-medium">Jam Pulang</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
    @forelse ($attendances as $attendance)
        <tbody x-data="{ editing: false }" class="contents">
            <tr>
                <td class="px-5 py-3 font-medium text-slate-800">{{ $attendance->intern->user->name }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $attendance->tanggal->translatedFormat('d M Y') }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_masuk?->format('H:i') ?? '-' }}</td>
                <td class="px-5 py-3 text-slate-600">{{ $attendance->waktu_pulang?->format('H:i') ?? '-' }}</td>
                <td class="px-5 py-3"><x-badge :status="$attendance->status" /></td>
                <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                    <a href="{{ route('attendance.show', $attendance) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                    <button type="button" @click="editing = !editing" class="text-slate-600 hover:text-slate-800 font-medium" x-text="editing ? 'Tutup' : 'Koreksi'"></button>
                </td>
            </tr>
            <tr x-show="editing" x-cloak>
                <td colspan="6" class="px-5 py-4 bg-slate-50">
                    <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}" class="grid sm:grid-cols-5 gap-3 items-end">
                        @csrf
                        @method('PUT')
                        <x-select
                            label="Status"
                            name="status"
                            :options="['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa']"
                            :selected="$attendance->status"
                            placeholder=""
                        />
                        <x-input label="Jam Masuk" name="waktu_masuk" type="time" :value="$attendance->waktu_masuk?->format('H:i')" />
                        <x-input label="Jam Pulang" name="waktu_pulang" type="time" :value="$attendance->waktu_pulang?->format('H:i')" />
                        <x-input label="Catatan" name="catatan" :value="$attendance->catatan" />
                        <x-button type="submit" variant="primary">Simpan</x-button>
                    </form>
                </td>
            </tr>
        </tbody>
    @empty
        <tbody>
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data absensi.</td>
            </tr>
        </tbody>
    @endforelse
</tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="mt-4 px-5">{{ $attendances->links() }}</div>
        @endif
    </x-card>
</x-app-layout>