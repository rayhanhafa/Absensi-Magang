<x-app-layout title="Tambah Lokasi Absensi">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Tambah Lokasi Absensi</h1>
        <p class="text-sm text-slate-500 mt-1">Klik tombol di bawah untuk mengisi koordinat otomatis dari lokasi Anda saat ini, atau isi manual.</p>
    </div>

    <form method="POST" action="{{ route('admin.office-settings.store') }}" x-data="officeLocationPicker()">
        @csrf

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input label="Nama Lokasi" name="name" placeholder="Contoh: Kantor Pusat" />
                </div>

                <x-input label="Latitude" name="latitude" type="text" x-model="latitude" />
                <x-input label="Longitude" name="longitude" type="text" x-model="longitude" />

                <div class="sm:col-span-2">
                    <x-button type="button" variant="secondary" @click="detect()">
                        <span x-show="!detecting">📍 Gunakan Lokasi Saya Saat Ini</span>
                        <span x-show="detecting">Mendeteksi lokasi...</span>
                    </x-button>
                    <p class="text-sm text-red-600 mt-2" x-show="errorMessage" x-text="errorMessage"></p>
                </div>

                <x-input label="Radius (meter)" name="radius_meter" type="number" value="100" />

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" checked>
                    <label for="is_active" class="text-sm text-slate-700">Jadikan lokasi aktif (menggantikan lokasi aktif lain)</label>
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Simpan</x-button>
            <a href="{{ route('admin.office-settings.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>