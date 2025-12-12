@extends('layouts.admin')

@section('title', $file->title)

@section('content')

{{-- Contenedor principal --}}
<div class="max-w-7xl mx-auto">
    
    {{-- Breadcrumb consistente --}}
    {{-- Se eliminó el div 'bg-white border-b' ya que el layout principal lo provee --}}
    <div class="mb-6 text-sm flex items-center space-x-1 text-gray-500">
        <a href="{{ route('files.groups.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            Grupos
        </a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('files.groups.show', $group) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $group->name }}
        </a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('files.groups.categories.show', [$group, $category]) }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
            {{ $category->name }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-800 font-medium">{{ $file->title }}</span>
    </div>

    {{-- Header (Título, Meta y Botones de Acción) --}}
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 pb-4 border-b border-gray-200 mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $file->title }}</h1>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600">
                
                {{-- Fecha de creación --}}
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $file->created_at->format('d/m/Y H:i') }}
                </span>
                
                {{-- Uploader --}}
                @if($file->uploader)
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Subido por: {{ $file->uploader->name }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="flex gap-3 pt-1">
            <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>

            <form action="{{ route('files.groups.categories.files.destroy', [$group, $category, $file]) }}"
                  method="POST" 
                  onsubmit="return confirm('⚠️ Advertencia: ¿Eliminar este archivo y todos sus adjuntos?')"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>
    
    {{-- Descripción del archivo --}}
    @if($file->description)
        {{-- CAMBIO DE ESTILO: De shadow-md p-6 a shadow-sm p-5 con border-l-4 blue --}}
        <div class="bg-white rounded-xl shadow-sm p-5 mb-8 border-l-4 border-blue-500">
            <p class="text-gray-700">{{ $file->description }}</p>
        </div>
    @endif

    {{-- Adjuntos --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">📎 Adjuntos ({{ $file->attachments->count() }})</h2>
            <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}" 
               class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                <span class="text-xl leading-none">+</span> Agregar más archivos
            </a>
        </div>

        @if($file->attachments->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($file->attachments as $att)
                    @php
                        // LÓGICA DE CLASIFICACIÓN DEL ARCHIVO (NO TOCADA)
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

                    <div class="bg-white rounded-lg border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                        
                        {{-- Thumbnail --}}
                        {{-- COLOR CAMBIADO a blue-500 y indigo-500 --}}
                        <div class="relative h-48 bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center overflow-hidden cursor-pointer"
                             @if($isImage || $isPDF)
                                onclick="openLightbox('{{ route('files.attachments.view', $att) }}', '{{ $thumbnailType }}', '{{ $att->original_filename }}')"
                             @endif>
                            
                            @if($isImage)
                                {{-- Mostrar la imagen real --}}
                                <img src="{{ route('files.attachments.view', $att) }}" 
                                     alt="{{ $att->original_filename }}"
                                     class="w-full h-full object-cover">
                            
                            @elseif($isPDF)
                                {{-- Thumbnail de PDF (SE MANTIENE TU IMAGEN) --}}
                                <img src="{{ asset('images/pdf-thumbnail.png') }}" 
                                     alt="PDF"
                                     class="h-28 opacity-90">
                            
                            @elseif($isWord)
                                {{-- Ícono de Word (SE MANTIENE TU IMAGEN) --}}
                                <img src="{{ asset('images/word-thumbnail.png') }}" 
                                     alt="PDF"
                                     class="h-28 opacity-90">
                            
                            @elseif($isExcel)
                                {{-- Ícono de Excel (SE MANTIENE EL EMOJI) --}}
                                <div class="text-center">
                                    <span class="text-7xl">📊</span>
                                </div>
                            
                            @elseif($isVideo)
                                {{-- Ícono de Video (SE MANTIENE EL EMOJI) --}}
                                <div class="text-center">
                                    <span class="text-7xl">🎥</span>
                                </div>
                            
                            @else
                                {{-- Archivo genérico (SE MANTIENE EL EMOJI) --}}
                                <div class="text-center">
                                    <span class="text-7xl">📄</span>
                                </div>
                            @endif

                            {{-- Overlay con botones --}}
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100">
                                @if($isImage || $isPDF)
                                    {{-- COLOR CAMBIADO de purple-600 a blue-600 --}}
                                    <button onclick="openLightbox('{{ route('files.attachments.view', $att) }}', '{{ $thumbnailType }}', '{{ $att->original_filename }}')" 
                                            class="bg-white text-blue-600 rounded-full p-3 hover:bg-blue-50 transition"
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
                            <div class="absolute top-2 right-2 bg-white rounded-full px-3 py-1 text-xs font-bold text-gray-700 shadow uppercase">
                                {{ $ext }}
                            </div>
                        </div>

                        {{-- Info del archivo --}}
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2">
                                {{ $att->original_filename }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-3">
                                {{ number_format($att->size / 1024, 2) }} KB | {{ $att->mime_type }}
                            </p>

                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('files.attachments.download', $att) }}"
                                   class="flex-1 text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    ⬇️ Descargar
                                </a>

                                <form method="POST"
                                      action="{{ route('files.attachments.destroy', $att) }}"
                                      onsubmit="return confirm('¿Eliminar este adjunto?')"
                                      class="flex-1 border-l border-gray-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full text-sm text-red-600 hover:text-red-800 font-medium pl-2">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State (Estado vacío) --}}
            <div class="flex flex-col items-center justify-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
                <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-3xl mb-4">
                    📎
                </div>
                <h3 class="text-lg font-medium text-gray-900">No hay archivos adjuntos</h3>
                <p class="text-gray-500 mt-1 max-w-sm">
                    Este documento no tiene archivos. Agrega los archivos para que estén disponibles.
                </p>
                <a href="{{ route('files.groups.categories.files.edit', [$group, $category, $file]) }}" 
                   class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium shadow-md">
                    + Agregar archivos
                </a>
            </div>
        @endif
    </div>

</div>

{{-- LIGHTBOX (SIN CAMBIOS EN HTML O JS) --}}
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