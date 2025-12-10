@extends('layouts.app')

@section('title', 'Editar Post')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Editar Post</h1>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif

    @if ($errors->any())
        <x-alert type="danger">
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    {{-- EDITAR POST --}}
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <x-input label="Título" for="title" name="title" type="text" value="{{ $post->title }}" required />

        <x-textarea label="Contenido" for="content" name="content">{{ $post->content }}</x-textarea>

        <x-input label="Fecha del Post" for="post_date" name="post_date" type="date" value="{{ $post->post_date }}" />

        <div class="mb-4">
            <label class="block mb-2 font-semibold">Portada Actual:</label>
            @if($post->cover_image)
                <img src="{{ asset('storage/' . $post->cover_image) }}" class="w-60 rounded shadow">
            @else
                <p class="text-gray-500">No hay portada.</p>
            @endif
        </div>

        <x-input label="Cambiar Portada" for="cover" name="cover" type="file" accept="image/*" />

        <x-button type="submit">Actualizar Post</x-button>
    </form>

    <hr class="my-8">

    {{-- AGREGAR MÚLTIPLES IMÁGENES --}}
    <h2 class="text-2xl font-bold mb-4">📸 Agregar Imágenes</h2>
    <p class="text-sm text-gray-600 mb-4">Puedes seleccionar múltiples imágenes a la vez</p>

    <form action="{{ route('media.store', $post) }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <input type="hidden" name="type" value="image">

        <div class="mb-4">
            <label class="block mb-2 font-semibold">Seleccionar imágenes (JPG, PNG, WebP)</label>
            <input type="file" 
                   name="files[]" 
                   multiple 
                   accept="image/*"
                   class="w-full p-2 border rounded"
                   id="imageFiles">
            <p class="text-xs text-gray-500 mt-1">Máximo 50MB por imagen</p>
        </div>

        <div id="imagePreview" class="grid grid-cols-3 gap-2 mb-4"></div>

        <x-button type="submit">Subir Imágenes</x-button>
    </form>

    <hr class="my-6">

    {{-- AGREGAR MÚLTIPLES VIDEOS --}}
    <h2 class="text-2xl font-bold mb-4">🎥 Agregar Videos Locales</h2>
    <p class="text-sm text-gray-600 mb-4">Sube videos desde tu computadora</p>

    <form action="{{ route('media.store', $post) }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf
        <input type="hidden" name="type" value="video">

        <div class="mb-4">
            <label class="block mb-2 font-semibold">Seleccionar videos (MP4, MOV, AVI)</label>
            <input type="file" 
                   name="files[]" 
                   multiple 
                   accept="video/*"
                   class="w-full p-2 border rounded">
            <p class="text-xs text-gray-500 mt-1">Máximo 50MB por video</p>
        </div>

        <x-button type="submit">Subir Videos</x-button>
    </form>

    <hr class="my-6">

    {{-- AGREGAR VIDEO EXTERNO (YouTube, Vimeo) --}}
    <h2 class="text-2xl font-bold mb-4">🔗 Agregar Video Externo</h2>
    <p class="text-sm text-gray-600 mb-4">Agrega videos de YouTube, Vimeo, etc.</p>
    
    <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4 text-sm">
        <strong>💡 Cómo obtener la URL de YouTube:</strong><br>
        1. Ve al video en YouTube<br>
        2. Haz clic en "Compartir" → "Insertar"<br>
        3. Copia la URL que aparece en el código (ejemplo: https://www.youtube.com/embed/dQw4w9WgXcQ)<br>
        <strong>O convierte manualmente:</strong><br>
        ❌ <code>https://www.youtube.com/watch?v=dQw4w9WgXcQ</code><br>
        ✅ <code>https://www.youtube.com/embed/dQw4w9WgXcQ</code>
    </div>

    <form action="{{ route('media.store', $post) }}" method="POST" class="mb-6">
        @csrf
        <input type="hidden" name="type" value="video">

        <x-input 
            label="URL del video (debe ser /embed/)" 
            for="external_url" 
            name="external_url" 
            type="url" 
            placeholder="https://www.youtube.com/embed/dQw4w9WgXcQ" />

        <x-button type="submit">Agregar Video</x-button>
    </form>

    <hr class="my-8">

    {{-- GALERÍA DE MEDIA --}}
    <h2 class="text-2xl font-bold mb-4">📂 Galería de Media ({{ $post->media->count() }})</h2>

    @if($post->media->isEmpty())
        <p class="text-gray-500">No hay archivos multimedia agregados aún.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($post->media as $item)
                <div class="border rounded-lg p-3 shadow-sm hover:shadow-md transition">
                    
                    {{-- IMAGEN --}}
                    @if($item->isImage())
                        <img src="{{ asset('storage/' . $item->file_path) }}" 
                             class="w-full h-40 object-cover rounded mb-2"
                             alt="{{ $item->original_filename }}">
                        <p class="text-xs text-gray-600 truncate">{{ $item->original_filename }}</p>

                    {{-- VIDEO EXTERNO --}}
                    @elseif($item->is_external)
                        <div class="bg-gray-100 h-40 flex items-center justify-center rounded mb-2">
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                            </svg>
                        </div>
                        <p class="text-xs text-blue-600 truncate">Video externo</p>
                        <a href="{{ $item->external_url }}" target="_blank" class="text-xs underline">Ver en nueva pestaña</a>

                    {{-- VIDEO LOCAL --}}
                    @elseif($item->isVideo())
                        <video src="{{ asset('storage/' . $item->file_path) }}" 
                               class="w-full h-40 object-cover rounded mb-2"
                               controls></video>
                        <p class="text-xs text-gray-600 truncate">{{ $item->original_filename }}</p>
                    @endif

                    {{-- BOTÓN ELIMINAR --}}
                    <form action="{{ route('media.destroy', $item) }}" 
                          method="POST"
                          onsubmit="return confirm('¿Eliminar este archivo?')"
                          class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded transition">
                            🗑️ Eliminar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- Script para preview de imágenes antes de subir --}}
<script>
document.getElementById('imageFiles')?.addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).slice(0, 9).forEach(file => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-24 object-cover rounded border';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection