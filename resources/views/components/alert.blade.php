@props(['type' => 'info'])

@php
    switch ($type) {
        case 'info':
            $class = 'bg-blue-100 text-blue-800';
            break;

        case 'danger':
            $class = 'bg-red-100 text-red-800';
            break;

        case 'succes':
            $class = 'bg-green-100 text-green-800';
            break;

        case 'warning':
            $class = 'bg-yellow-100 text-yellow-800';
            break;

        default:
            $class = 'bg-blue-100 text-blue-800';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => 'p-4 rounded-lg ' . $class]) }} role="alert">
    <span class="font-bold text-[15px] inline-block mr-4">{{$title ?? 'Info!'}}</span>
    <span class="block text-sm font-medium sm:inline max-sm:mt-2">{{$slot}}</span>
</div>

{{--         <x-alert type="info">
            <x-slot name="title">
                Titulo Alerta
            </x-slot>
            Contenido de la alerta
        </x-alert> --}}