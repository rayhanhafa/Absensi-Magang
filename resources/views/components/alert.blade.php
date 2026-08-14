@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'bg-green-50 text-green-800 border-green-200',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
        'danger' => 'bg-red-50 text-red-800 border-red-200',
        'info' => 'bg-blue-50 text-blue-800 border-blue-200',
    ];

    $icons = [
        'success' => 'check-circle',
        'warning' => 'bell',
        'danger' => 'check-circle',
        'info' => 'document',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 px-4 py-3 rounded-lg border text-sm ' . ($styles[$type] ?? $styles['info'])]) }}>
    <x-dynamic-icon :name="$icons[$type] ?? 'document'" class="w-5 h-5 flex-shrink-0 mt-0.5" />
    <div class="flex-1">{{ $slot }}</div>
</div>