@extends('layouts.admin')

@section('title', 'Grupos de archivos privados')

@section('content')

    <div class="max-w-7xl mx-auto"> 

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Archivos privados</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Organiza los documentos en grupos y categorías.
                </p>
            </div>

            <a href="{{ route('files.groups.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                <span class="mr-2">📁</span>
                <span>Nuevo grupo</span>
            </a>
        </div>

        {{-- Lista de grupos --}}
        @if($groups->count())

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($groups as $group)
                    @php
                        $categoriesCount = $group->categories_count
                            ?? optional($group->categories)->count()
                            ?? 0;
                    @endphp

                    <a href="{{ route('files.groups.show', $group) }}"
                       class="group block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md hover:border-blue-300 hover:-translate-y-1 transition-all duration-200">
                        <div class="flex items-start gap-4">
                            {{-- Icono con fondo suave --}}
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                📂
                            </div>
                            
                            <div class="flex-1">
                                <h2 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition-colors">
                                    {{ $group->name }}
                                </h2>
                                
                                @if($group->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                        {{ $group->description }}
                                    </p>
                                @endif
                                
                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center text-xs font-medium text-gray-400">
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                        {{ $categoriesCount }} {{ $categoriesCount === 1 ? 'categoría' : 'categorías' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- Empty State (Estado vacío) --}}
            <div class="flex flex-col items-center justify-center bg-white rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-3xl mb-4">
                    📂
                </div>
                <h3 class="text-lg font-medium text-gray-900">No hay grupos creados</h3>
                <p class="text-gray-500 mt-1 max-w-sm">
                    Crea el primer grupo para comenzar a organizar tus archivos y documentos privados.
                </p>
                <a href="{{ route('files.groups.create') }}" class="mt-6 text-blue-600 font-medium hover:underline">
                    Crear primer grupo &rarr;
                </a>
            </div>
        @endif
    </div>
@endsection