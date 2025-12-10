@props([
    'label' => null,
    'for' => null,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $for }}" class="block text-sm font-medium mb-1">
            {{ $label }}
        </label>
    @endif

    <input 
        id="{{ $for }}"
        {{ $attributes->merge(['class' => 'w-full p-2 border rounded']) }}
    >
</div>
