@props(['title' => null, 'subtitle' => null, 'noPadding' => false])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200/80 shadow-card overflow-hidden']) }}>
    @if ($title)
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-800 text-sm">{{ $title }}</h3>
                @if ($subtitle)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($action)
                <div class="flex-shrink-0">{{ $action }}</div>
            @endisset
        </div>
    @endif
    <div class="{{ $noPadding ? '' : 'p-5' }}">
        {{ $slot }}
    </div>
</div>