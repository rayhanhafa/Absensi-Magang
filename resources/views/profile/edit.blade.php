<x-app-layout title="Pengaturan Profil">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengaturan Profil</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
        </div>
    </div>

    <div class="space-y-6 max-w-4xl">
        <x-card class="shadow-sm border-slate-200/80">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-card>

        <x-card class="shadow-sm border-slate-200/80">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-card>

        <x-card class="shadow-sm border-slate-200/80 border-red-200/80">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-card>
    </div>
</x-app-layout>
