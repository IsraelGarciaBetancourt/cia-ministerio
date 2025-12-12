@props(['label' => null])

<div class="flex flex-col gap-1">
    @if ($label)
        <span class="text-sm font-medium text-text-primary">{{ $label }}</span>
    @endif

    <div class="relative">
        <input
            type="date"
            {{ $attributes->merge([
                'class' => '
                    w-full bg-light border border-border rounded-lg px-4 py-2
                    text-text-primary focus:border-primary-200 focus:ring-primary-200
                '
            ]) }}
        >
        <span class="absolute right-3 top-2.5 text-text-muted">📅</span>
    </div>
</div>
