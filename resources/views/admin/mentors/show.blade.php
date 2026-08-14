<x-app-layout title="Detail Mentor">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">{{ $mentor->user->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $mentor->jabatan }} — {{ $mentor->bagian }}</p>
        </div>
        <a href="{{ route('admin.mentors.edit', $mentor) }}">
            <x-button variant="secondary">Edit Data</x-button>
        </a>
    </div>

    <x-card title="Peserta Bimbingan">
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Universitas</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($mentor->interns as $intern)
                        <tr>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $intern->user->name }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $intern->universitas }}</td>
                            <td class="px-5 py-3"><x-badge :status="$intern->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada peserta bimbingan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>