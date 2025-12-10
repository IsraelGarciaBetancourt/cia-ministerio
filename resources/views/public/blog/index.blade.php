@extends('layouts.app')

@section('title', 'Blog - CIA Ministerio')

@section('content')
{{-- Hero Section --}}
<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-6xl mx-auto px-6 py-16">
        <h1 class="text-5xl font-bold mb-4">📰 Eventos y Noticias</h1>
        <p class="text-xl text-blue-100">Mantente informado con las últimas actualizaciones de nuestra comunidad</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 py-8">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Contador --}}
    <div class="mb-8 flex items-center justify-between">
        <p class="text-gray-600">
            <span class="font-bold text-2xl text-gray-800">{{ $posts->total() }}</span> 
            <span class="text-lg">{{ $posts->total() == 1 ? 'publicación' : 'publicaciones' }}</span>
        </p>
        
        @auth
            <a href="{{ route('posts.create') }}" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition inline-flex items-center gap-2">
                <span>➕</span>
                <span>Crear Post</span>
            </a>
        @endauth
    </div>

    {{-- Grid de posts --}}
    @if($posts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($posts as $post)
                <article class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <a href="{{ route('blog.show', $post) }}" class="block">
                        
                        {{-- Imagen de portada --}}
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($post->cover_image)
                                <img src="{{ asset('storage/' . $post->cover_image) }}" 
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-500">
                                    <span class="text-white text-6xl">📄</span>
                                </div>
                            @endif
                            
                            {{-- Badge de fecha --}}
                            <div class="absolute top-3 right-3 bg-white rounded-lg px-3 py-1 shadow-md">
                                <p class="text-xs font-semibold text-gray-700">
                                    {{ \Carbon\Carbon::parse($post->post_date)->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Contenido --}}
                        <div class="p-5">
                            <h2 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition line-clamp-2">
                                {{ $post->title }}
                            </h2>
                            
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ Str::limit(strip_tags($post->content), 120) }}
                            </p>

                            {{-- Footer del card --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-blue-600 text-sm font-semibold group-hover:underline">
                                    Leer más →
                                </span>
                                
                                @if($post->media->count() > 0)
                                    <div class="flex items-center gap-1 text-gray-500 text-xs">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $post->media->count() }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        {{-- Sin posts --}}
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">No hay publicaciones aún</h3>
            <p class="text-gray-500 mb-6">Sé el primero en crear una publicación</p>
            
            @auth
                <a href="{{ route('posts.create') }}" 
                   class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                    ➕ Crear mi primera publicación
                </a>
            @endauth
        </div>
    @endif

</div>
@endsection