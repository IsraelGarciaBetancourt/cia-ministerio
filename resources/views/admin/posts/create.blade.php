@extends('layouts.admin')

@section('title-section', 'Crear Nuevo Post')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 text-sm flex items-center space-x-1 text-gray-500">
    <a href="{{ route('posts.index') }}" class="text-blue-600 hover:text-blue-800 hover:underline transition">
        Posts
    </a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-800 font-medium">Crear</span>
</div>

<div class="max-w-4xl mx-auto">

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        
        <div class="p-6 border-b border-gray-100">
            <h1 class="text-2xl font-bold text-gray-900">
                Crear Nuevo Post
            </h1>
        </div>
        
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-8">
                
                {{-- Contenedor de Errores (Si hay) --}}
                @if ($errors->any())
                    <div>
                        {{-- Asumiendo que x-alert tiene estilos consistentes --}}
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
                
                {{-- Título --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:items-center">
                    <label for="title" class="md:text-right font-medium text-gray-700">Título <span class="text-red-500">*</span></label>
                    <div class="md:col-span-2">
                        <x-input for="title" name="title" type="text" required :value="old('title')"
                                 class="w-full border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500" 
                                 placeholder="Escriba el título del post" />
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label for="content" class="md:text-right font-medium text-gray-700">Contenido <span class="text-red-500">*</span></label>
                    <div class="md:col-span-2">
                        <x-textarea for="content" name="content" 
                                    class="w-full border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500 h-40" 
                                    placeholder="Escriba aquí el contenido completo del post...">{{ old('content') }}</x-textarea>
                    </div>
                </div>

                {{-- Fecha del Post --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:items-center">
                    <label for="post_date" class="md:text-right font-medium text-gray-700">Fecha de Publicación</label>
                    <div class="md:col-span-2">
                        <x-input for="post_date" name="post_date" type="date" :value="old('post_date', now()->format('Y-m-d'))"
                                 class="w-full md:w-auto border-gray-300 rounded-lg focus:border-yellow-500 focus:ring-yellow-500" />
                        <p class="mt-1 text-sm text-gray-500">Por defecto, es la fecha de hoy.</p>
                    </div>
                </div>
                
                {{-- Portada --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label for="cover" class="md:text-right font-medium text-gray-700">Imagen de Portada</label>
                    <div class="md:col-span-2">
                        <x-input for="cover" name="cover" type="file" accept="image/*" 
                                 class="w-full" />
                        <p class="mt-1 text-sm text-gray-500">Tamaño máximo 5 MB. Se convierte a WebP automáticamente.</p>
                    </div>
                </div>
            </div>

            {{-- Footer / Botón de Acción --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <x-button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold shadow-md px-6 py-3">
                    <span class="mr-2">💾</span> Publicar Post
                </x-button>
            </div>
        </form>
    </div>

</div>
@endsection