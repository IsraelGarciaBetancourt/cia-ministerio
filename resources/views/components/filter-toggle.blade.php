@props(['on' => false])

<div 
    {{ $attributes->merge(['class' => 'w-12 h-6 rounded-full cursor-pointer transition relative']) }}
    x-data="{ on: @js($on) }"
    @click="on = !on"
>
    <div 
        class="absolute inset-0 rounded-full transition"
        :class="on ? 'bg-dark' : 'bg-light-200 border border-border'"
    ></div>

    <div 
        class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition"
        :class="on ? 'translate-x-6' : ''"
    ></div>
</div>
