<x-app-layout title="Tambah Jadwal Kerja">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Tambah Jadwal Kerja</h1>
    </div>

    <form method="POST" action="{{ route('admin.schedules.store') }}">
        @csrf

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input label="Nama Jadwal" name="nama_jadwal" placeholder="Contoh: Jadwal Reguler" />
                </div>
                <x-input label="Jam Masuk" name="jam_masuk" type="time" />
                <x-input label="Jam Pulang" name="jam_pulang" type="time" />
                <x-input label="Toleransi Keterlambatan (menit)" name="toleransi_keterlambatan" type="number" value="15" />

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" checked>
                    <label for="is_active" class="text-sm text-slate-700">Jadikan jadwal aktif (menggantikan jadwal aktif lain)</label>
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Simpan</x-button>
            <a href="{{ route('admin.schedules.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>