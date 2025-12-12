@props(['icon' => null])

<div class="relative">
    @if ($icon)
        <span class="absolute left-3 top-2.5 text-text-muted">{{ $icon }}</span>
    @endif

    <input
        {{ $attributes->merge([
            'class' =>
            'w-full pl-10 pr-4 py-2 rounded-md bg-light-200 text-text-primary
            border border-light-200 focus:border-primary-200 focus:ring-primary-200'
        ]) }}
    >
</div>
