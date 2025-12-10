@extends('layouts.app')

@section('title', 'CIA - INCIO')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <h1>Bienvenido a la página principal</h1>

        <x-alert type="info">
            <x-slot name="title">
                Titulo Alerta
            </x-slot>
            Contenido de la alerta
        </x-alert>
    </div>
@endsection