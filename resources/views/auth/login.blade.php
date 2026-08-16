<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800">Masuk ke Akun Anda</h2>
        <p class="text-sm text-slate-500 mt-2">Silakan masukkan email dan password untuk melanjutkan.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input label="Email" id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="contoh@email.com" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>
            <x-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 group-hover:border-primary-500 transition-colors cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-slate-600 group-hover:text-slate-800 transition-colors">Ingat saya</span>
            </label>
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full">
                Masuk
            </x-button>
        </div>
    </form>
</x-guest-layout>
