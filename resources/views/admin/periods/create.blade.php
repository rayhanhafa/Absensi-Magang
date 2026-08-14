<x-app-layout title="Tambah Periode Magang">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Tambah Periode Magang</h1>
    </div>

    <form method="POST" action="{{ route('admin.periods.store') }}">
        @csrf

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input label="Nama Periode" name="nama_periode" placeholder="Contoh: MagangHub 2026" />
                </div>
                <x-input label="Tanggal Mulai" name="tanggal_mulai" type="date" />
                <x-input label="Tanggal Selesai" name="tanggal_selesai" type="date" />
                <x-select
                    label="Status"
                    name="status"
                    :options="['aktif' => 'Aktif', 'selesai' => 'Selesai']"
                    :selected="'aktif'"
                    placeholder=""
                />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Simpan</x-button>
            <a href="{{ route('admin.periods.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>