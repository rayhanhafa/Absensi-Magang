<x-app-layout title="Data Absensi">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan pantau data kehadiran semua pemagang.</p>
        </div>
    </div>

    <x-card class="mb-6 shadow-sm border-slate-200/80">
        <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3">
            <div class="w-full sm:w-48">
                <x-input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal', date('Y-m-d')) }}"
                />
            </div>
            <div class="w-full sm:w-48">
                <x-select name="status" :options="[
                    'hadir' => 'Hadir',
                    'terlambat' => 'Terlambat',
                    'izin' => 'Izin',
                    'sakit' => 'Sakit',
                    'alpa' => 'Alpa'
                ]" :selected="request('status')" placeholder="Semua Status" />
            </div>
            <div class="w-full sm:w-48">
                <x-select name="intern_id" :options="$interns->pluck('user.name', 'id')->toArray()" :selected="request('intern_id')" placeholder="Semua Peserta" />
            </div>
            <div class="w-full sm:w-auto">
                <x-button type="submit" variant="primary" class="w-full sm:w-auto">Filter</x-button>
            </div>
            
            @if(request()->hasAny(['tanggal', 'status', 'intern_id']))
                <div class="w-full sm:w-auto sm:ml-auto">
                    <a href="{{ route('admin.attendances.index') }}">
                        <x-button type="button" variant="ghost" class="w-full sm:w-auto text-slate-500">Reset</x-button>
                    </a>
                </div>
            @endif
        </form>
    </x-card>

    <x-card noPadding class="shadow-sm border-slate-200/80">
        <div class="overflow-x-auto">
            <table class="w-full text-sm table-row-hover">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200 bg-slate-50/50">
                        <th class="px-5 py-3.5 font-semibold">Nama</th>
                        <th class="px-5 py-3.5 font-semibold">Instansi</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Masuk</th>
                        <th class="px-5 py-3.5 font-semibold">Jam Pulang</th>
                        <th class="px-5 py-3.5 font-semibold">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tbody x-data="{ editing: false }" class="contents">
                            <tr class="transition-colors hover:bg-slate-50/50">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-800">{{ $attendance->intern->user->name }}</div>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $attendance->intern->instansi ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-600">
                                    @if($attendance->waktu_masuk)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                            <x-dynamic-icon name="clock" class="w-3 h-3" />
                                            {{ $attendance->waktu_masuk->format('H:i') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-600">
                                    @if($attendance->waktu_pulang)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-100 text-slate-700 text-xs font-medium">
                                            <x-dynamic-icon name="clock" class="w-3 h-3" />
                                            {{ $attendance->waktu_pulang->format('H:i') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-3"><x-badge :status="$attendance->status" /></td>
                                <td class="px-5 py-3 text-right space-x-2">
                                    <a href="{{ route('attendance.show', $attendance) }}" class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-800 font-medium text-xs bg-primary-50 px-2.5 py-1.5 rounded-lg transition-colors">
                                        Detail
                                    </a>
                                    <button type="button" @click="editing = !editing" class="inline-flex items-center gap-1 text-slate-600 hover:text-slate-800 font-medium text-xs bg-slate-100 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <span x-text="editing ? 'Tutup' : 'Koreksi'"></span>
                                    </button>
                                </td>
                            </tr>
                            {{-- Inline Edit Form --}}
                            <tr x-show="editing" x-cloak>
                                <td colspan="6" class="px-5 py-4 bg-primary-50/50 border-t border-primary-100">
                                    <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}" class="flex flex-wrap items-end gap-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="w-32">
                                            <x-select
                                                name="status"
                                                :options="['hadir' => 'Hadir', 'terlambat' => 'Terlambat', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa']"
                                                :selected="$attendance->status"
                                                placeholder=""
                                            />
                                        </div>
                                        <div class="w-32">
                                            <x-input name="waktu_masuk" type="time" :value="$attendance->waktu_masuk?->format('H:i')" />
                                        </div>
                                        <div class="w-32">
                                            <x-input name="waktu_pulang" type="time" :value="$attendance->waktu_pulang?->format('H:i')" />
                                        </div>
                                        <div>
                                            <x-button type="submit" variant="primary" size="sm">Simpan</x-button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <x-dynamic-icon name="calendar" class="w-6 h-6" />
                                    </div>
                                    <p class="text-slate-500 font-medium">Belum ada data absensi</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attendances->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $attendances->links() }}
            </div>
        @endif
    </x-card>
</x-app-layout>