@extends('layouts.admin')

@section('title', 'Nueva categoría en '.$group->name)

@section('content')
<div class="max-w-3xl mx-auto">
    
    {{-- Breadcrumb consistente --}}
    <div class="mb-6 text-sm flex items-center space-x-1 text-gray-500">
        <a href="{{ route('files.groups.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            Grupos
        </a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('files.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $group->name }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">Nueva categoría</span>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            Nueva categoría en <span class="text-blue-600">{{ $group->name }}</span>
        </h1>

        <form action="{{ route('files.groups.categories.store', $group) }}" method="POST" class="space-y-6">
            @csrf

            {{-- Campo: Nombre de la categoría --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la categoría</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo: Descripción --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea id="description" name="description" rows="3"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('files.groups.show', $group) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md">
                    Guardar categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection