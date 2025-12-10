@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="max-w-md mx-auto mt-20 p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-4 text-center">Iniciar Sesión</h1>

    @if($errors->any())
        <x-alert type="danger">
            {{ $errors->first() }}
        </x-alert>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 text-sm">Correo:</label>
            <input type="email" name="email" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 text-sm">Contraseña:</label>
            <input type="password" name="password" class="w-full p-2 border rounded" required>
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Entrar
        </button>
    </form>

</div>
@endsection
