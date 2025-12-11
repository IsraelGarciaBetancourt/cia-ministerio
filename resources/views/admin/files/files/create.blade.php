@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Nuevo Archivo</h1>

    <form action="{{ route('files.groups.categories.files.store', [$group, $category]) }}"
          method="POST" enctype="multipart/form-data" class="space-y-4">

        @csrf

        <div>
            <label class="block text-sm font-semibold">Título</label>
            <input type="text" name="title" required
                   class="w-full border rounded-lg p-2" value="{{ old('title') }}">
        </div>

        <div>
            <label class="block text-sm font-semibold">Descripción</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded-lg p-2">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold">Archivos adjuntos</label>
            <input type="file" name="attachments[]" multiple
                   class="w-full border rounded-lg p-2">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('files.groups.categories.show', [$group, $category]) }}"
               class="px-4 py-2 border rounded-lg">Cancelar</a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Crear Archivo
            </button>
        </div>

    </form>
</div>

@endsection
