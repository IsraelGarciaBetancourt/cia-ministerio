@props(['type' => 'info'])

@php
    switch ($type) {
        case 'info':
            // Dorado suave, para mensajes informativos
            $class = 'bg-gold-400/10 border border-gold-400 text-text-primary';
            break;

        case 'danger':
            // Usamos la gama "fire" para errores
            $class = 'bg-fire-600/10 border border-fire-600 text-fire-600';
            break;

        case 'success':
            // Podemos usar verdes de Tailwind para éxito
            $class = 'bg-emerald-50 border border-emerald-500 text-emerald-700';
            break;

        case 'warning':
            $class = 'bg-amber-50 border border-amber-500 text-amber-700';
            break;

        default:
            $class = 'bg-gold-400/10 border border-gold-400 text-text-primary';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-lg flex flex-col sm:flex-row items-start sm:items-center gap-2 ' . $class]) }} role="alert">
    <span class="font-bold text-[15px] inline-block mr-2">
        {{ $title ?? 'Info!' }}
    </span>
    <span class="block text-sm font-medium">
        {{ $slot }}
    </span>
</div>

{{-- Uso:
<x-alert type="info">
    <x-slot name="title">Título Alerta</x-slot>
    Contenido de la alerta
</x-alert>
--}}
