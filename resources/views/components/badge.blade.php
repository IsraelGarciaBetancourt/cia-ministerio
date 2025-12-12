@props([
    'color' => 'gold', // gold, fire, dark
])

@php
    $colors = [
        'gold' => 'bg-gold-400 text-dark',
        'fire' => 'bg-fire-500 text-light',
        'dark' => 'bg-dark text-gold-400',
    ];
@endphp

<span {{ $attributes->merge([
    'class' => "px-3 py-1 rounded-full text-xs font-semibold shadow " . ($colors[$color] ?? $colors['gold'])
]) }}>
    {{ $slot }}
</span>
