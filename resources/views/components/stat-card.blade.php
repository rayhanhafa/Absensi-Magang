@props(['label', 'value', 'color' => 'primary', 'icon' => null, 'suffix' => null])

@php
    $colorMap = [
        'primary' => ['bg-primary-50 text-primary-600', 'text-primary-600', 'bg-primary-600'],
        'indigo'  => ['bg-indigo-50 text-indigo-600', 'text-indigo-600', 'bg-indigo-600'],
        'green'   => ['bg-green-50 text-green-600', 'text-green-600', 'bg-green-600'],
        'amber'   => ['bg-amber-50 text-amber-600', 'text-amber-600', 'bg-amber-600'],
        'red'     => ['bg-red-50 text-red-600', 'text-red-600', 'bg-red-600'],
        'blue'    => ['bg-blue-50 text-blue-600', 'text-blue-600', 'bg-blue-600'],
        'purple'  => ['bg-purple-50 text-purple-600', 'text-purple-600', 'bg-purple-600'],
        'slate'   => ['bg-slate-100 text-slate-600', 'text-slate-600', 'bg-slate-600'],
    ];

    [$iconBg, $valueColor, $barColor] = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="bg-white rounded-xl border border-slate-200/80 shadow-card p-5 hover:shadow-card-hover transition-shadow duration-200">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $valueColor }} leading-none">
                {{ $value }}
                @if($suffix)
                    <span class="text-sm font-normal text-slate-400 ml-1">{{ $suffix }}</span>
                @endif
            </p>
        </div>
        @if ($icon)
            <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $iconBg }} flex items-center justify-center">
                <x-dynamic-icon :name="$icon" class="w-5 h-5" />
            </div>
        @endif
    </div>
</div>