<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20">
    <button
        @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100"
    >
        <x-dynamic-icon name="menu" class="w-6 h-6" />
    </button>

    <div class="hidden lg:block">
        <p class="text-sm text-slate-500">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-medium text-slate-800">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500 capitalize">{{ auth()->user()->getRoleNames()->first() }}</p>
        </div>

        <a href="{{ route('profile.edit') }}" class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-semibold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </a>
    </div>
</header>