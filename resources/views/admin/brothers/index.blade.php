@extends('layouts.admin')

@section('title', 'Hermanos')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-dark">Hermanos</h1>
        <p class="text-sm text-gray-500">
            Personas registradas para diezmos
        </p>
    </div>

    <a href="{{ route('brothers.create') }}"
       class="inline-flex items-center gap-2 bg-primary-300 text-dark px-5 py-3 rounded-xl shadow hover:brightness-95 transition">
        ➕ Nuevo Hermano
    </a>
</div>

<form method="GET" class="flex flex-col md:flex-row gap-3 mb-6">
    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Buscar por nombre o apellido..."
        class="w-full md:w-80 rounded-lg border border-gray-300 px-4 py-2 focus:ring-gold-500 focus:border-gold-500"
    >

    <button class="bg-primary-300 hover:brightness-95 transition text-white px-5 py-2 rounded-lg">
        Buscar
    </button>

    @if($search)
        <a href="{{ route('brothers.index') }}"
           class="px-5 py-2 rounded-lg border">
            Limpiar
        </a>
    @endif
</form>


{{-- Listado --}}
<div class="bg-white rounded-xl shadow overflow-hidden">

    @if($brothers->count())

    {{-- Tabla desktop --}}
    <div class="hidden md:block">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="text-left px-6 py-4">Nombre</th>
                    <th class="text-left px-6 py-4">Teléfono</th>
                    <th class="text-left px-6 py-4">Zona</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brothers as $brother)
                    <tr class="border-t">
                        <td class="px-6 py-4 font-medium text-dark">
                            {{ trim($brother->name . ' ' . $brother->lastname) }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $brother->phone ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $brother->zona ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                        <form method="POST" action="{{ route('brothers.toggle', $brother) }}">
                            @csrf
                            @method('PATCH')

                            <button
                                class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $brother->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $brother->is_active ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>

                        <td class="px-6 py-4 text-right space-x-4">
                            <a href="{{ route('brothers.edit', $brother) }}"
                               class="text-primary-300 hover:underline">
                                Editar
                            </a>

                            @if(!$brother->financeEntries->count())
                                <form method="POST"
                                    action="{{ route('brothers.destroy', $brother) }}"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar este hermano? Esta acción no se puede deshacer.')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:underline text-sm">
                                        Eliminar
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cards mobile (optimizado) --}}
    <div class="md:hidden divide-y">
        @foreach($brothers as $brother)
            <div class="px-4 py-3">

                {{-- Fila superior: Nombre + Estado --}}
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-dark text-sm leading-tight">
                        {{ trim($brother->name . ' ' . $brother->lastname) }}
                    </h3>

                    <form method="POST" action="{{ route('brothers.toggle', $brother) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            class="px-2 py-0.5 rounded-full text-[11px] font-medium
                            {{ $brother->is_active
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $brother->is_active ? 'Activo' : 'Inactivo' }}
                        </button>
                    </form>
                </div>

                {{-- Teléfono --}}
                <p class="text-xs text-gray-500 mt-1">
                    📞 {{ $brother->phone ?? 'Sin teléfono' }}
                </p>

                {{-- Acciones --}}
                <div class="mt-2 flex items-center justify-between">
                    <a href="{{ route('brothers.edit', $brother) }}"
                    class="text-gold-600 text-sm font-medium">
                        Editar
                    </a>

                    @if(!$brother->financeEntries->count())
                        <form method="POST"
                            action="{{ route('brothers.destroy', $brother) }}"
                            onsubmit="return confirm('¿Eliminar este hermano? No se puede deshacer.')">
                            @csrf
                            @method('DELETE')

                            <button class="text-xs text-red-600 font-medium">
                                Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @else
        <div class="p-12 text-center">
            <p class="text-gray-500 mb-4">
                No hay hermanos registrados
            </p>
            <a href="{{ route('brothers.create') }}"
               class="inline-flex items-center gap-2 bg-primary-300 text-dark px-6 py-3 rounded-xl shadow">
                ➕ Registrar primer hermano
            </a>
        </div>
    @endif

</div>

<div class="mt-6">
    {{ $brothers->links() }}
</div>

@endsection
