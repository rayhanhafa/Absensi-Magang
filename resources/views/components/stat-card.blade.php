@props(['label', 'value', 'color' => 'indigo'])

@php
    $colors = [
        'indigo' => 'text-indigo-600 bg-indigo-50',
        'green' => 'text-green-600 bg-green-50',
        'amber' => 'text-amber-600 bg-amber-50',
        'red' => 'text-red-600 bg-red-50',
        'blue' => 'text-blue-600 bg-blue-50',
        'purple' => 'text-purple-600 bg-purple-50',
    ];
@endphp

<div class="bg-white rounded-xl border border-slate-200 p-5">
    <p class="text-sm text-slate-500 mb-1">{{ $label }}</p>
    <p class="text-2xl font-semibold {{ explode(' ', $colors[$color] ?? $colors['indigo'])[0] }}">{{ $value }}</p>
</div>