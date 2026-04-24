@props(['class' => ''])

@php
    $company = $setting->company ?? config('app.name');
    $logoFile = $setting->logo ?? null;
@endphp

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center group focus:outline-none focus:ring-2 focus:ring-indigo-400/50 rounded-xl p-2 -m-2 ' . $class]) }}>
    @if ($logoFile)
        <img
            src="{{ asset('storage/images/' . ltrim($logoFile, '/')) }}"
            alt="{{ $company }}"
            class="h-20 md:h-24 w-auto max-w-[220px] object-contain drop-shadow-[0_8px_24px_rgba(0,0,0,0.35)] transition duration-300 ease-out group-hover:scale-[1.03] group-hover:drop-shadow-[0_12px_28px_rgba(99,102,241,0.25)]"
        />
    @else
        <span class="text-center text-2xl md:text-3xl font-semibold text-white tracking-tight drop-shadow-md">
            {{ $company }}
        </span>
    @endif
</a>
