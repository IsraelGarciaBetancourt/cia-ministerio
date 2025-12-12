@props(['variant' => 'primary'])

@php
$base = "px-4 py-2 rounded-md font-medium transition";

$variants = [
    'primary' => "bg-primary-200 text-dark hover:bg-primary-300",
    'light'   => "bg-light-200 text-text-primary hover:bg-light-100",
    'dark'    => "bg-dark text-light hover:bg-dark-100",
];

$classes = $variants[$variant] ?? $variants['primary'];
@endphp

<button {{ $attributes->class("$base $classes") }}>
    {{ $slot }}
</button>



{{-- <x-button>Guardar</x-button> --}}
