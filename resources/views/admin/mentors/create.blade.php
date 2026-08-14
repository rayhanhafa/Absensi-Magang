<x-app-layout title="Tambah Mentor">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Tambah Mentor</h1>
        <p class="text-sm text-slate-500 mt-1">Lengkapi data akun dan data mentor baru.</p>
    </div>

    <form method="POST" action="{{ route('admin.mentors.store') }}">
        @csrf

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Nama Lengkap" name="name" />
                <x-input label="Email" name="email" type="email" />
                <x-input label="Password" name="password" type="password" />
                <x-input label="NIP" name="nip" />
                <x-input label="Jabatan" name="jabatan" />
                <x-input label="Bagian" name="bagian" />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Simpan</x-button>
            <a href="{{ route('admin.mentors.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>