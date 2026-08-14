<x-app-layout title="Peserta Bimbingan">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Peserta Bimbingan</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar peserta magang yang Anda bimbing.</p>
    </div>

    <x-card>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b border-slate-200">
                        <th class="px-5 py-3 font-medium">Nama</th>
                        <th class="px-5 py-3 font-medium">Universitas</th>
                        <th class="px-5 py-3 font-medium">Jurusan</th>
                        <th class="px-5 py-3 font-medium">Periode</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Detail</th>
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
                            <td class="px-5 py-3 text-slate-600">{{ $intern->jurusan }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $intern->internshipPeriod->nama_periode }}</td>
                            <td class="px-5 py-3"><x-badge :status="$intern->status" /></td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('mentor.interns.show', $intern) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada peserta bimbingan.</td>
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