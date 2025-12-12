{{-- 1. Cambiamos layouts.app por layouts.admin --}}
@extends('layouts.admin')

@section('title', 'Categoría: '.$category->name)

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- Breadcrumb mejorado --}}
    <div class="mb-6 text-sm flex items-center space-x-1">
        <a href="{{ route('files.groups.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            Grupos
        </a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('files.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $group->name }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>

    {{-- Header (Título, Descripción y Botones de Edición/Eliminación) --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-gray-200 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-600 mt-2">{{ $category->description }}</p>
            @endif
        </div>

        {{-- Botones de Acción (Editar/Eliminar) --}}
        <div class="flex gap-3 pt-1">
            <a href="{{ route('files.groups.categories.edit', [$group, $category]) }}"
               class="px-4 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50 font-medium transition flex items-center">
                <span class="mr-1">✏️</span> Editar
            </a>

            <form action="{{ route('files.groups.categories.destroy', [$group, $category]) }}"
                  method="POST"
                  onsubmit="return confirm('⚠️ Advertencia: ¿Eliminar esta categoría y todos sus archivos? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 rounded-lg border border-red-300 text-sm text-red-600 hover:bg-red-50 font-medium transition flex items-center">
                    <span class="mr-1">🗑</span> Eliminar
                </button>
            </form>
        </div>
    </div>

    {{-- Seccion de Archivos (Header y Botón Nuevo Archivo) --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Archivos adjuntos</h2>

        <a href="{{ route('files.groups.categories.files.create', [$group, $category]) }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-md">
            <span class="mr-1">➕</span>
            <span>Nuevo archivo</span>
        </a>
    </div>

    @php
        $files = $files ?? ($category->privateFiles ?? collect());
    @endphp

    @if($files->count())
        {{-- Listado de Archivos --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($files as $file)
                <a href="{{ route('files.groups.categories.files.show', [$group, $category, $file]) }}"
                   class="group block bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        {{-- Icono representativo del archivo --}}
                        <div class="p-3 bg-gray-100 text-gray-600 rounded-lg text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            📄
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition-colors truncate">
                                {{ $file->title }}
                            </h3>
                            @if($file->description)
                                <p class="text-sm text-gray-500 mt-1 mb-2 line-clamp-2">
                                    {{ Str::limit($file->description, 120) }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-400">
                                Subido el {{ optional($file->created_at)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        {{-- Empty State (Estado vacío) --}}
        <div class="flex flex-col items-center justify-center bg-white rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl mb-4">
                📁
            </div>
            <h3 class="text-lg font-medium text-gray-900">Esta categoría está vacía</h3>
            <p class="text-gray-500 mt-1 max-w-sm">
                Aún no has subido ningún archivo a esta categoría.
            </p>
            <a href="{{ route('files.groups.categories.files.create', [$group, $category]) }}" class="mt-6 text-blue-600 font-medium hover:underline flex items-center">
                <span class="mr-1">Subir primer archivo</span> &rarr;
            </a>
        </div>
    @endif
</div>
@endsection