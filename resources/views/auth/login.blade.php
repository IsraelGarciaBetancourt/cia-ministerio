@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')

<div class="flex justify-center pt-20">
    <div class="w-full max-w-md">  <!-- Puedes cambiar a max-w-xl si quieres más ancho -->

        <h1 class="text-2xl font-bold text-dark mb-6 text-center">
            Iniciar Sesión
        </h1>

        <div class="bg-white rounded-xl shadow p-6">
            @if($errors->any())
                <div class="mb-5">
                    <x-alert type="danger">
                        {{ $errors->first() }}
                    </x-alert>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Correo:</label>
                    <input type="email" name="email" required
                           class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Contraseña:</label>
                    <input type="password" name="password" required
                           class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit"
                            class="bg-primary-300 text-dark px-6 py-2 rounded-xl shadow hover:brightness-95">
                        Entrar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection