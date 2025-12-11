@extends('layouts.app')

@section('title', 'Grupos de archivos privados')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Archivos privados</h1>
            <p class="text-gray-600 text-sm mt-1">
                Organiza los documentos en grupos y categorías, igual que las publicaciones del blog.
            </p>
        </div>

        <a href="{{ route('files.groups.create') }}"
           class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
            <span class="mr-2">📁</span>
            <span>Nuevo grupo</span>
        </a>
    </div>

    {{-- Lista de grupos --}}
    @if($groups->count())
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($groups as $group)
                @php
                    $categoriesCount = $group->categories_count
                        ?? optional($group->categories)->count()
                        ?? 0;
                @endphp

                <a href="{{ route('files.groups.show', $group) }}"
                   class="block bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 text-2xl">📂</div>
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $group->name }}</h2>
                            @if($group->description)
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $group->description }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                {{ $categoriesCount }} {{ $categoriesCount === 1 ? 'categoría' : 'categorías' }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
            Aún no hay grupos creados. Crea el primero para comenzar a organizar los documentos.
        </div>
    @endif
</div>
@endsection
