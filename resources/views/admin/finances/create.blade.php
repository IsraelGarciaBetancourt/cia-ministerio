@extends('layouts.admin')

@section('title', 'Crear Finanza')

@section('content')

<div class="flex justify-center">
    <div class="w-full max-w-xl">

        <h1 class="text-2xl font-bold text-dark mb-6">
            Crear Finanza
        </h1>

        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ route('finances.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1">Título</label>
                    <input type="text" name="title" required
                           class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Fecha</label>
                    <input type="date" name="date" required
                           class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Descripción (opcional)</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('finances.index') }}"
                       class="px-5 py-2 rounded-xl border text-gray-600">
                        Cancelar
                    </a>

                    <button
                        class="bg-primary-300 text-dark px-6 py-2 rounded-xl shadow hover:brightness-95">
                        Crear
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
