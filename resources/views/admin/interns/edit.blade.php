<x-app-layout title="Edit Peserta Magang">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Edit Peserta Magang</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $intern->user->name }}</p>
    </div>

    <form method="POST" action="{{ route('admin.interns.update', $intern) }}">
        @csrf
        @method('PUT')

        <x-card title="Data Akun" class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Nama Lengkap" name="name" :value="$intern->user->name" />
                <x-input label="Email" name="email" type="email" :value="$intern->user->email" />
                <x-input label="Nomor Induk" name="nomor_induk" :value="$intern->user->profile?->nomor_induk" />
                <x-input label="Nomor HP" name="nomor_hp" :value="$intern->user->profile?->nomor_hp" />
            </div>
        </x-card>

        <x-card title="Data Magang" class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Universitas" name="universitas" :value="$intern->universitas" />
                <x-input label="Jurusan" name="jurusan" :value="$intern->jurusan" />

                <x-select
                    label="Mentor"
                    name="mentor_id"
                    :options="$mentors->pluck('user.name', 'id')"
                    :selected="$intern->mentor_id"
                    placeholder="Belum ditentukan"
                />
                <x-select
                    label="Periode Magang"
                    name="internship_period_id"
                    :options="$periods->pluck('nama_periode', 'id')"
                    :selected="$intern->internship_period_id"
                />

                <x-input label="Tanggal Mulai" name="tanggal_mulai" type="date" :value="$intern->tanggal_mulai->format('Y-m-d')" />
                <x-input label="Tanggal Selesai" name="tanggal_selesai" type="date" :value="$intern->tanggal_selesai->format('Y-m-d')" />

                <x-select
                    label="Status"
                    name="status"
                    :options="['aktif' => 'Aktif', 'selesai' => 'Selesai', 'nonaktif' => 'Nonaktif']"
                    :selected="$intern->status"
                    placeholder=""
                />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Perbarui</x-button>
            <a href="{{ route('admin.interns.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>