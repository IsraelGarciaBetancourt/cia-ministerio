@props(['disabled' => false])

<input 
    @disabled($disabled) 

    {{ $attributes->merge([
        'class' => '
            border-gold-500 
            bg-surface
            text-text-primary
            focus:border-gold-400 
            focus:ring-gold-400 
            rounded-md 
            shadow-sm
            transition
        '
    ]) }}
>
