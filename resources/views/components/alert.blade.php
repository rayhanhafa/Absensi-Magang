@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'bg-emerald-50 text-emerald-800 border-l-4 border-emerald-500',
        'warning' => 'bg-amber-50 text-amber-800 border-l-4 border-amber-500',
        'danger'  => 'bg-red-50 text-red-800 border-l-4 border-red-500',
        'info'    => 'bg-blue-50 text-blue-800 border-l-4 border-blue-500',
    ];

    $icons = [
        'success' => 'check-circle',
        'warning' => 'bell',
        'danger'  => 'x-circle',
        'info'    => 'information-circle',
    ];

    $iconColors = [
        'success' => 'text-emerald-500',
        'warning' => 'text-amber-500',
        'danger'  => 'text-red-500',
        'info'    => 'text-blue-500',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 px-4 py-3.5 rounded-lg text-sm ' . ($styles[$type] ?? $styles['info'])]) }}>
    <x-dynamic-icon
        :name="$icons[$type] ?? 'information-circle'"
        class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $iconColors[$type] ?? 'text-blue-500' }}"
    />
    <div class="flex-1 leading-relaxed">{{ $slot }}</div>
</div>