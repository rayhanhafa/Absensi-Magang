<header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20 shadow-sm">

    {{-- Left: Hamburger + Page Title --}}
    <div class="flex items-center gap-3 min-w-0">
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 -ml-1 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors"
            aria-label="Toggle sidebar"
        >
            <x-dynamic-icon name="menu" class="w-5 h-5" />
        </button>

        {{-- Page Title --}}
        @if (isset($title))
            <div class="min-w-0 hidden sm:block">
                <h1 class="text-sm font-semibold text-slate-800 truncate">{{ $title }}</h1>
            </div>
        @else
            <div class="hidden lg:block">
                <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        @endif
    </div>

    {{-- Right: User Dropdown --}}
    <div class="flex items-center gap-3">
        {{-- Date (mobile hidden, show only on small+ when no title) --}}
        <div class="hidden sm:block text-right">
            <p class="text-xs text-slate-500">{{ now()->translatedFormat('d M Y') }}</p>
        </div>

        {{-- User Dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                @click.outside="open = false"
                class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors group"
            >
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-semibold text-slate-700 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 capitalize leading-tight">{{ auth()->user()->getRoleNames()->first() }}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 text-white flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-slate-200/80 py-1.5 z-50 origin-top-right"
                style="display:none;"
            >
                <div class="px-4 py-2.5 border-b border-slate-100 mb-1">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <x-dynamic-icon name="user-circle" class="w-4 h-4 text-slate-400" />
                    Profil Saya
                </a>

                <div class="my-1 border-t border-slate-100"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <x-dynamic-icon name="logout" class="w-4 h-4" />
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>