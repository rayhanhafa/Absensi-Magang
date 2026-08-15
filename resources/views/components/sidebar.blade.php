@php
    $navItems = match (true) {
        auth()->user()->hasRole('admin') => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
            ['label' => 'Peserta Magang', 'route' => 'admin.interns.index', 'icon' => 'users'],
            ['label' => 'Mentor', 'route' => 'admin.mentors.index', 'icon' => 'user-group'],
            ['label' => 'Periode Magang', 'route' => 'admin.periods.index', 'icon' => 'calendar'],
            ['label' => 'Jadwal Kerja', 'route' => 'admin.schedules.index', 'icon' => 'clock'],
            ['label' => 'Lokasi Absensi', 'route' => 'admin.office-settings.index', 'icon' => 'calendar'],
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
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex flex-col lg:translate-x-0"
    style="display: none;"
>
    <div class="h-16 flex items-center px-6 border-b border-slate-800">
        <span class="text-white font-semibold text-lg tracking-tight">Absensi Magang</span>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @foreach ($navItems as $item)
            <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs(explode('.', $item['route'])[0] . '.' . explode('.', $item['route'])[1] . '*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <x-dynamic-icon :name="$item['icon']" class="w-5 h-5 flex-shrink-0" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="p-3 border-t border-slate-800">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition">
                <x-dynamic-icon name="logout" class="w-5 h-5 flex-shrink-0" />
                Keluar
            </button>
        </form>
    </div>
</aside>