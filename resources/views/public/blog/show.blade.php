@extends('layouts.app')

@section('title', $post->title)

@section('content')

{{-- Botón volver --}}
<div class="bg-white border-b">
    <div class="max-w-4xl mx-auto px-6 py-4">
        <a href="{{ route('blog.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">Volver al blog</span>
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Header del post --}}
    <header class="mb-8">
        <div class="mb-4">
            <span class="inline-block bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                📅 {{ \Carbon\Carbon::parse($post->post_date)->format('d \\d\\e F, Y') }}
            </span>
        </div>
        
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        
        @if($post->creator)
            <div class="flex items-center gap-3 text-gray-600">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($post->creator->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $post->creator->name }}</p>
                    <p class="text-sm text-gray-500">Autor</p>
                </div>
            </div>
        @endif
    </header>

    {{-- Imagen de portada --}}
    @if($post->cover_image)
        <div class="mb-8 rounded-xl overflow-hidden shadow-2xl">
            <img src="{{ asset('storage/' . $post->cover_image) }}" 
                 alt="{{ $post->title }}"
                 class="w-full h-auto cursor-pointer hover:opacity-95 transition"
                 onclick="openLightbox('{{ asset('storage/' . $post->cover_image) }}', 'image', '{{ $post->title }}')">
        </div>
    @endif

    {{-- Contenido del post --}}
    <article class="prose prose-lg max-w-none mb-12">
        <div class="bg-white rounded-lg p-8 shadow-sm">
            {!! nl2br(e($post->content)) !!}
        </div>
    </article>

    {{-- Galería de medios --}}
    @if($media->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-900">📸 Galería Multimedia</h2>
                <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-semibold">
                    {{ $media->count() }} {{ $media->count() == 1 ? 'elemento' : 'elementos' }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        
        @foreach($media as $item)

            {{-- Imagen --}}
            @if($item->isImage())
                <div class="relative group cursor-pointer" 
                     onclick="openLightbox('{{ asset('storage/' . $item->file_path) }}', 'image', '{{ $item->original_filename }}')">
                    <img src="{{ asset('storage/' . $item->file_path) }}" 
                         class="rounded shadow w-full h-64 object-cover transition group-hover:opacity-90">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                        <span class="text-white text-4xl opacity-0 group-hover:opacity-100 transition">🔍</span>
                    </div>
                </div>

            {{-- Video externo --}}
            @elseif($item->is_external)
                @php
                    // Extraer ID del video de YouTube
                    $videoId = null;
                    if (preg_match('/youtube\.com\/embed\/([^?&]+)/', $item->external_url, $matches)) {
                        $videoId = $matches[1];
                    } elseif (preg_match('/youtu\.be\/([^?&]+)/', $item->external_url, $matches)) {
                        $videoId = $matches[1];
                    }
                    $thumbnailUrl = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : null;
                @endphp

                <div class="relative cursor-pointer group" 
                     onclick="openLightbox('{{ $item->external_url }}', 'video-external')">
                    
                    @if($thumbnailUrl)
                        {{-- Thumbnail de YouTube --}}
                        <img src="{{ $thumbnailUrl }}" 
                             alt="Video thumbnail"
                             class="w-full h-64 rounded shadow object-cover"
                             onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'">
                    @else
                        {{-- Fallback si no se puede extraer el ID --}}
                        <div class="w-full h-64 rounded shadow bg-gray-900 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-6xl mb-2">▶️</div>
                                <div class="text-white text-sm">Click para ver video</div>
                            </div>
                        </div>
                    @endif

                    {{-- Overlay con ícono de play --}}
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition rounded flex items-center justify-center">
                        <div class="bg-red-600 rounded-full p-4 opacity-0 group-hover:opacity-100 transition transform group-hover:scale-110">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Badge de YouTube --}}
                    <div class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                        YouTube
                    </div>
                </div>

            {{-- Video local --}}

            {{-- Video local --}}
            @elseif($item->isVideo())
                <div class="relative cursor-pointer group" 
                     onclick="openLightbox('{{ asset('storage/' . $item->file_path) }}', 'video-local', '{{ $item->original_filename }}')">
                    <video src="{{ asset('storage/' . $item->file_path) }}" 
                           class="w-full h-64 rounded shadow object-cover"
                           muted
                           preload="metadata"></video>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center rounded">
                        <span class="text-white text-6xl opacity-0 group-hover:opacity-100 transition">▶️</span>
                    </div>
                    <div class="mt-2 text-center text-sm text-gray-600">{{ $item->original_filename }}</div>
                </div>
            @endif

        @endforeach

            </div>
        </section>

    @endif

</div>

