<x-app-layout title="Mentor">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Mentor</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data mentor pembimbing.</p>
        </div>
        <a href="{{ route('admin.mentors.create') }}">
            <x-button variant="primary">+ Tambah Mentor</x-button>
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">NIP</th>
                        <th class="px-5 py-3 font-medium">Jabatan</th>
                        <th class="px-5 py-3 font-medium">Bagian</th>
                        <th class="px-5 py-3 font-medium">Peserta Bimbingan</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($mentors as $mentor)
                        <tr>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $mentor->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $mentor->user->email }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $mentor->nip ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $mentor->jabatan ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $mentor->bagian ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $mentor->interns_count }} peserta</td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.mentors.show', $mentor) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                                <a href="{{ route('admin.mentors.edit', $mentor) }}" class="text-slate-600 hover:text-slate-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.mentors.destroy', $mentor) }}" class="inline" onsubmit="return confirm('Hapus data mentor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data mentor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($mentors->hasPages())
            <div class="mt-4 px-5">{{ $mentors->links() }}</div>
        @endif
    </x-card>
</x-app-layout>