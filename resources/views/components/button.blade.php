@props(['variant' => 'primary', 'type' => 'button', 'size' => 'md'])

@php
    $variants = [
        'primary'   => 'bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800 focus:ring-primary-500 shadow-sm shadow-primary-600/20',
        'secondary' => 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 active:bg-slate-100 focus:ring-primary-500',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:ring-red-500 shadow-sm shadow-red-600/20',
        'success'   => 'bg-green-600 text-white hover:bg-green-700 active:bg-green-800 focus:ring-green-500 shadow-sm shadow-green-600/20',
        'ghost'     => 'bg-transparent text-slate-600 hover:bg-slate-100 active:bg-slate-200 focus:ring-slate-400',
    ];

    $sizes = [
        'sm'  => 'px-3 py-1.5 text-xs rounded-lg gap-1.5',
        'md'  => 'px-4 py-2.5 text-sm rounded-lg gap-2',
        'lg'  => 'px-6 py-3 text-base rounded-xl gap-2.5',
        'xl'  => 'px-8 py-4 text-lg rounded-xl gap-3',
    ];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center font-medium transition-all duration-150 active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}
>
    {{ $slot }}
</button>