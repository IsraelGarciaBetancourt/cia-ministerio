@extends('layouts.admin')

@section('title', 'Finanzas')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-dark">Finanzas</h1>
        <p class="text-sm text-gray-500">
            Gestión de diezmos, ofrendas y eventos
        </p>
    </div>

    <a href="{{ route('finance-types.index') }}"
       class="inline-flex items-center gap-2 bg-primary-300 text-dark px-5 py-3 rounded-xl shadow hover:brightness-95 transition">
        Tipos de Finanzas
    </a>

    <a href="{{ route('finances.create') }}"
       class="inline-flex items-center gap-2 bg-primary-300 text-dark px-5 py-3 rounded-xl shadow hover:brightness-95 transition">
        ➕ Crear Finanza
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Total de Finanzas</p>
        <p class="text-3xl font-bold text-dark">
            {{ $finances->total() }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Finanzas Abiertas</p>
        <p class="text-3xl font-bold text-green-600">
            {{ $finances->where('is_closed', false)->count() }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Finanzas Cerradas</p>
        <p class="text-3xl font-bold text-red-600">
            {{ $finances->where('is_closed', true)->count() }}
        </p>
    </div>
</div>

{{-- Listado --}}
<div class="bg-white rounded-xl shadow overflow-hidden">

    @if($finances->count())

    {{-- Tabla desktop --}}
    <div class="hidden md:block">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="text-left px-6 py-4">Título</th>
                    <th class="text-left px-6 py-4">Fecha</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $finance)
                    <tr class="border-t">
                        <td class="px-6 py-4 font-medium text-dark">
                            {{ $finance->title }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $finance->finance_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($finance->is_closed)
                                <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    Cerrada
                                </span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    Abierta
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('finances.show', $finance) }}"
                               class="text-primary-300 font-medium hover:underline">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cards mobile --}}
    <div class="md:hidden divide-y">
        @foreach($finances as $finance)
            <div class="p-5">
                <h3 class="font-semibold text-dark">
                    {{ $finance->title }}
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $finance->finance_date->format('d/m/Y') }}
                </p>

                <div class="mt-3 flex items-center justify-between">
                    @if($finance->is_closed)
                        <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">
                            Cerrada
                        </span>
                    @else
                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                            Abierta
                        </span>
                    @endif

                    <a href="{{ route('finances.show', $finance) }}"
                       class="text-primary-300 font-medium">
                        Ver →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @else
        <div class="p-12 text-center">
            <p class="text-gray-500 mb-4">
                No hay finanzas registradas aún
            </p>
            <a href="{{ route('finances.create') }}"
               class="inline-flex items-center gap-2 bg-primary-300 text-dark px-6 py-3 rounded-xl shadow">
                ➕ Crear primera finanza
            </a>
        </div>
    @endif

</div>

<div class="mt-6">
    {{ $finances->links() }}
</div>

@endsection
