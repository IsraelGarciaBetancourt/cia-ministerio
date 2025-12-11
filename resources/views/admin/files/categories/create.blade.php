@extends('layouts.app')

@section('title', 'Nueva categoría en '.$group->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 text-sm text-gray-500">
        <a href="{{ route('files.groups.index') }}" class="hover:underline">Grupos</a>
        <span class="mx-1">/</span>
        <a href="{{ route('files.groups.show', $group) }}" class="hover:underline">{{ $group->name }}</a>
        <span class="mx-1">/</span>
        <span>Nueva categoría</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            Nueva categoría en {{ $group->name }}
        </h1>

        <form action="{{ route('files.groups.categories.store', $group) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Nombre de la categoría</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                       required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea name="description" rows="3"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('files.groups.show', $group) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                    Guardar categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
