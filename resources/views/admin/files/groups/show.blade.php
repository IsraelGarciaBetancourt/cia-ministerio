@extends('layouts.app')

@section('title', 'Grupo: '.$group->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="mb-4 text-sm text-gray-500">
        <a href="{{ route('files.groups.index') }}" class="hover:underline">Grupos</a>
        <span class="mx-1">/</span>
        <span class="text-gray-800 font-medium">{{ $group->name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $group->name }}</h1>
            @if($group->description)
                <p class="text-gray-600 text-sm mt-1">
                    {{ $group->description }}
                </p>
            @endif
        </div>

        <div class="flex gap-2">
            <a href="{{ route('files.groups.edit', $group) }}"
               class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                ✏️ Editar grupo
            </a>

            <form action="{{ route('files.groups.destroy', $group) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar este grupo y todas sus categorías/archivos?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-2 rounded-lg border border-red-300 text-sm text-red-600 hover:bg-red-50">
                    🗑 Eliminar
                </button>
            </form>
        </div>
    </div>

    {{-- Botón nueva categoría --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Categorías</h2>

        <a href="{{ route('files.groups.categories.create', $group) }}"
           class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            <span class="mr-1">📂</span>
            <span>Nueva categoría</span>
        </a>
    </div>

    {{-- Lista de categorías --}}
    @if($categories->count())
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($categories as $category)
                @php
                    $filesCount = $category->private_files_count
                        ?? optional($category->privateFiles)->count()
                        ?? 0;
                @endphp

                <a href="{{ route('files.groups.categories.show', [$group, $category]) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-2xl">📁</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $filesCount }} {{ $filesCount === 1 ? 'archivo' : 'archivos' }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-6 text-center text-gray-500">
            Este grupo aún no tiene categorías. Crea una para empezar a subir documentos.
        </div>
    @endif
</div>
@endsection
