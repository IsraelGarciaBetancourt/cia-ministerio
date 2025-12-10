@extends('layouts.app')

@section('title', 'Archivos Privados')

@section('content')
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Archivos Privados</h1>

    @if(session('success'))
        <x-alert type="succes">{{ session('success') }}</x-alert>
    @endif

    {{-- Subir archivo --}}
    <form action="{{ route('private-files.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
        @csrf

        <x-input label="Subir archivo" for="file" name="file" type="file" required />

        <x-button type="submit">Subir Archivo</x-button>
    </form>

    {{-- Listado --}}
    <div class="bg-white p-4 rounded shadow">
        <table class="w-full">
            <thead>
                <tr class="border-b font-semibold">
                    <th class="p-2 text-left">Nombre</th>
                    <th class="p-2 text-left">Tipo</th>
                    <th class="p-2 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($files as $file)
                <tr class="border-b">
                    <td class="p-2">{{ $file->file_name }}</td>
                    <td class="p-2">{{ $file->mime_type }}</td>
                    <td class="p-2 text-center">
                        <a href="{{ route('private-files.download', $file) }}" class="text-blue-600 hover:underline">Descargar</a>

                        <form action="{{ route('private-files.destroy', $file) }}" method="POST" class="inline-block ml-2"
                              onsubmit="return confirm('¿Eliminar archivo?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $files->links() }}
        </div>
    </div>

</div>
@endsection
