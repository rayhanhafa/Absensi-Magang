@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-slate-200 shadow-sm']) }}>
    @if ($title)
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="font-semibold text-slate-800">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-5">
        {{ $slot }}
    </div>
</div>