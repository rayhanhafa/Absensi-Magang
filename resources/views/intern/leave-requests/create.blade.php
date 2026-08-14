<x-app-layout title="Ajukan Izin">
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-800">Ajukan Izin</h1>
        <p class="text-sm text-slate-500 mt-1">Isi form berikut untuk mengajukan izin atau sakit.</p>
    </div>

    <form method="POST" action="{{ route('intern.leave-requests.store') }}" enctype="multipart/form-data">
        @csrf

        <x-card class="mb-6">
            <div class="grid sm:grid-cols-2 gap-4">
                <x-select
                    label="Jenis"
                    name="jenis"
                    :options="['izin' => 'Izin', 'sakit' => 'Sakit']"
                />

                <div></div>

                <x-input label="Tanggal Mulai" name="tanggal_mulai" type="date" />
                <x-input label="Tanggal Selesai" name="tanggal_selesai" type="date" />

                <div class="sm:col-span-2">
                    <label for="alasan" class="block text-sm font-medium text-slate-700 mb-1.5">Alasan</label>
                    <textarea
                        name="alasan"
                        id="alasan"
                        rows="4"
                        class="block w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500 {{ $errors->has('alasan') ? 'border-red-300' : '' }}"
                        placeholder="Jelaskan alasan pengajuan izin Anda secara detail (minimal 10 karakter)."
                    >{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="bukti" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Bukti Pendukung <span class="text-slate-400 font-normal">(opsional — jpg, png, atau pdf, maks 2MB)</span>
                    </label>
                    <input
                        type="file"
                        name="bukti"
                        id="bukti"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 {{ $errors->has('bukti') ? 'border border-red-300 rounded-lg' : '' }}"
                    >
                    @error('bukti')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-3">
            <x-button type="submit" variant="primary">Kirim Pengajuan</x-button>
            <a href="{{ route('intern.leave-requests.index') }}">
                <x-button type="button" variant="secondary">Batal</x-button>
            </a>
        </div>
    </form>
</x-app-layout>