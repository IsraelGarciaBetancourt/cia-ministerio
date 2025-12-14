@extends('layouts.admin')

@section('title', 'Tipos de Finanza')

@section('content')

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-dark">Tipos de Finanza</h1>
        <p class="text-sm text-gray-500">Configuración de reglas de aportes</p>
    </div>

    <a href="{{ route('finance-types.create') }}"
       class="inline-flex items-center gap-2 bg-primary-300 text-dark px-5 py-3 rounded-xl shadow hover:brightness-95 transition">
        ➕ Nuevo Tipo
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow overflow-hidden">

    @if($types->count())

        {{-- Tabla desktop --}}
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="text-left px-6 py-4">Nombre</th>
                        <th class="text-center px-6 py-4">Requiere Hermano</th>
                        <th class="text-center px-6 py-4">Permite Múltiples</th>
                        <th class="text-center px-6 py-4">Estado</th>
                        <th class="text-right px-6 py-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                        <tr class="border-t">
                            <td class="px-6 py-4 font-medium text-dark">
                                {{ $type->name }}
                                <span class="text-xs text-gray-400 ml-2">({{ $type->slug }})</span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                {!! $type->requires_brother ? '✅' : '❌' !!}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {!! $type->allows_multiple ? '✅' : '❌' !!}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ route('finance-types.toggle', $type) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $type->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $type->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right space-x-4">
                                <a href="{{ route('finance-types.edit', $type) }}"
                                   class="text-primary-300 hover:underline font-medium">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('finance-types.destroy', $type) }}"
                                      class="inline"
                                      onsubmit="return confirm('¿Eliminar este tipo? (Solo se elimina si NO tiene movimientos asociados)')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline font-medium">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Cards mobile --}}
        <div class="md:hidden divide-y">
            @foreach($types as $type)
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-semibold text-dark text-sm truncate">
                                {{ $type->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $type->slug }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('finance-types.toggle', $type) }}">
                            @csrf
                            @method('PATCH')
                            <button class="px-2 py-0.5 rounded-full text-[11px] font-medium
                                {{ $type->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $type->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </div>

                    <div class="mt-2 flex items-center justify-between text-xs text-gray-600">
                        <span>Hermano: {!! $type->requires_brother ? '✅' : '❌' !!}</span>
                        <span>Múltiples: {!! $type->allows_multiple ? '✅' : '❌' !!}</span>
                    </div>

                    <div class="mt-2 flex items-center justify-between">
                        <a href="{{ route('finance-types.edit', $type) }}"
                           class="text-primary-300 text-sm font-medium">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('finance-types.destroy', $type) }}"
                              onsubmit="return confirm('¿Eliminar este tipo? (Solo si NO tiene movimientos)')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 text-sm font-medium">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="p-12 text-center">
            <p class="text-gray-500 mb-4">No hay tipos creados aún</p>
            <a href="{{ route('finance-types.create') }}"
               class="inline-flex items-center gap-2 bg-primary-300 text-dark px-6 py-3 rounded-xl shadow">
                ➕ Crear primer tipo
            </a>
        </div>
    @endif

</div>

<div class="mt-6">
    {{ $types->links() }}
</div>

@endsection
