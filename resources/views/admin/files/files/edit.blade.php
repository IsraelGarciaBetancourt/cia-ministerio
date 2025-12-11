@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-6 shadow rounded-lg">
    <h1 class="text-2xl font-bold mb-6">Editar archivo</h1>

    <form action="{{ route('files.groups.categories.files.update', [$group, $category, $file]) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">

        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold">Título</label>
            <input type="text" name="title" value="{{ $file->title }}" required
                   class="w-full border rounded-lg p-2">
        </div>

        <div>
            <label class="block text-sm font-semibold">Descripción</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded-lg p-2">{{ $file->description }}</textarea>
        </div>

        <div>
            <h2 class="font-semibold text-sm mb-2">Adjuntos actuales</h2>

            @foreach($file->attachments as $att)
                <div class="flex items-center justify-between bg-gray-50 border p-2 rounded mb-2">
                    <div>
                        <p class="font-medium">{{ $att->original_filename }}</p>
                        <p class="text-xs text-gray-500">{{ $att->mime_type }}</p>
                    </div>

                    <label class="text-red-600 cursor-pointer text-sm">
                        <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}" class="mr-1">
                        Eliminar
                    </label>
                </div>
            @endforeach
        </div>

        <div>
            <label class="block text-sm font-semibold">Agregar nuevos adjuntos</label>
            <input type="file" name="attachments[]" multiple
                   class="w-full border rounded-lg p-2">
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('files.groups.categories.files.show', [$group, $category, $file]) }}"
               class="px-4 py-2 border rounded-lg">Cancelar</a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Guardar cambios
            </button>
        </div>

    </form>
</div>

@endsection
