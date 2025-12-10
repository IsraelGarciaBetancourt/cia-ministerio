@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
{{-- Header del panel --}}
<div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">⚙️ Panel de Administración</h1>
                <p class="text-blue-100">Gestiona tus publicaciones</p>
            </div>
            <a href="{{ route('blog.index') }}" 
               class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition shadow-lg inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Ver Blog Público</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
            <p class="font-medium">✓ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
            <p class="font-medium">✗ {{ session('error') }}</p>
        </div>
    @endif

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $posts->total() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Posts Hoy</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">
                        {{ $posts->where('created_at', '>=', now()->startOfDay())->count() }}
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <a href="{{ route('posts.create') }}" class="block h-full">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="bg-indigo-100 rounded-full p-3 inline-block mb-2">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <p class="text-indigo-600 font-semibold">Crear Nuevo Post</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Tabla de posts --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Todas las Publicaciones</h2>
        </div>

        @if($posts->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Post
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Media
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($post->cover_image)
                                            <img src="{{ asset('storage/' . $post->cover_image) }}" 
                                                 alt="{{ $post->title }}"
                                                 class="w-16 h-16 rounded-lg object-cover mr-4">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center mr-4">
                                                <span class="text-white text-2xl">📄</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 50) }}</p>
                                            <p class="text-sm text-gray-500">{{ Str::limit($post->content, 60) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($post->post_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $post->media->count() }} archivos
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('blog.show', $post) }}" 
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Ver">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('posts.edit', $post) }}" 
                                           class="text-indigo-600 hover:text-indigo-900"
                                           title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('posts.destroy', $post) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Eliminar este post?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay posts aún</h3>
                <p class="text-gray-500 mb-4">Crea tu primera publicación</p>
                <a href="{{ route('posts.create') }}" 
                   class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    ➕ Crear Post
                </a>
            </div>
        @endif
    </div>

</div>
@endsection