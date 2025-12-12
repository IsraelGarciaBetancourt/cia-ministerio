@extends('layouts.app')

@section('title', 'Blog - CIA Ministerio')

@section('content')

{{-- ===================================================== --}}
{{-- HERO --}}
{{-- ===================================================== --}}
<section class="bg-primary-100 py-12 border-b border-primary-200 mt-10">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-text-primary mb-3">
            Eventos y Noticias
        </h1>
        <p class="text-text-muted text-lg">
            Mantente informado con las últimas actualizaciones de nuestra iglesia.
        </p>
    </div>
</section>

{{-- ===================================================== --}}
{{-- CONTENEDOR --}}
{{-- ===================================================== --}}
<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- ================================================= --}}
    {{-- SEARCH BAR --}}
    {{-- ================================================= --}}
    <form method="GET" class="mb-8">
        <div class="relative">
            <span class="absolute left-4 top-2.5 text-text-muted">🔍</span>

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Buscar..."
                   class="w-full pl-12 pr-4 py-2 rounded-lg bg-light border border-border 
                          text-text-primary shadow-sm
                          focus:border-primary-200 focus:ring-primary-200">
        </div>
    </form>


    {{-- ================================================= --}}
    {{-- FILTROS (ÚNICA / RANGO con MESES + AÑO) --}}
    {{-- ================================================= --}}
    <div
        x-data="{
            open: {{ request('from_month') || request('to_month') || request('month') ? 'true' : 'false' }},
            rango: {{ request('from_month') || request('to_month') ? 'true' : 'false' }},
        }"
        class="mb-12"
    >
        {{-- Botón --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-text-primary">Filtros</h3>

            <button type="button"
                    @click="open = !open"
                    class="px-4 py-2 rounded-md bg-dark text-light hover:bg-dark-100 transition text-sm">
                Filtros
            </button>
        </div>


        {{-- PANEL --}}
        <form method="GET"
            x-show="open"
            x-transition
            class="bg-light border border-border rounded-xl p-6 shadow-sm">

            {{-- Mantener búsqueda al aplicar filtros --}}
            <input type="hidden" name="search" value="{{ request('search') }}">

            {{-- Toggle Única/Rango --}}
            <div class="flex items-center justify-between mb-6">
                <span class="text-sm text-text-muted">Fecha única / Rango</span>

                <button type="button"
                        @click="rango = !rango"
                        class="relative w-12 h-6 rounded-full transition"
                        :class="rango ? 'bg-dark' : 'bg-light-200 border border-border'">
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition"
                        :class="rango ? 'translate-x-6' : ''"></span>
                </button>
            </div>


            {{-- ===================================================== --}}
            {{-- FECHA ÚNICA (MES + AÑO) --}}
            {{-- ===================================================== --}}
            <div x-show="!rango" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Mes --}}
                <div>
                    <label class="text-sm text-text-primary">Mes</label>
                    <select name="month"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">
                        <option value="">Seleccione</option>

                        @foreach([
                            '01' => 'Enero',
                            '02' => 'Febrero',
                            '03' => 'Marzo',
                            '04' => 'Abril',
                            '05' => 'Mayo',
                            '06' => 'Junio',
                            '07' => 'Julio',
                            '08' => 'Agosto',
                            '09' => 'Septiembre',
                            '10' => 'Octubre',
                            '11' => 'Noviembre',
                            '12' => 'Diciembre'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Año --}}
                <div>
                    <label class="text-sm text-text-primary">Año</label>
                    <select name="year"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">
                        <option value="">Seleccione</option>

                        @foreach(range(now()->year, now()->year - 20) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            {{-- ===================================================== --}}
            {{-- RANGO DE FECHAS (MES + AÑO DESDE / HASTA) --}}
            {{-- ===================================================== --}}
            <div x-show="rango" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Desde --}}
                <div>
                    <label class="text-sm text-text-primary">Desde (Mes)</label>
                    <select name="from_month"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">

                        <option value="">Seleccione</option>
                        @foreach([
                            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ request('from_month') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                    <label class="text-sm text-text-primary mt-3 block">Desde (Año)</label>
                    <select name="from_year"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">
                        <option value="">Seleccione</option>

                        @foreach(range(now()->year, now()->year - 20) as $y)
                            <option value="{{ $y }}" {{ request('from_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hasta --}}
                <div>
                    <label class="text-sm text-text-primary">Hasta (Mes)</label>
                    <select name="to_month"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">
                        <option value="">Seleccione</option>

                        @foreach([
                            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                            '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                            '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                        ] as $num => $name)
                            <option value="{{ $num }}" {{ request('to_month') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>

                    <label class="text-sm text-text-primary mt-3 block">Hasta (Año)</label>
                    <select name="to_year"
                        class="mt-1 w-full rounded-md bg-light border border-border px-3 py-2
                            focus:border-primary-200 focus:ring-primary-200">
                        <option value="">Seleccione</option>

                        @foreach(range(now()->year, now()->year - 20) as $y)
                            <option value="{{ $y }}" {{ request('to_year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>


            {{-- BOTONES --}}
            <div class="flex justify-end gap-4 mt-10">
                <a href="{{ route('blog.index') }}"
                class="px-4 py-2 rounded-md border border-border bg-light text-text-muted hover:bg-light-200 transition text-sm">
                    Limpiar
                </a>

                <button type="submit"
                        class="px-6 py-2 rounded-md bg-primary-200 text-dark font-semibold hover:bg-primary-300 transition text-sm">
                    Aplicar
                </button>
            </div>
        </form>
    </div>



    {{-- ===================================================== --}}
    {{-- LISTADO DE POSTS --}}
    {{-- ===================================================== --}}
    <h2 class="text-lg font-semibold text-text-primary mb-4">Últimas publicaciones</h2>

    @if($posts->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

            @foreach($posts as $post)
                <article class="bg-light rounded-xl shadow-sm border border-border overflow-hidden hover:shadow-md transition">
                    
                    {{-- Imagen o icono por defecto --}}
                    <a href="{{ route('blog.show', $post) }}" class="block">
                        <div class="relative h-48 bg-surface-muted flex items-center justify-center">
                            @if($post->cover_image)
                                <img src="{{ asset('storage/' . $post->cover_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-primary-100 flex items-center justify-center">
                                    {{-- ícono de documento simple --}}
                                    <span class="text-3xl">📄</span>
                                </div>
                            @endif
                        </div>
                    </a>

                    {{-- Contenido --}}
                    <div class="p-4">
                        <p class="text-xs text-primary-200 font-medium mb-1">
                            {{ \Carbon\Carbon::parse($post->post_date)->format('F d, Y') }}
                        </p>

                        <a href="{{ route('blog.show', $post) }}"
                           class="block text-text-primary font-semibold text-sm leading-tight line-clamp-2 hover:text-primary-300 transition mb-2">
                            {{ $post->title }}
                        </a>

                        {{-- Descripción corta --}}
                        <p class="text-xs text-text-muted line-clamp-2 mb-4">
                            {{ Str::limit(strip_tags($post->content), 110) }}
                        </p>

                        <div class="flex items-center justify-between pt-3 border-t border-border">
                            <a href="{{ route('blog.show', $post) }}"
                               class="text-sm font-semibold text-text-primary hover:text-primary-300 transition">
                                Leer más →
                            </a>

                            @if($post->media->count() > 0)
                                <span class="text-xs text-text-muted flex items-center gap-1">
                                    📷 {{ $post->media->count() }}
                                </span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach

        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-6 flex justify-center">
            {{ $posts->links() }}
        </div>

    @else
        {{-- SIN POSTS --}}
        <div class="text-center py-20">
            <div class="text-7xl mb-4">📭</div>
            <h3 class="text-2xl font-bold text-text-primary mb-2">No hay publicaciones aún</h3>
            <p class="text-text-muted mb-6">Sé el primero en crear una publicación</p>

            @auth
                <a href="{{ route('posts.create') }}"
                   class="inline-block bg-primary-200 text-dark px-8 py-3 rounded-lg hover:bg-primary-300 transition font-semibold">
                    ➕ Crear publicación
                </a>
            @endauth
        </div>
    @endif

</div>

@endsection
