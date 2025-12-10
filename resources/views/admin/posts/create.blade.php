@extends('layouts.app')

@section('title', 'Crear Post')

@section('content')
<div class="max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Crear Nuevo Post</h1>

    @if ($errors->any())
        <x-alert type="danger">
            <x-slot name="title">Errores:</x-slot>
            <ul class="list-disc pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-input label="Título" for="title" name="title" type="text" required />

        <x-textarea label="Contenido" for="content" name="content">{{ old('content') }}</x-textarea>

        <x-input label="Fecha del Post" for="post_date" name="post_date" type="date" />

        <x-input label="Portada (WebP)" for="cover" name="cover" type="file" accept="image/*" />

        <x-button type="submit">Crear Post</x-button>
    </form>

</div>
@endsection
