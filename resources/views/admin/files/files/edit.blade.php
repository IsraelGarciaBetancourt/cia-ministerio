@extends('layouts.admin')

@section('title', 'Editar archivo: '.$file->title)

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
        <a href="{{ route('files.groups.categories.files.show', [$group, $category, $file]) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $file->title }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">Editar</span>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            Editar archivo: <span class="text-blue-600">{{ $file->title }}</span>
        </h1>

        <form action="{{ route('files.groups.categories.files.update', [$group, $category, $file]) }}"
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- Campo: Título --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" id="title" name="title" 
                       value="{{ old('title', $file->title) }}" required
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo: Descripción --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción (opcional)</label>
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $file->description) }}</textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sección: Adjuntos actuales --}}
            <div class="pt-4 border-t border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 mb-3">Adjuntos actuales (Marcar para eliminar)</h2>

                @if($file->attachments->isEmpty())
                    <p class="text-sm text-gray-500 bg-gray-50 p-3 rounded-lg border border-dashed text-center">
                        No hay archivos adjuntos en este documento.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($file->attachments as $att)
                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 p-3 rounded-lg transition hover:bg-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl text-blue-500">📎</span>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $att->original_filename }}</p>
                                        <p class="text-xs text-gray-500">{{ $att->mime_type }} | {{ number_format($att->size / 1024, 2) }} KB</p>
                                    </div>
                                </div>

                                {{-- CHECKBOX PARA ELIMINAR --}}
                                <label class="text-red-600 cursor-pointer text-sm font-medium flex items-center gap-1 bg-red-50 p-2 rounded-lg border border-red-200 hover:bg-red-100 transition">
                                    <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}" class="w-4 h-4 text-red-600 bg-white border-gray-300 rounded focus:ring-red-500">
                                    Eliminar
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sección: Agregar nuevos adjuntos --}}
            <div class="pt-4 border-t border-gray-100">
                <label for="attachments" class="block text-sm font-medium text-gray-700">Agregar nuevos adjuntos (opcional)</label>
                {{-- Mismo estilo avanzado para input file que en la vista 'create' --}}
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
                <a href="{{ route('files.groups.categories.files.show', [$group, $category, $file]) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

@endsection