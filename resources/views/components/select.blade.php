@props(['label' => null, 'name', 'options' => [], 'selected' => null, 'placeholder' => 'Pilih...'])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full rounded-lg border-slate-300 bg-white shadow-sm text-sm text-slate-800 focus:border-primary-500 focus:ring-primary-500 transition-colors ' . ($errors->has($name) ? 'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-500' : '')]) }}
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected(old($name, $selected) == $optValue)>
                {{ $optLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>