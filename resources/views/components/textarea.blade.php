@props([
    'label' => null,
    'for' => null,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $for }}" class="block text-sm font-medium mb-1 text-text-primary">
            {{ $label }}
        </label>
    @endif

    <textarea 
        id="{{ $for }}"
        {{ $attributes->merge([
            'class' => '
                w-full p-2 
                h-32
                bg-surface
                text-text-primary
                border border-gold-500 
                rounded-md 
                focus:border-gold-400 
                focus:ring-gold-400
                focus:outline-none
                transition
            '
        ]) }}
    >{{ $slot }}</textarea>
</div>


{{-- <x-textarea label="Contenido" for="content" name="content">{{ old('content') }}</x-textarea> --}}