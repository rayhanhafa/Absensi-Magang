<x-app-layout title="Edit Lokasi Absensi">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Edit Lokasi Absensi</h1>
    </div>

    <form method="POST" action="{{ route('admin.office-settings.update', $officeSetting) }}" x-data="officeLocationPicker()">
        @csrf
        @method('PUT')

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <x-input label="Nama Lokasi" name="name" :value="$officeSetting->name" />
                </div>

                <x-input label="Latitude" name="latitude" type="text" :value="$officeSetting->latitude" x-model="latitude" />
                <x-input label="Longitude" name="longitude" type="text" :value="$officeSetting->longitude" x-model="longitude" />

                <div class="sm:col-span-2">
                    <x-button type="button" variant="secondary" @click="detect()">
                        <span x-show="!detecting">📍 Gunakan Lokasi Saya Saat Ini</span>
                        <span x-show="detecting">Mendeteksi lokasi...</span>
                    </x-button>
                    <p class="text-sm text-red-600 mt-2" x-show="errorMessage" x-text="errorMessage"></p>
                </div>

                <x-input label="Radius (meter)" name="radius_meter" type="number" :value="$officeSetting->radius_meter" />

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked($officeSetting->is_active)>
                    <label for="is_active" class="text-sm text-slate-700">Jadikan lokasi aktif (menggantikan lokasi aktif lain)</label>
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Perbarui</x-button>
            <a href="{{ route('admin.office-settings.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>