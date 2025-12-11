@extends('layouts.app')

@section('title', $file->title)

@section('content')

{{-- Breadcrumb --}}
<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('files.groups.index') }}" class="hover:text-purple-600 transition">
                📁 Grupos
            </a>
            <span>/</span>
            <a href="{{ route('files.groups.show', $group) }}" class="hover:text-purple-600 transition">
                {{ $group->name }}
            </a>
            <span>/</span>
            <a href="{{ route('files.groups.categories.show', [$group, $category]) }}" class="hover:text-purple-600 transition">
                {{ $category->name }}
            </a>
            <span>/</span>
            <span class="text-purple-600 font-medium">{{ $file->title }}</span>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $file->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $file->created_at->format('d/m/Y H:i') }}
                    </span>
                    @if($file->uploader)
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $file->uploader->name }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>

                <form action="{{ route('files.groups.categories.files.destroy', [$group, $category, $file]) }}"
                      method="POST" 
                      onsubmit="return confirm('¿Eliminar este archivo y todos sus adjuntos?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        @if($file->description)
            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-purple-500">
                <p class="text-gray-700">{{ $file->description }}</p>
            </div>
        @endif
    </div>

    {{-- Adjuntos --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">📎 Adjuntos ({{ $file->attachments->count() }})</h2>
            <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}" 
               class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                + Agregar más archivos
            </a>
        </div>

        @if($file->attachments->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($file->attachments as $att)
                    @php
                        $ext = strtolower(pathinfo($att->original_filename, PATHINFO_EXTENSION));
                        $isImage = str_starts_with($att->mime_type, 'image/');
                        $isPDF = $att->mime_type === 'application/pdf';
                        $isWord = str_contains($att->mime_type, 'word');
                        $isExcel = str_contains($att->mime_type, 'excel') || str_contains($att->mime_type, 'spreadsheet');
                        $isVideo = str_starts_with($att->mime_type, 'video/');
                        
                        // Determinar thumbnail
                        if ($isImage) {
                            $thumbnailType = 'image';
                        } elseif ($isPDF) {
                            $thumbnailType = 'pdf';
                        } elseif ($isWord) {
                            $thumbnailType = 'word';
                        } elseif ($isExcel) {
                            $thumbnailType = 'excel';
                        } elseif ($isVideo) {
                            $thumbnailType = 'video';
                        } else {
                            $thumbnailType = 'generic';
                        }
                    @endphp

                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        
                        {{-- Thumbnail --}}
                        <div class="relative h-48 bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center overflow-hidden cursor-pointer"
                             @if($isImage || $isPDF)
                                onclick="openLightbox('{{ route('files.attachments.view', $att) }}', '{{ $thumbnailType }}', '{{ $att->original_filename }}')"
                             @endif>
                            
                            @if($isImage)
                                {{-- Mostrar la imagen real --}}
                                <img src="{{ route('files.attachments.view', $att) }}" 
                                     alt="{{ $att->original_filename }}"
                                     class="w-full h-full object-cover">
                            
                            @elseif($isPDF)
                                {{-- Thumbnail de PDF --}}
                                <img src="{{ asset('images/pdf-thumbnail.png') }}" 
                                     alt="PDF"
                                     class="h-28 opacity-90">
                            
                            @elseif($isWord)
                                {{-- Ícono de Word --}}
                                <div class="text-center">
                                    <span class="text-7xl">📝</span>
                                </div>
                            
                            @elseif($isExcel)
                                {{-- Ícono de Excel --}}
                                <div class="text-center">
                                    <span class="text-7xl">📊</span>
                                </div>
                            
                            @elseif($isVideo)
                                {{-- Ícono de Video --}}
                                <div class="text-center">
                                    <span class="text-7xl">🎥</span>
                                </div>
                            
                            @else
                                {{-- Archivo genérico --}}
                                <div class="text-center">
                                    <span class="text-7xl">📄</span>
                                </div>
                            @endif

                            {{-- Overlay con botones --}}
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100">
                                @if($isImage || $isPDF)
                                    <button onclick="openLightbox('{{ route('files.attachments.view', $att) }}', '{{ $thumbnailType }}', '{{ $att->original_filename }}')" 
                                            class="bg-white text-purple-600 rounded-full p-3 hover:bg-purple-50 transition"
                                            title="Ver">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                @endif
                                
                                <a href="{{ route('files.attachments.download', $att) }}" 
                                   class="bg-white text-blue-600 rounded-full p-3 hover:bg-blue-50 transition"
                                   title="Descargar">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>

                            {{-- Badge de extensión --}}
                            <div class="absolute top-2 right-2 bg-white rounded px-2 py-1 text-xs font-bold text-gray-700 shadow uppercase">
                                {{ $ext }}
                            </div>
                        </div>

                        {{-- Info del archivo --}}
                        <div class="p-4">
                            <p class="text-xs text-gray-500 mb-1">{{ $att->mime_type }}</p>
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                {{ $att->original_filename }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-3">
                                {{ number_format($att->size / 1024, 2) }} KB
                            </p>

                            <div class="flex gap-2 pt-3 border-t">
                                <a href="{{ route('files.attachments.download', $att) }}"
                                   class="flex-1 text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    ⬇️ Descargar
                                </a>

                                <form method="POST"
                                      action="{{ route('files.attachments.destroy', $att) }}"
                                      onsubmit="return confirm('¿Eliminar este adjunto?')"
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full text-sm text-red-600 hover:text-red-800 font-medium">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📎</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay adjuntos</h3>
                <p class="text-gray-500 mb-4">Agrega archivos a este documento</p>
                <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}" 
                   class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition">
                    + Agregar archivos
                </a>
            </div>
        @endif
    </div>

</div>

{{-- LIGHTBOX --}}
<div id="lightbox" 
     class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden items-center justify-center p-4">
    
    {{-- Botón cerrar --}}
    <button onclick="closeLightbox()" 
            class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition z-10">
        ✕
    </button>

    {{-- Botón descargar --}}
    <a id="lightboxDownload" 
       href="#"
       download
       class="absolute top-4 left-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition z-10 flex items-center gap-2">
        <span>⬇️</span>
        <span>Descargar</span>
    </a>

    {{-- Contenedor de media --}}
    <div class="w-full h-full flex items-center justify-center p-8" onclick="event.stopPropagation()">
        <img id="lightboxImage" 
             class="max-w-full max-h-full object-contain shadow-2xl rounded-lg" 
             style="display: none;">
        <iframe id="lightboxPDF" 
                class="w-full h-full max-w-6xl max-h-[90vh] rounded-lg shadow-2xl" 
                style="display: none;"></iframe>
    </div>

    {{-- Nombre del archivo --}}
    <div id="lightboxFilename" 
         class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white text-center bg-black bg-opacity-50 px-4 py-2 rounded-lg"></div>
</div>

<script>
let currentUrl = '';
let currentFilename = '';

function openLightbox(url, type, filename) {
    currentUrl = url;
    currentFilename = filename;

    const lightbox = document.getElementById('lightbox');
    const image = document.getElementById('lightboxImage');
    const pdf = document.getElementById('lightboxPDF');
    const filenameEl = document.getElementById('lightboxFilename');
    const downloadBtn = document.getElementById('lightboxDownload');

    // Ocultar todos
    image.style.display = 'none';
    pdf.style.display = 'none';

    // Mostrar según tipo
    if (type === 'image') {
        image.src = url;
        image.style.display = 'block';
    } else if (type === 'pdf') {
        pdf.src = url;
        pdf.style.display = 'block';
    }

    // Actualizar nombre y botón de descarga
    filenameEl.textContent = filename;
    downloadBtn.href = url.replace('/view', '/download');

    // Mostrar lightbox
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    const pdf = document.getElementById('lightboxPDF');
    
    pdf.src = '';
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// Cerrar al hacer click fuera
document.getElementById('lightbox')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});
</script>

@endsection