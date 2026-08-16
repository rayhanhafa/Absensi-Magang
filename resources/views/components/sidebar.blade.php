@php
    $navItems = match (true) {
        auth()->user()->hasRole('admin') => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Peserta Magang', 'route' => 'admin.interns.index', 'icon' => 'users'],
            ['label' => 'Mentor', 'route' => 'admin.mentors.index', 'icon' => 'user-group'],
            ['label' => 'Periode Magang', 'route' => 'admin.periods.index', 'icon' => 'calendar'],
            ['label' => 'Jadwal Kerja', 'route' => 'admin.schedules.index', 'icon' => 'clock'],
            ['label' => 'Lokasi Absensi', 'route' => 'admin.office-settings.index', 'icon' => 'map-pin'],
            ['label' => 'Absensi', 'route' => 'admin.attendances.index', 'icon' => 'check-circle'],
            ['label' => 'Laporan', 'route' => 'admin.reports.index', 'icon' => 'document'],
        ],
        auth()->user()->hasRole('mentor') => [
            ['label' => 'Dashboard', 'route' => 'mentor.dashboard', 'icon' => 'home'],
            ['label' => 'Peserta Bimbingan', 'route' => 'mentor.interns.index', 'icon' => 'users'],
            ['label' => 'Absensi', 'route' => 'mentor.attendances.index', 'icon' => 'check-circle'],
            ['label' => 'Pengajuan Izin', 'route' => 'mentor.leave-requests.index', 'icon' => 'document-text'],
        ],
        auth()->user()->hasRole('intern') => [
            ['label' => 'Dashboard', 'route' => 'intern.dashboard', 'icon' => 'home'],
            ['label' => 'Riwayat Absensi', 'route' => 'intern.attendance.history', 'icon' => 'clock'],
            ['label' => 'Pengajuan Izin', 'route' => 'intern.leave-requests.index', 'icon' => 'document-text'],
        ],
        default => [],
    };
@endphp

<aside
    x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="-translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="-translate-x-full opacity-0"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-slate-300 flex flex-col lg:translate-x-0 shadow-xl"
    style="display: none;"
>
    {{-- Logo Area --}}
    <div class="h-16 flex items-center px-5 border-b border-slate-700/50 flex-shrink-0">
        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : (auth()->user()->hasRole('mentor') ? route('mentor.dashboard') : route('intern.dashboard')) }}" class="flex items-center gap-3 min-w-0">
            <img
                src="{{ asset('storage/images/logo.png') }}"
                alt="Mantik Logo"
                class="h-9 w-auto flex-shrink-0 object-contain"
            >
            <div class="min-w-0">
                <p class="text-white font-bold text-sm leading-tight tracking-wide truncate">Mantik</p>
                <p class="text-slate-400 text-xs leading-tight truncate">Magang Cantik</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">
        @foreach ($navItems as $item)
            @php
                $routeParts = explode('.', $item['route']);
                $isActive = request()->routeIs($routeParts[0] . '.' . $routeParts[1] . '*');
            @endphp
            <a
                href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group relative
                    {{ $isActive
                        ? 'bg-primary-600 text-white shadow-sm shadow-primary-900/30'
                        : 'text-slate-400 hover:bg-slate-700/60 hover:text-white' }}"
            >
                {{-- Active indicator --}}
                @if ($isActive)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-primary-300 rounded-r-full"></span>
                @endif
                <x-dynamic-icon :name="$item['icon']" class="w-5 h-5 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Bottom: User + Logout --}}
    <div class="p-3 border-t border-slate-700/50 flex-shrink-0 space-y-1">
        {{-- Profile link --}}
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-700/60 hover:text-white transition-all duration-150 group">
            <div class="w-7 h-7 rounded-full bg-primary-600/80 text-white flex items-center justify-center font-semibold text-xs flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-slate-300 text-xs font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-slate-500 text-xs capitalize truncate">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
        </a>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-red-900/30 hover:text-red-400 transition-all duration-150">
                <x-dynamic-icon name="logout" class="w-5 h-5 flex-shrink-0" />
                Keluar
            </button>
        </form>
    </div>
</aside>