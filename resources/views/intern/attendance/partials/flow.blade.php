<div x-data="attendanceFlow('{{ $action }}', {{ config('attendance.require_location') ? 'true' : 'false' }})" x-init="photoFieldName = '{{ $photoFieldName }}'" class="w-full">

    <template x-if="step === 'idle'">
        <x-button type="button" size="lg" :variant="$buttonVariant" @click="start()" class="w-full sm:w-auto font-semibold shadow-md">
            {{ $buttonLabel }}
        </x-button>
    </template>

    <template x-if="step === 'locating'">
        <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-200">
            <svg class="animate-spin w-5 h-5 text-primary-600" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <p class="text-sm font-medium text-slate-700">Mendeteksi lokasi Anda...</p>
        </div>
    </template>

    <template x-if="step === 'location-error'">
        <div class="p-4 rounded-xl bg-red-50 border border-red-100">
            <p class="text-sm text-red-600 font-medium mb-3 flex items-center gap-2">
                <x-dynamic-icon name="x-circle" class="w-5 h-5" />
                <span x-text="errorMessage"></span>
            </p>
            <x-button type="button" variant="secondary" size="sm" @click="start()">Coba Lagi</x-button>
        </div>
    </template>

    <template x-if="step === 'camera'">
        <div @photo-captured="handlePhoto($event.detail.file)" class="animate-fade-in space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100">
                <x-dynamic-icon name="map-pin" class="w-4 h-4" />
                Lokasi ditemukan (±<span x-text="Math.round(location.accuracy)"></span>m)
            </div>
            
            <div class="rounded-xl overflow-hidden shadow-card border border-slate-200 bg-slate-900">
                <x-camera-capture :label="$buttonVariant === 'primary' ? 'Selfie Check-in' : 'Selfie Check-out'" class="p-4 text-white" />
            </div>
        </div>
    </template>

    <template x-if="step === 'ready'">
        <div class="animate-fade-in space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium border border-emerald-100">
                <x-dynamic-icon name="map-pin" class="w-4 h-4" />
                Lokasi ditemukan (±<span x-text="Math.round(location.accuracy)"></span>m)
            </div>

            <template x-if="errorMessage">
                <div class="p-3 rounded-lg bg-red-50 border border-red-100 flex items-start gap-2">
                    <x-dynamic-icon name="x-circle" class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" />
                    <p class="text-sm text-red-600 font-medium" x-text="errorMessage"></p>
                </div>
            </template>

            <template x-if="photoPreviewUrl">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <img :src="photoPreviewUrl" class="w-48 max-w-full rounded-lg shadow-sm border border-slate-300 object-cover aspect-video mb-3" alt="Preview selfie">
                    <x-button type="button" variant="secondary" size="sm" @click="retakePhoto()">
                        <x-dynamic-icon name="camera" class="w-4 h-4 mr-1" /> Ambil Ulang
                    </x-button>
                </div>
            </template>

            <x-button type="button" size="lg" :variant="$buttonVariant" @click="submit()" class="w-full sm:w-auto font-semibold shadow-md">
                Kirim {{ $buttonLabel }}
            </x-button>
        </div>
    </template>

    <template x-if="step === 'submitting'">
        <div class="flex items-center gap-3 p-4 rounded-xl bg-primary-50 border border-primary-100">
            <svg class="animate-spin w-5 h-5 text-primary-600" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <p class="text-sm font-medium text-primary-700">Menyimpan absensi...</p>
        </div>
    </template>

</div>