@extends('layouts.admin')

@section('title-section', 'Editar Post: ' . Str::limit($post->title, 40))

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 text-sm flex items-center space-x-1 text-gray-500">
    <a href="{{ route('posts.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
        Posts
    </a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-800 font-medium">Editar</span>
</div>

<div class="max-w-6xl mx-auto">

    {{-- ALERTAS Y ERRORES --}}
    @if(session('success'))
        <x-alert type="success" class="mb-6">
            <x-slot name="title">Éxito</x-slot>
            {{ session('success') }}
        </x-alert>
    @endif

    @if(session('error'))
        <x-alert type="danger" class="mb-6">
            <x-slot name="title">Error</x-slot>
            {{ session('error') }}
        </x-alert>
    @endif

    @if(session('warning'))
        <x-alert type="warning" class="mb-6">
            <x-slot name="title">Advertencia</x-slot>
            {{ session('warning') }}
        </x-alert>
    @endif

    @if ($errors->any())
        <div class="mb-6">
            <x-alert type="danger">
                <x-slot name="title">¡Atención! Errores de validación:</x-slot>
                <ul class="list-disc pl-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        </div>
    @endif

    {{-- Tarjeta Principal --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100">

        {{-- SECCIÓN 1: EDITAR DATOS PRINCIPALES DEL POST --}}
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold text-gray-900">1. Detalles del Post</h2>
            <p class="text-sm text-gray-500 mt-1">Modifica el título, contenido, fecha y portada principal.</p>
        </div>

        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-6">

                {{-- Título --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:items-center">
                    <label for="title" class="md:text-right font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                    <div class="md:col-span-3">
                        <x-input for="title" name="title" type="text" required :value="old('title', $post->title)"
                                 class="w-full border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500" />
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <label for="content" class="md:text-right font-medium text-gray-700">Contenido <span class="text-red-500">*</span></label>
                    <div class="md:col-span-3">
                        <x-textarea for="content" name="content" 
                                    class="w-full border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500 h-40">{{ old('content', $post->content) }}</x-textarea>
                    </div>
                </div>

                {{-- Fecha del Post --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:items-center">
                    <label for="post_date" class="md:text-right font-medium text-gray-700">Fecha de Publicación</label>
                    <div class="md:col-span-3">
                        <x-input for="post_date" name="post_date" type="date" :value="old('post_date', \Carbon\Carbon::parse($post->post_date)->format('Y-m-d'))"
                                 class="w-full md:w-auto border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500" />
                    </div>
                </div>

                {{-- Portada Actual y Cambio --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-dashed border-gray-200">
                    <div class="md:text-right font-medium text-gray-700 pt-2">Portada Actual</div>
                    <div class="md:col-span-3">
                        @if($post->cover_image)
                            <img src="{{ asset('storage/' . $post->cover_image) }}" class="w-48 h-auto object-cover rounded-lg shadow-md border mb-4">
                        @else
                            <div class="w-48 h-auto p-4 bg-gray-100 rounded-lg border text-center text-gray-500 mb-4">
                                No hay portada
                            </div>
                        @endif

                        <x-input label="Cambiar Portada (Opcional)" for="cover" name="cover" type="file" accept="image/*" />
                        <p class="mt-1 text-sm text-gray-500">Sube una nueva imagen para reemplazar la actual.</p>
                    </div>
                </div>

            </div>

            {{-- Footer / Botón de Acción --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <x-button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold shadow-md px-6 py-3">
                    <span class="mr-2">🔄</span> Actualizar Post
                </x-button>
            </div>
        </form>
        
        <div class="border-t border-gray-100">

            {{-- SECCIÓN 2: AGREGAR MEDIA --}}
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">2. Gestión de Contenido Multimedia</h2>
                <p class="text-sm text-gray-500 mt-1">Sube o enlaza imágenes y videos que acompañarán la publicación.</p>
            </div>
            
            <div class="p-6 space-y-8">
                
                {{-- A. AGREGAR MÚLTIPLES IMÁGENES CON AJAX --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2">📸 Agregar Imágenes Locales</h3>
                    <p class="text-sm text-gray-600 mb-4">Sube hasta 100 imágenes. Se procesarán una por una automáticamente.</p>

                    <div class="space-y-4">
                        <div>
                            <label for="imageFiles" class="block mb-2 font-medium text-gray-700">Seleccionar imágenes (JPG, PNG, WebP)</label>
                            <input type="file" 
                                   name="files[]" 
                                   multiple 
                                   accept="image/*"
                                   class="w-full p-2 border border-gray-300 rounded-lg bg-white focus:border-yellow-500 focus:ring-yellow-500"
                                   id="imageFiles"
                                   onchange="updateImageCount()">
                            <p class="text-xs text-gray-500 mt-1">Máximo 10MB por imagen. Se subirán de 1 en 1.</p>
                            <p id="imageFileCount" class="text-sm font-medium text-blue-600 mt-2"></p>
                        </div>

                        <div id="imagePreview" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-2 pt-2"></div>

                        {{-- Barra de progreso mejorada --}}
                        <div id="imageUploadProgress" class="hidden">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-blue-900">Subiendo imágenes...</span>
                                    <span id="imageProgressPercent" class="text-sm font-bold text-blue-600">0%</span>
                                </div>
                                <div class="bg-blue-200 rounded-full h-4 overflow-hidden">
                                    <div id="imageProgressBar" class="bg-blue-600 h-full transition-all duration-300 ease-out" style="width: 0%"></div>
                                </div>
                                <p id="imageProgressText" class="text-xs text-blue-700 mt-2">Esperando...</p>
                                
                                {{-- Lista de archivos procesados --}}
                                <div id="imageProcessedList" class="mt-4 max-h-40 overflow-y-auto space-y-1"></div>
                                
                                {{-- Resumen final --}}
                                <div id="imageSummary" class="mt-4 pt-3 border-t border-blue-200 hidden">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-green-700 font-semibold">✓ <span id="successCount">0</span> exitosas</span>
                                        <span class="text-red-700 font-semibold">✗ <span id="errorCount">0</span> fallidas</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="button"
                                    id="imageSubmitBtn"
                                    onclick="uploadImagesSequentially()"
                                    class="bg-blue-500 hover:bg-blue-600 text-white shadow-sm px-4 py-2 text-sm font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Subir Imágenes
                            </button>
                            
                            <button type="button"
                                    id="imageCancelBtn"
                                    onclick="cancelImageUpload()"
                                    class="hidden ml-3 bg-red-500 hover:bg-red-600 text-white shadow-sm px-4 py-2 text-sm font-semibold rounded-lg transition">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>

                {{-- B. AGREGAR VIDEOS LOCALES --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2">🎥 Agregar Videos Locales</h3>
                    <p class="text-sm text-gray-600 mb-4">Sube videos desde tu computadora.</p>

                    <form action="{{ route('media.store', $post) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="videoUploadForm"
                          class="space-y-4">
                        @csrf
                        <input type="hidden" name="type" value="video">

                        <div>
                            <label for="videoFiles" class="block mb-2 font-medium text-gray-700">Seleccionar videos (MP4, MOV, AVI)</label>
                            <input type="file" 
                                   name="files[]" 
                                   multiple 
                                   accept="video/*"
                                   class="w-full p-2 border border-gray-300 rounded-lg bg-white focus:border-yellow-500 focus:ring-yellow-500"
                                   id="videoFiles"
                                   onchange="updateVideoCount()">
                            <p class="text-xs text-gray-500 mt-1">Máximo 50MB por video.</p>
                            <p id="videoFileCount" class="text-sm font-medium text-blue-600 mt-2"></p>
                        </div>

                        {{-- Barra de progreso para videos --}}
                        <div id="videoUploadProgress" class="hidden">
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-purple-900">Subiendo videos...</span>
                                    <span id="videoProgressPercent" class="text-sm font-bold text-purple-600">0%</span>
                                </div>
                                <div class="bg-purple-200 rounded-full h-3 overflow-hidden">
                                    <div id="videoProgressBar" class="bg-purple-600 h-full transition-all duration-300 ease-out" style="width: 0%"></div>
                                </div>
                                <p id="videoProgressText" class="text-xs text-purple-700 mt-2 text-center">Preparando videos...</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    id="videoSubmitBtn"
                                    class="bg-blue-500 hover:bg-blue-600 text-white shadow-sm px-4 py-2 text-sm font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Subir Videos
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- C. AGREGAR VIDEO EXTERNO (YouTube, Vimeo) --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-bold mb-3 flex items-center gap-2">🔗 Agregar Video Externo</h3>
                    <p class="text-sm text-gray-600 mb-4">Agrega videos de YouTube, Vimeo, etc.</p>

                    <div class="bg-yellow-100 border border-yellow-300 rounded p-3 mb-4 text-sm text-gray-800">
                        <strong class="font-semibold block mb-1">💡 Cualquier URL de YouTube funciona:</strong>
                        <ul class="list-disc pl-5 space-y-1 text-xs">
                            <li>✅ <code class="bg-yellow-200 px-1 rounded">youtube.com/watch?v=...</code></li>
                            <li>✅ <code class="bg-yellow-200 px-1 rounded">youtu.be/...</code></li>
                            <li>✅ <code class="bg-yellow-200 px-1 rounded">youtube.com/embed/...</code></li>
                        </ul>
                        <p class="text-xs mt-2 text-gray-600">El sistema convertirá automáticamente al formato correcto.</p>
                    </div>

                    <form action="{{ route('media.store', $post) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="type" value="video">

                        <div>
                            <label for="external_url" class="block mb-2 font-medium text-gray-700">URL del video</label>
                            <x-input 
                                for="external_url" 
                                name="external_url" 
                                type="url" 
                                placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ" 
                                class="w-full border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500" />
                        </div>

                        <div class="pt-2">
                            <x-button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white shadow-sm px-4 py-2 text-sm font-semibold">
                                Agregar Video
                            </x-button>
                        </div>
                    </form>
                </div>
                
            </div>
            
            {{-- SECCIÓN 3: GALERÍA DE MEDIA --}}
            <div class="p-6 border-t border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">3. Galería de Media ({{ $post->media->count() }})</h2>
                <p class="text-sm text-gray-500 mt-1">Lista de todos los archivos multimedia asociados al post.</p>
            </div>
            
            <div class="p-6">
                @if($post->media->isEmpty())
                    <div class="text-center py-10 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-4xl mb-2">📂</div>
                        <p class="text-gray-500 font-medium">No hay archivos multimedia agregados aún.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                        @foreach($post->media as $item)
                            <div class="bg-white border rounded-lg shadow-sm hover:shadow-md transition overflow-hidden group relative">
                                
                                {{-- Contenido del Media --}}
                                <div class="w-full h-32 flex items-center justify-center bg-gray-100 overflow-hidden">
                                    {{-- IMAGEN --}}
                                    @if(method_exists($item, 'isImage') && $item->isImage())
                                        <img src="{{ asset('storage/' . $item->file_path) }}" 
                                             class="w-full h-full object-cover"
                                             alt="{{ $item->original_filename }}">
                                    
                                    {{-- VIDEO EXTERNO --}}
                                    @elseif($item->is_external)
                                        <div class="text-center">
                                            <span class="text-4xl text-blue-500">🔗</span>
                                            <p class="text-xs text-blue-600 mt-1">Video Externo</p>
                                        </div>
                                    
                                    {{-- VIDEO LOCAL --}}
                                    @elseif(method_exists($item, 'isVideo') && $item->isVideo())
                                        <div class="text-center">
                                            <span class="text-4xl text-red-500">🎥</span>
                                            <p class="text-xs text-red-600 mt-1">Video Local</p>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Info y Botón --}}
                                <div class="p-3">
                                    <p class="text-xs text-gray-500 mb-1">
                                        {{ $item->is_external ? 'ENLACE' : 'WEBP' }}
                                    </p>
                                    <p class="text-sm font-semibold text-gray-800 line-clamp-2">
                                        {{ $item->is_external ? Str::limit($item->external_url, 30) : $item->original_filename }}
                                    </p>
                                    
                                    @if(!$item->is_external && $item->file_path)
                                        <p class="text-xs text-green-600 mt-1">
                                            ✓ Optimizado {{ round(Storage::disk('public')->size($item->file_path) / 1024, 1) }} KB
                                        </p>
                                    @endif
                                    
                                    {{-- BOTÓN ELIMINAR --}}
                                    <form action="{{ route('media.destroy', $item) }}" 
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este archivo multimedia?')"
                                          class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-500 hover:bg-red-600 text-white text-xs py-2 rounded transition font-semibold">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
        </div>
        
    </div>
    
</div>

@push('scripts')
<script>
// Variables globales
let isUploading = false;
let uploadCancelled = false;

// ========================================
// PREVIEW Y CONTADOR DE IMÁGENES
// ========================================
function updateImageCount() {
    const input = document.getElementById('imageFiles');
    const count = input.files.length;
    const countEl = document.getElementById('imageFileCount');
    const preview = document.getElementById('imagePreview');
    
    preview.innerHTML = '';
    
    if (count > 0) {
        const totalSize = Array.from(input.files).reduce((sum, file) => sum + file.size, 0);
        const totalMB = (totalSize / 1024 / 1024).toFixed(2);
        countEl.textContent = `✓ ${count} archivo(s) seleccionado(s) (${totalMB} MB total)`;
        countEl.classList.add('text-green-600');
        countEl.classList.remove('text-blue-600');
        
        // Preview de primeras 12 imágenes
        Array.from(input.files).slice(0, 12).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-24 object-cover rounded border shadow-sm';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
        
        if (count > 12) {
            const more = document.createElement('div');
            more.className = 'w-full h-24 flex items-center justify-center bg-gray-100 rounded border text-gray-600 font-semibold';
            more.textContent = `+${count - 12} más`;
            preview.appendChild(more);
        }
    } else {
        countEl.textContent = '';
        countEl.classList.remove('text-green-600');
        countEl.classList.add('text-blue-600');
    }
}

// ========================================
// SUBIDA SECUENCIAL DE IMÁGENES (1x1)
// ========================================
async function uploadImagesSequentially() {
    const fileInput = document.getElementById('imageFiles');
    const files = Array.from(fileInput.files);
    
    if (files.length === 0) {
        alert('Por favor selecciona al menos una imagen');
        return;
    }
    
    // UI Elements
    const submitBtn = document.getElementById('imageSubmitBtn');
    const cancelBtn = document.getElementById('imageCancelBtn');
    const progress = document.getElementById('imageUploadProgress');
    const progressBar = document.getElementById('imageProgressBar');
    const progressPercent = document.getElementById('imageProgressPercent');
    const progressText = document.getElementById('imageProgressText');
    const processedList = document.getElementById('imageProcessedList');
    const summary = document.getElementById('imageSummary');
    const successCountEl = document.getElementById('successCount');
    const errorCountEl = document.getElementById('errorCount');
    
    // Reset estado
    isUploading = true;
    uploadCancelled = false;
    let successCount = 0;
    let errorCount = 0;
    
    // Mostrar UI
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Subiendo...';
    cancelBtn.classList.remove('hidden');
    progress.classList.remove('hidden');
    processedList.innerHTML = '';
    summary.classList.add('hidden');
    
    const totalFiles = files.length;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    
    // Procesar archivo por archivo
    for (let i = 0; i < files.length; i++) {
        if (uploadCancelled) {
            progressText.textContent = '❌ Subida cancelada por el usuario';
            break;
        }
        
        const file = files[i];
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'image');
        formData.append('_token', csrfToken);
        
        try {
            progressText.textContent = `Procesando: ${file.name} (${i + 1}/${totalFiles})`;
            
            const response = await fetch('{{ route("media.storeSingle", $post) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                successCount++;
                addProcessedItem(file.name, 'success', result.size);
            } else {
                errorCount++;
                addProcessedItem(file.name, 'error', result.message);
            }
            
        } catch (error) {
            errorCount++;
            addProcessedItem(file.name, 'error', error.message);
            console.error('Error:', error);
        }
        
        // Actualizar progreso
        const percent = Math.round(((i + 1) / totalFiles) * 100);
        progressBar.style.width = percent + '%';
        progressPercent.textContent = percent + '%';
    }
    
    // Finalizar
    isUploading = false;
    submitBtn.disabled = false;
    submitBtn.textContent = 'Subir Imágenes';
    cancelBtn.classList.add('hidden');
    
    if (!uploadCancelled) {
        progressText.textContent = `✓ Proceso completado: ${successCount} exitosas, ${errorCount} fallidas`;
        summary.classList.remove('hidden');
        successCountEl.textContent = successCount;
        errorCountEl.textContent = errorCount;
        
        // Limpiar input
        fileInput.value = '';
        updateImageCount();
        
        // Recargar página después de 3 segundos si hay éxitos
        if (successCount > 0) {
            setTimeout(() => {
                window.location.reload();
            }, 3000);
        }
    }
}

function addProcessedItem(filename, status, detail) {
    const list = document.getElementById('imageProcessedList');
    const item = document.createElement('div');
    item.className = `text-xs p-2 rounded ${status === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}`;
    
    const icon = status === 'success' ? '✓' : '✗';
    item.innerHTML = `<strong>${icon} ${filename}</strong><br><span class="text-xs opacity-75">${detail}</span>`;
    
    list.appendChild(item);
    list.scrollTop = list.scrollHeight; // Auto-scroll
}

function cancelImageUpload() {
    if (confirm('¿Estás seguro de cancelar la subida?')) {
        uploadCancelled = true;
    }
}

// ========================================
// CONTADOR DE VIDEOS
// ========================================
function updateVideoCount() {
    const input = document.getElementById('videoFiles');
    const count = input.files.length;
    const countEl = document.getElementById('videoFileCount');
    
    if (count > 0) {
        const totalSize = Array.from(input.files).reduce((sum, file) => sum + file.size, 0);
        const totalMB = (totalSize / 1024 / 1024).toFixed(2);
        countEl.textContent = `✓ ${count} video(s) seleccionado(s) (${totalMB} MB total)`;
        countEl.classList.add('text-green-600');
        countEl.classList.remove('text-blue-600');
    } else {
        countEl.textContent = '';
        countEl.classList.remove('text-green-600');
        countEl.classList.add('text-blue-600');
    }
}

// ========================================
// PROGRESO DE VIDEOS (mantener original)
// ========================================
document.getElementById('videoUploadForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('videoFiles');
    const fileCount = fileInput.files.length;
    
    if (fileCount === 0) {
        e.preventDefault();
        alert('Por favor selecciona al menos un video');
        return;
    }
    
    const submitBtn = document.getElementById('videoSubmitBtn');
    const progress = document.getElementById('videoUploadProgress');
    const progressBar = document.getElementById('videoProgressBar');
    const progressPercent = document.getElementById('videoProgressPercent');
    const progressText = document.getElementById('videoProgressText');
    
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ Subiendo...';
    progress.classList.remove('hidden');
    
    let width = 0;
    const interval = setInterval(() => {
        width += 2;
        if (width >= 90) {
            clearInterval(interval);
            progressBar.style.width = '90%';
            progressPercent.textContent = '90%';
            progressText.textContent = 'Procesando videos... Espera un momento.';
        } else {
            progressBar.style.width = width + '%';
            progressPercent.textContent = width + '%';
            progressText.textContent = `Subiendo videos... (${Math.floor(width / (90 / fileCount))}/${fileCount})`;
        }
    }, 150);
});
</script>
@endpush
@endsection