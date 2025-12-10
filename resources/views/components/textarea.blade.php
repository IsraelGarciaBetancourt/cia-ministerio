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

    <textarea 
        id="{{ $for }}"
        {{ $attributes->merge(['class' => 'w-full p-2 border rounded h-32']) }}
    >{{ $slot }}</textarea>
</div>

{{-- <x-textarea label="Contenido" for="content" name="content">{{ old('content') }}</x-textarea> --}}