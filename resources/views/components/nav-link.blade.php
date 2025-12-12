@props(['active' => false])

@php
$classes = $active
    ? 'text-primary-200 font-semibold'
    : 'text-light hover:text-primary-200';
@endphp

<a {{ $attributes->class([
        'transition px-3 py-2 text-sm',
        $classes
    ]) }}>
    {{ $slot }}
</a>
