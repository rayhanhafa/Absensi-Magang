<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Mantik' }} — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('storage/images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">

        {{-- Overlay mobile --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-sm lg:hidden"
            style="display: none;"
        ></div>

        <x-sidebar />

        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <x-navbar />

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <x-alert type="success" class="mb-5 animate-slide-in">{{ session('success') }}</x-alert>
                @endif

                @if (session('error'))
                    <x-alert type="danger" class="mb-5 animate-slide-in">{{ session('error') }}</x-alert>
                @endif

                @if (session('warning'))
                    <x-alert type="warning" class="mb-5 animate-slide-in">{{ session('warning') }}</x-alert>
                @endif

                @if ($errors->any())
                    <x-alert type="danger" class="mb-5 animate-slide-in">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="px-6 py-4 border-t border-slate-100">
                <p class="text-xs text-slate-400 text-center">© {{ date('Y') }} Mantik — Magang Cantik. Semua hak dilindungi.</p>
            </footer>
        </div>
    </div>

</body>
</html>