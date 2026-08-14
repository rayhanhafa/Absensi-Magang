<x-app-layout title="Edit Mentor">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Edit Mentor</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $mentor->user->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.mentors.update', $mentor) }}">
        @csrf
        @method('PUT')

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Nama Lengkap" name="name" :value="$mentor->user->name" />
                <x-input label="Email" name="email" type="email" :value="$mentor->user->email" />
                <x-input label="NIP" name="nip" :value="$mentor->nip" />
                <x-input label="Jabatan" name="jabatan" :value="$mentor->jabatan" />
                <x-input label="Bagian" name="bagian" :value="$mentor->bagian" />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Perbarui</x-button>
            <a href="{{ route('admin.mentors.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>