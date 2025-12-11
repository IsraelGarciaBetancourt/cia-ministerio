@extends('layouts.app')

@section('title', 'Categoría: '.$category->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="mb-4 text-sm text-gray-500">
        <a href="{{ route('files.groups.index') }}" class="hover:underline">Grupos</a>
        <span class="mx-1">/</span>
        <a href="{{ route('files.groups.show', $group) }}" class="hover:underline">{{ $group->name }}</a>
        <span class="mx-1">/</span>
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-gray-600 text-sm mt-1">{{ $category->description }}</p>
            @endif
        </div>

        <div class="flex gap-2">
            <a href="{{ route('files.groups.categories.edit', [$group, $category]) }}"
               class="px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                ✏️ Editar categoría
            </a>

            <form action="{{ route('files.groups.categories.destroy', [$group, $category]) }}"
                  method="POST"
                  onsubmit="return confirm('¿Eliminar esta categoría y todos sus archivos?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-2 rounded-lg border border-red-300 text-sm text-red-600 hover:bg-red-50">
                    🗑 Eliminar
                </button>
            </form>
        </div>
    </div>

    {{-- Botón nuevo archivo --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Archivos</h2>

        <a href="{{ route('files.groups.categories.files.create', [$group, $category]) }}"
           class="inline-flex items-center px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
            <span class="mr-1">📄</span>
            <span>Nuevo archivo</span>
        </a>
    </div>

    @php
        $files = $files ?? ($category->privateFiles ?? collect());
    @endphp

    @if($files->count())
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($files as $file)
                <a href="{{ route('files.groups.categories.files.show', [$group, $category, $file]) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $file->title }}</h3>
                    @if($file->description)
                        <p class="text-sm text-gray-600 mb-2">
                            {{ Str::limit($file->description, 120) }}
                        </p>
                    @endif
                    <p class="text-xs text-gray-500">
                        Subido el {{ optional($file->created_at)->format('d/m/Y') }}
                    </p>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-6 text-center text-gray-500">
            No hay archivos en esta categoría todavía.
        </div>
    @endif
</div>
@endsection