{{-- LIGHTBOX MODAL --}}
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden items-center justify-center p-4">
    
    {{-- Botón cerrar --}}
    <button onclick="closeLightbox()" 
            class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition z-10">
        ✕
    </button>

    {{-- Botón descargar (solo para imágenes y videos locales) --}}
    <button id="downloadBtn" 
            onclick="downloadMedia()" 
            class="absolute top-4 left-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition z-10 flex items-center gap-2">
        <span>⬇️</span>
        <span>Descargar</span>
    </button>

    {{-- Contenedor de media --}}
    <div class="w-full h-full flex items-center justify-center p-8" onclick="event.stopPropagation()">
        <img id="lightboxImage" 
             class="max-w-full max-h-full object-contain" 
             style="display: none;"
             alt="">
        <video id="lightboxVideoLocal" 
               class="max-w-full max-h-[90vh] rounded-lg shadow-2xl" 
               style="display: none;"
               controls 
               controlsList="nodownload"></video>
        <iframe id="lightboxVideoExternal" 
                class="w-full h-full max-w-6xl max-h-[85vh] rounded-lg shadow-2xl" 
                style="display: none;"
                frameborder="0" 
                allow="autoplay; fullscreen" 
                allowfullscreen></iframe>
    </div>

    {{-- Nombre del archivo --}}
    <div id="lightboxFilename" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-center"></div>
</div>

<script>
let currentMediaUrl = '';
let currentMediaType = '';
let currentFilename = '';

function openLightbox(url, type, filename = '') {
    console.log('Opening lightbox:', { url, type, filename }); // Debug
    
    currentMediaUrl = url;
    currentMediaType = type;
    currentFilename = filename;

    const lightbox = document.getElementById('lightbox');
    const image = document.getElementById('lightboxImage');
    const videoLocal = document.getElementById('lightboxVideoLocal');
    const videoExternal = document.getElementById('lightboxVideoExternal');
    const downloadBtn = document.getElementById('downloadBtn');
    const filenameEl = document.getElementById('lightboxFilename');

    // Pausar videos primero
    videoLocal.pause();
    videoLocal.currentTime = 0;
    videoExternal.src = '';

    // Ocultar todos
    image.style.display = 'none';
    videoLocal.style.display = 'none';
    videoExternal.style.display = 'none';

    // Mostrar según tipo
    if (type === 'image') {
        image.src = url;
        image.style.display = 'block';
        downloadBtn.style.display = 'flex';
    } else if (type === 'video-local') {
        videoLocal.src = url;
        videoLocal.style.display = 'block';
        videoLocal.load();
        videoLocal.play().catch(e => console.error('Error playing video:', e));
        downloadBtn.style.display = 'flex';
    } else if (type === 'video-external') {
        videoExternal.src = url + '?autoplay=1';
        videoExternal.style.display = 'block';
        downloadBtn.style.display = 'none';
    }

    // Mostrar nombre
    filenameEl.textContent = filename;

    // Mostrar lightbox
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    const image = document.getElementById('lightboxImage');
    const videoLocal = document.getElementById('lightboxVideoLocal');
    const videoExternal = document.getElementById('lightboxVideoExternal');

    // Pausar videos
    videoLocal.pause();
    videoExternal.src = '';

    // Ocultar todo
    image.style.display = 'none';
    videoLocal.style.display = 'none';
    videoExternal.style.display = 'none';

    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

async function downloadMedia() {
    if (currentMediaType === 'image') {
        await downloadImageAsPNG(currentMediaUrl, currentFilename);
    } else if (currentMediaType === 'video-local') {
        downloadVideoDirectly(currentMediaUrl, currentFilename);
    }
}

// Descargar imagen WebP convertida a PNG
async function downloadImageAsPNG(url, filename) {
    try {
        // Mostrar indicador de carga
        const downloadBtn = document.getElementById('downloadBtn');
        const originalText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = '<span>⏳</span><span>Convirtiendo...</span>';
        downloadBtn.disabled = true;

        // Cargar imagen
        const response = await fetch(url);
        const blob = await response.blob();

        // Crear imagen en canvas para convertir a PNG
        const img = new Image();
        const imageUrl = URL.createObjectURL(blob);
        
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            // Convertir a PNG y descargar
            canvas.toBlob(function(pngBlob) {
                const pngUrl = URL.createObjectURL(pngBlob);
                const a = document.createElement('a');
                a.href = pngUrl;
                a.download = (filename || 'imagen').replace(/\.[^.]+$/, '') + '.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                
                // Limpiar
                URL.revokeObjectURL(imageUrl);
                URL.revokeObjectURL(pngUrl);

                // Restaurar botón
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
            }, 'image/png');
        };

        img.src = imageUrl;

    } catch (error) {
        console.error('Error al descargar:', error);
        alert('Error al descargar la imagen');
        
        const downloadBtn = document.getElementById('downloadBtn');
        downloadBtn.innerHTML = '<span>⬇️</span><span>Descargar</span>';
        downloadBtn.disabled = false;
    }
}

// Descargar video directamente
function downloadVideoDirectly(url, filename) {
    const a = document.createElement('a');
    a.href = url;
    a.download = filename || 'video.mp4';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Cerrar con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// Cerrar al hacer click fuera del contenido
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});
</script>

<style>
#lightbox {
    backdrop-filter: blur(10px);
}

#downloadBtn {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

#lightboxImage, 
#lightboxVideoLocal {
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}
</style>
@endsection