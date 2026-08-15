<x-app-layout title="Lokasi Absensi">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Lokasi Absensi</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola titik lokasi & radius area check-in/check-out.</p>
        </div>
        <a href="{{ route('admin.office-settings.create') }}">
            <x-button variant="primary">+ Tambah Lokasi</x-button>
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama Lokasi</th>
                        <th class="px-5 py-3 font-medium">Koordinat</th>
                        <th class="px-5 py-3 font-medium">Radius</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($officeSettings as $office)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $office->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $office->latitude }}, {{ $office->longitude }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $office->radius_meter }} meter</td>
                            <td class="px-5 py-3">
                                @if ($office->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.office-settings.edit', $office) }}" class="text-slate-600 hover:text-slate-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.office-settings.destroy', $office) }}" class="inline" onsubmit="return confirm('Hapus lokasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada lokasi absensi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($officeSettings->hasPages())
            <div class="mt-4 px-5">{{ $officeSettings->links() }}</div>
        @endif
    </x-card>
</x-app-layout>