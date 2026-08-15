@props(['label' => 'Selfie'])

<div x-data="cameraCapture()" x-init="init($el); start()" {{ $attributes }}>
    <p class="text-sm font-medium text-slate-700 mb-2">📷 {{ $label }}</p>

    <template x-if="step === 'starting'">
        <p class="text-sm text-slate-500">Membuka kamera...</p>
    </template>

    <template x-if="step === 'error'">
        <div>
            <p class="text-sm text-red-600 mb-3" x-text="errorMessage"></p>
            <x-button type="button" variant="secondary" @click="start()">Coba Lagi</x-button>
        </div>
    </template>

    <div x-show="step === 'live'">
        <video data-camera-video class="w-full rounded-lg bg-slate-900 aspect-video object-cover" playsinline muted></video>
        <p class="text-xs text-slate-500 mt-2 mb-3">Pastikan wajah terlihat jelas.</p>
        <x-button type="button" variant="primary" @click="capture()">Ambil Foto</x-button>
    </div>

    <template x-if="step === 'captured'">
        <div>
            <img :src="photoUrl" class="w-full rounded-lg aspect-video object-cover" alt="Preview selfie">
            <div class="flex gap-3 mt-3">
                <x-button type="button" variant="secondary" @click="retake()">Ambil Ulang</x-button>
                <x-button type="button" variant="primary" @click="confirm()">Gunakan Foto</x-button>
            </div>
        </div>
    </template>

    <canvas data-camera-canvas class="hidden"></canvas>
</div>