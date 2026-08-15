<div x-data="attendanceFlow('{{ $action }}', {{ config('attendance.require_location') ? 'true' : 'false' }})" x-init="photoFieldName = '{{ $photoFieldName }}'">

    <template x-if="step === 'idle'">
        <x-button type="button" :variant="$buttonVariant" @click="start()">{{ $buttonLabel }}</x-button>
    </template>

    <template x-if="step === 'locating'">
        <p class="text-sm text-slate-500 flex items-center gap-2">
            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
            📍 Mendeteksi lokasi Anda...
        </p>
    </template>

    <template x-if="step === 'location-error'">
        <div>
            <p class="text-sm text-red-600 mb-3" x-text="errorMessage"></p>
            <x-button type="button" variant="secondary" @click="start()">Coba Lagi</x-button>
        </div>
    </template>

    <template x-if="step === 'camera'">
        <div @photo-captured="handlePhoto($event.detail.file)">
            <p class="text-sm text-green-700 mb-3">📍 Lokasi ditemukan (akurasi ±<span x-text="Math.round(location.accuracy)"></span>m)</p>
            <x-camera-capture :label="$buttonVariant === 'primary' ? 'Selfie Check-in' : 'Selfie Check-out'" />
        </div>
    </template>

    <template x-if="step === 'ready'">
        <div>
            <p class="text-sm text-green-700 mb-3">📍 Lokasi ditemukan (akurasi ±<span x-text="Math.round(location.accuracy)"></span>m)</p>

            <template x-if="errorMessage">
                <p class="text-sm text-red-600 mb-3" x-text="errorMessage"></p>
            </template>

            <template x-if="photoPreviewUrl">
                <div class="mb-3">
                    <img :src="photoPreviewUrl" class="w-40 rounded-lg aspect-square object-cover" alt="Preview selfie">
                    <button type="button" class="text-xs text-slate-500 hover:text-slate-700 mt-1" @click="retakePhoto()">Ambil ulang foto</button>
                </div>
            </template>

            <x-button type="button" :variant="$buttonVariant" @click="submit()">{{ $buttonLabel }}</x-button>
        </div>
    </template>

    <template x-if="step === 'submitting'">
        <p class="text-sm text-slate-500">Menyimpan absensi...</p>
    </template>

</div>