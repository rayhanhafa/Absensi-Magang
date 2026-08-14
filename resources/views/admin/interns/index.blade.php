<x-app-layout title="Peserta Magang">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Peserta Magang</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data peserta magang.</p>
        </div>
        <a href="{{ route('admin.interns.create') }}">
            <x-button variant="primary">+ Tambah Peserta</x-button>
        </a>
    </div>

    <x-card class="mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama..."
                class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
            <select name="mentor_id" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Mentor</option>
                @foreach ($mentors as $mentor)
                    <option value="{{ $mentor->id }}" @selected(request('mentor_id') == $mentor->id)>{{ $mentor->user->name }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-lg border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="aktif" @selected(request('status') == 'aktif')>Aktif</option>
                <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                <option value="nonaktif" @selected(request('status') == 'nonaktif')>Nonaktif</option>
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
                        <th class="px-5 py-3 font-medium">Universitas</th>
                        <th class="px-5 py-3 font-medium">Mentor</th>
                        <th class="px-5 py-3 font-medium">Periode</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($interns as $intern)
                        <tr>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $intern->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $intern->user->email }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $intern->universitas }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $intern->mentor?->user?->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $intern->internshipPeriod->nama_periode }}</td>
                            <td class="px-5 py-3"><x-badge :status="$intern->status" /></td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.interns.show', $intern) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                                <a href="{{ route('admin.interns.edit', $intern) }}" class="text-slate-600 hover:text-slate-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.interns.destroy', $intern) }}" class="inline" onsubmit="return confirm('Hapus data peserta ini? Tindakan tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data peserta magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($interns->hasPages())
            <div class="mt-4 px-5">{{ $interns->links() }}</div>
        @endif
    </x-card>
</x-app-layout>