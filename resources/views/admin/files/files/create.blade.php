@extends('layouts.admin')

@section('title', 'Nuevo archivo en '.$category->name)

@section('content')
<div class="max-w-4xl mx-auto">
    
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
        <a href="{{ route('files.groups.categories.show', [$group, $category]) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $category->name }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">Nuevo archivo</span>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            Nuevo Archivo en <span class="text-blue-600">{{ $category->name }}</span>
        </h1>

        <form action="{{ route('files.groups.categories.files.store', [$group, $category]) }}"
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6"> {{-- Se cambió a space-y-6 para consistencia --}}

            @csrf

            {{-- Campo: Título --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" id="title" name="title" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                       value="{{ old('title') }}">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo: Descripción --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo: Archivos adjuntos --}}
            <div>
                <label for="attachments" class="block text-sm font-medium text-gray-700">Archivos adjuntos</label>
                {{-- Nota: El estilo del input file de Tailwind es limitado, se añaden clases base --}}
                <input type="file" id="attachments" name="attachments[]" multiple
                       class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                       >
                @error('attachments')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('attachments.*')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('files.groups.categories.show', [$group, $category]) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md">
                    Crear Archivo
                </button>
            </div>
        </form>
    </div>
</div>

@endsection