<x-app-layout title="Edit Periode Magang">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Edit Periode Magang</h1>
    </div>

    <form method="POST" action="{{ route('admin.periods.update', $period) }}">
        @csrf
        @method('PUT')

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input label="Nama Periode" name="nama_periode" :value="$period->nama_periode" />
                </div>
                <x-input label="Tanggal Mulai" name="tanggal_mulai" type="date" :value="$period->tanggal_mulai->format('Y-m-d')" />
                <x-input label="Tanggal Selesai" name="tanggal_selesai" type="date" :value="$period->tanggal_selesai->format('Y-m-d')" />
                <x-select
                    label="Status"
                    name="status"
                    :options="['aktif' => 'Aktif', 'selesai' => 'Selesai']"
                    :selected="$period->status"
                    placeholder=""
                />
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Perbarui</x-button>
            <a href="{{ route('admin.periods.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>