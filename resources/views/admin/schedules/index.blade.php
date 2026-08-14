<x-app-layout title="Jadwal Kerja">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Jadwal Kerja</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola jadwal kerja dan toleransi keterlambatan.</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}">
            <x-button variant="primary">+ Tambah Jadwal</x-button>
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama Jadwal</th>
                        <th class="px-5 py-3 font-medium">Jam Masuk</th>
                        <th class="px-5 py-3 font-medium">Jam Pulang</th>
                        <th class="px-5 py-3 font-medium">Toleransi</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $schedule->nama_jadwal }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $schedule->jam_masuk->format('H:i') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $schedule->jam_pulang->format('H:i') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $schedule->toleransi_keterlambatan }} menit</td>
                            <td class="px-5 py-3">
                                @if ($schedule->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="text-slate-600 hover:text-slate-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada jadwal kerja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>