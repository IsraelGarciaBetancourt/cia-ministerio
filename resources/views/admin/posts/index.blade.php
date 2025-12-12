@extends('layouts.admin')

@section('title-section', 'Gestión de Posts')

@section('content')

    {{-- ========================================================= --}}
    {{-- ALERTAS --}}
    {{-- ========================================================= --}}
    @if(session('success'))
        {{-- Usando componentes de alerta genéricos (asumiendo que existen) --}}
        <x-alert type="success" class="mb-4">
            <x-slot name="title">Éxito</x-slot>
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" class="mb-4">
            <x-slot name="title">Error</x-slot>
            {{ session('error') }}
        </x-alert>
    @endif


    {{-- ========================================================= --}}
    {{-- ESTADÍSTICAS RÁPIDAS --}}
    {{-- ========================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        {{-- Total posts --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Total de Posts</p>
            <p class="text-4xl font-bold mt-2 text-gray-900">{{ $posts->total() }}</p>
        </div>

        {{-- Posts de hoy --}}
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Posts Hoy</p>
            <p class="text-4xl font-bold mt-2 text-gray-900">
                {{ $posts->where('created_at', '>=', now()->startOfDay())->count() }}
            </p>
        </div>

        {{-- Crear un post --}}
        <a href="{{ route('posts.create') }}"
           class="bg-yellow-500 text-gray-900 font-semibold rounded-xl p-6 shadow-md flex items-center justify-center hover:bg-yellow-600 transition">
            <span class="text-xl mr-2">✨</span> Crear Nuevo Post
        </a>

    </div>

    {{-- ========================================================= --}}
    {{-- FORMULARIO DE BÚSQUEDA (LA LUPA) --}}
    {{-- ========================================================= --}}
    <div class="mb-6">
        <form action="{{ route('posts.index') }}" method="GET" class="flex items-center max-w-xl">
            <div class="relative w-full">
                <input type="search" name="search" id="search"
                       placeholder="Buscar posts por título..."
                       value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                
                {{-- Ícono de la Lupa --}}
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            <button type="submit" class="hidden md:block ml-2 px-4 py-2 bg-gray-100 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Buscar
            </button>
            
            {{-- Botón para limpiar la búsqueda --}}
            @if(request('search'))
                <a href="{{ route('posts.index') }}" 
                   class="ml-2 px-4 py-2 bg-red-100 border border-red-300 text-red-700 rounded-lg hover:bg-red-200 transition flex items-center text-sm font-medium">
                    Limpiar
                </a>
            @endif
        </form>
    </div>


    {{-- ========================================================= --}}
    {{-- TABLA DE POSTS (RESPONSIVE LISTA) --}}
    {{-- ========================================================= --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">Todas las Publicaciones @if(request('search')) <span class="font-normal text-gray-500 text-base">({{ $posts->total() }} resultados)</span> @endif</h2>
        </div>

        @if($posts->count() > 0)

            <div class="w-full">
                <table class="w-full text-sm md:table-fixed border-collapse">
                    
                    {{-- Encabezados (Ocultos en móvil) --}}
                    <thead class="bg-gray-100 hidden md:table-header-group">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase w-1/2">Post</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase w-1/6">Fecha</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase w-1/6">Media</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-600 uppercase w-1/6">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach($posts as $post)
                            
                            <tr class="hover:bg-gray-50 transition 
                                       block md:table-row py-4 md:py-0"> 

                                {{-- ---- Columna POST (Título + Imagen) ---- --}}
                                <td class="px-6 pt-4 pb-2 md:py-4 block md:table-cell">
                                    <div class="flex items-start gap-4">

                                        {{-- Cover o fallback --}}
                                        @if($post->cover_image)
                                            <img src="{{ asset('storage/' . $post->cover_image) }}"
                                                 class="w-16 h-16 flex-shrink-0 rounded-lg object-cover" />
                                        @else
                                            <div class="w-16 h-16 flex-shrink-0 rounded-lg bg-yellow-100 flex items-center justify-center">
                                                <span class="text-2xl text-yellow-700">📄</span>
                                            </div>
                                        @endif

                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-gray-900 line-clamp-2">
                                                <span class="md:hidden text-xs text-gray-500 block font-normal mb-1 uppercase">Post:</span>
                                                {{ Str::limit($post->title, 70) }}
                                            </p>
                                            <p class="text-gray-500 text-xs line-clamp-2 mt-1 hidden sm:block">
                                                {{ Str::limit(strip_tags($post->content), 90) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- ---- Columna FECHA ---- --}}
                                <td class="px-6 py-2 md:py-4 block md:table-cell">
                                    <span class="text-gray-900">
                                        <span class="md:hidden text-xs text-gray-500 font-normal mr-2 uppercase">Fecha:</span>
                                        {{ \Carbon\Carbon::parse($post->post_date)->format('d/m/Y') }}
                                    </span>
                                </td>

                                {{-- ---- Columna MEDIA ---- --}}
                                <td class="px-6 py-2 md:py-4 block md:table-cell">
                                    <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        <span class="md:hidden text-gray-500 font-normal mr-2 uppercase">Media:</span>
                                        {{ $post->media->count() }} archivo(s)
                                    </span>
                                </td>

                                {{-- ---- Columna ACCIONES ---- --}}
                                <td class="px-6 pt-2 pb-4 md:py-4 block md:table-cell text-left md:text-right">
                                    <div class="flex items-center justify-start md:justify-end gap-3">

                                        <span class="md:hidden text-xs text-gray-500 font-normal uppercase">Acciones:</span>
                                        
                                        {{-- Ver --}}
                                        <a href="{{ route('blog.show', $post) }}"
                                           class="text-gray-600 hover:text-gray-900 transition p-2 border border-gray-300 rounded-lg text-lg"
                                           title="Ver publicación">
                                            👁️
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('posts.edit', $post) }}"
                                           class="text-blue-600 hover:text-blue-800 transition p-2 border border-gray-300 rounded-lg text-lg"
                                           title="Editar">
                                            ✏️
                                        </a>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('posts.destroy', $post) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Eliminar este post?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-500 hover:text-red-700 transition p-2 border border-gray-300 rounded-lg text-lg"
                                                    title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $posts->links() }}
            </div>

        @else

            <div class="py-16 text-center">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay posts aún</h3>
                <p class="text-gray-500 mb-6">Crea tu primera publicación</p>

                <a href="{{ route('posts.create') }}"
                   class="bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg hover:bg-yellow-600 transition inline-block font-semibold shadow-md">
                    ➕ Crear Post
                </a>
            </div>

        @endif
    </div>

@endsection