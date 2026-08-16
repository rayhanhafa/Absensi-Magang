<x-app-layout title="Ajukan Izin/Sakit">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ajukan Izin/Sakit</h1>
            <p class="text-sm text-slate-500 mt-1">Formulir pengajuan izin atau pemberitahuan sakit.</p>
        </div>
        <div>
            <a href="{{ route('intern.leave-requests.index') }}">
                <x-button type="button" variant="secondary" size="sm" class="w-full sm:w-auto">
                    <x-dynamic-icon name="arrow-left" class="w-4 h-4 mr-1.5" />
                    Kembali
                </x-button>
            </a>
        </div>
    </div>

    <div class="max-w-2xl">
        <x-card class="shadow-sm border-slate-200/80">
            <form action="{{ route('intern.leave-requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <x-input type="date" name="tanggal_mulai" label="Tanggal Mulai" :value="old('tanggal_mulai')" required />
                    </div>
                    <div>
                        <x-input type="date" name="tanggal_selesai" label="Tanggal Selesai" :value="old('tanggal_selesai')" required />
                    </div>
                </div>

                <div>
                    <x-select name="jenis" label="Jenis Pengajuan" :options="['izin' => 'Izin', 'sakit' => 'Sakit']" :selected="old('jenis')" required />
                </div>

                <div>
                    <label for="alasan" class="block text-sm font-medium text-slate-700 mb-1.5">Alasan/Keterangan</label>
                    <textarea name="alasan" id="alasan" rows="3" required class="block w-full rounded-lg border-slate-300 bg-white shadow-sm text-sm text-slate-800 placeholder:text-slate-400 focus:border-primary-500 focus:ring-primary-500 transition-colors {{ $errors->has('alasan') ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-500' : '' }}" placeholder="Tuliskan alasan pengajuan Anda secara jelas...">{{ old('alasan') }}</textarea>
                    @error('alasan')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <x-dynamic-icon name="x-circle" class="w-3.5 h-3.5" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="bukti_file" class="block text-sm font-medium text-slate-700 mb-1.5">Bukti (Surat Dokter/Dokumen Pendukung)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-primary-400 hover:bg-slate-50 transition-colors group">
                        <div class="space-y-2 text-center">
                            <x-dynamic-icon name="document" class="mx-auto h-10 w-10 text-slate-400 group-hover:text-primary-500 transition-colors" />
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="bukti_file" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input id="bukti_file" name="bukti_file" type="file" class="sr-only">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-slate-500">PDF, JPG, JPEG, PNG maksimal 2MB</p>
                        </div>
                    </div>
                    @error('bukti_file')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <x-dynamic-icon name="x-circle" class="w-3.5 h-3.5" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <x-button type="submit" variant="primary" class="w-full sm:w-auto">
                        <x-dynamic-icon name="check-circle" class="w-4 h-4 mr-1.5" />
                        Kirim Pengajuan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>