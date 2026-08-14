<x-app-layout title="Tambah Peserta Magang">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Tambah Peserta Magang</h1>
        <p class="text-sm text-slate-500 mt-1">Lengkapi data akun dan data magang peserta baru.</p>
    </div>

    <form method="POST" action="{{ route('admin.interns.store') }}">
        @csrf

        <x-card title="Data Akun" class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Nama Lengkap" name="name" />
                <x-input label="Email" name="email" type="email" />
                <x-input label="Password" name="password" type="password" />
                <x-input label="Nomor Induk" name="nomor_induk" />
                <x-input label="Nomor HP" name="nomor_hp" />
            </div>
        </x-card>

        <x-card title="Data Magang" class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-input label="Universitas" name="universitas" />
                <x-input label="Jurusan" name="jurusan" />

                <x-select
                    label="Mentor"
                    name="mentor_id"
                    :options="$mentors->pluck('user.name', 'id')"
                    placeholder="Belum ditentukan"
                />
                <x-select
                    label="Periode Magang"
                    name="internship_period_id"
                    :options="$periods->pluck('nama_periode', 'id')"
                />

                <x-input label="Tanggal Mulai" name="tanggal_mulai" type="date" />
                <x-input label="Tanggal Selesai" name="tanggal_selesai" type="date" />

                <x-select
                    label="Status"
                    name="status"
                    :options="['aktif' => 'Aktif', 'selesai' => 'Selesai', 'nonaktif' => 'Nonaktif']"
                    :selected="'aktif'"
                    placeholder=""
                />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Simpan</x-button>
            <a href="{{ route('admin.interns.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>