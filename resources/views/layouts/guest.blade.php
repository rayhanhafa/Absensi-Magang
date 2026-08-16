<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mantik') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">

    <div class="min-h-screen flex">
        {{-- Left Panel: Branding --}}
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-700 via-primary-600 to-teal-500 relative overflow-hidden flex-col items-center justify-center p-12">
            {{-- Background decoration --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white"></div>
                <div class="absolute top-1/2 -right-24 w-72 h-72 rounded-full bg-white"></div>
                <div class="absolute -bottom-16 left-1/3 w-64 h-64 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 text-center max-w-sm">
                <div class="relative inline-block mb-8">
                    {{-- Soft glow aura --}}
                    <div class="absolute inset-0 bg-white/30 blur-[40px] rounded-full -z-10 scale-125"></div>
                    <img
                        src="{{ asset('storage/images/logo.png') }}"
                        alt="Mantik Logo"
                        class="h-40 w-auto relative z-10 drop-shadow-md"
                    >
                </div>
                <h1 class="text-3xl font-bold text-white mb-3 leading-tight">Mantik</h1>
                <p class="text-primary-100 text-lg font-medium mb-2">Magang Cantik</p>
                <p class="text-primary-200 text-sm leading-relaxed mt-4">
                    Sistem informasi absensi magang modern untuk memudahkan monitoring kehadiran peserta magang.
                </p>
            </div>

            {{-- Decorative dots --}}
            <div class="absolute bottom-8 left-8 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-white/50"></div>
                <div class="w-2 h-2 rounded-full bg-white/30"></div>
                <div class="w-2 h-2 rounded-full bg-white/20"></div>
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="flex-1 flex flex-col items-center justify-center p-6 sm:p-12">
            {{-- Mobile Logo --}}
            <div class="lg:hidden mb-8 text-center">
                <div class="relative inline-block mb-4">
                    {{-- Soft glow aura untuk mobile --}}
                    <div class="absolute inset-0 bg-white/40 blur-2xl rounded-full -z-10 scale-150"></div>
                    <img
                        src="{{ asset('storage/images/logo.png') }}"
                        alt="Mantik Logo"
                        class="h-24 w-auto relative z-10 drop-shadow-sm"
                    >
                </div>
                <h1 class="text-xl font-bold text-slate-800">Mantik</h1>
                <p class="text-slate-500 text-sm">Magang Cantik</p>
            </div>

            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>

            <p class="mt-8 text-xs text-slate-400 text-center">
                © {{ date('Y') }} Mantik — Magang Cantik
            </p>
        </div>
    </div>

</body>
</html>
