@extends('layouts.admin')

@section('title', 'Finanzas')

@section('content')

{{-- Header --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-dark">💰 Finanzas</h1>
        <p class="text-sm text-gray-500">
            Gestión de diezmos, ofrendas y eventos
        </p>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('finance-types.index') }}"
           class="inline-flex items-center gap-2 bg-gray-100 text-dark px-5 py-3 rounded-xl shadow hover:bg-gray-200 transition">
            ⚙️ Tipos
        </a>

        <a href="{{ route('finances.create') }}"
           class="inline-flex items-center gap-2 bg-primary-300 text-dark px-5 py-3 rounded-xl shadow hover:brightness-95 transition">
            ➕ Nueva Finanza
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Total de Finanzas</p>
        <p class="text-3xl font-bold text-dark">
            {{ $finances->total() }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Finanzas Abiertas</p>
        <p class="text-3xl font-bold text-green-600">
            {{ \App\Models\Finance::where('is_closed', false)->count() }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Finanzas Cerradas</p>
        <p class="text-3xl font-bold text-red-600">
            {{ \App\Models\Finance::where('is_closed', true)->count() }}
        </p>
    </div>
</div>

{{-- Barra de búsqueda y filtros --}}
<div class="bg-white rounded-xl shadow p-4 mb-6" x-data="dateFilterModal()">
    <div class="flex flex-col md:flex-row gap-3">
        
        {{-- Buscador --}}
        <form method="GET" action="{{ route('finances.index') }}" class="flex-1 flex gap-2">
            @if(request('month') || request('year'))
                <input type="hidden" name="month" value="{{ request('month') }}">
                <input type="hidden" name="year" value="{{ request('year') }}">
            @endif
            
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar por título..."
                class="flex-1 rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300"
            />

            <button
                type="submit"
                class="px-4 py-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                🔍
            </button>
        </form>

        {{-- Botón filtro de fecha --}}
        <button
            @click="openModal()"
            type="button"
            class="px-5 py-3 bg-blue-100 text-blue-700 rounded-xl shadow hover:bg-blue-200 transition whitespace-nowrap">
            📅 
            @if(request('month') && request('year'))
                {{ \Carbon\Carbon::createFromDate(request('year'), request('month'), 1)->locale('es')->isoFormat('MMMM YYYY') }}
            @else
                Filtrar por fecha
            @endif
        </button>

        {{-- Botón limpiar filtros --}}
        @if(request()->has('search') || request()->has('month') || request()->has('year'))
            <a href="{{ route('finances.index') }}"
               class="px-5 py-3 bg-red-100 text-red-700 rounded-xl shadow hover:bg-red-200 transition whitespace-nowrap inline-flex items-center gap-2">
                ✕ Limpiar
            </a>
        @endif
    </div>

    {{-- Modal de filtro de fecha (inline dentro del x-data) --}}
    <div
        x-show="isOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
        style="display: none;"
        @click="closeModal()">

        <div
            @click.stop
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <h2 class="text-xl font-bold mb-6 text-center">
                📅 Filtrar por Fecha
            </h2>

            <form method="GET" action="{{ route('finances.index') }}" class="space-y-6">

                {{-- Mantener búsqueda si existe --}}
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Selector de Mes y Año Mejorado --}}
                <div class="space-y-4">
                    
                    {{-- Año --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                        <div class="flex items-center gap-3">
                            <button 
                                type="button"
                                @click="changeYear(-1)"
                                class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                ◀
                            </button>
                            
                            <input 
                                type="number" 
                                x-model="selectedYear"
                                name="year"
                                min="2020"
                                max="2030"
                                class="flex-1 text-center text-2xl font-bold border-2 border-gray-200 focus:border-primary-300 focus:ring-primary-300 rounded-lg py-2">
                            
                            <button 
                                type="button"
                                @click="changeYear(1)"
                                class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                ▶
                            </button>
                        </div>
                    </div>

                    {{-- Mes (Grid de botones) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Mes</label>
                        <input type="hidden" name="month" x-model="selectedMonth">
                        
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="(month, index) in months" :key="index">
                                <button
                                    type="button"
                                    @click="selectMonth(index + 1)"
                                    :class="{
                                        'bg-primary-300 text-dark font-semibold shadow-md': selectedMonth == (index + 1),
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200': selectedMonth != (index + 1)
                                    }"
                                    class="py-3 rounded-lg transition text-sm"
                                    x-text="month">
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-blue-600 mb-1">Fecha seleccionada:</p>
                        <p class="text-lg font-bold text-blue-800" x-text="getFormattedDate()"></p>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                        Cancelar
                    </button>

                    <button
                        type="button"
                        @click="clearFilter()"
                        class="px-5 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition">
                        Limpiar
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-primary-300 text-dark rounded-xl hover:brightness-95 transition font-semibold">
                        Aplicar Filtro
                    </button>
                </div>

            </form>
        </div>
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
                    <th class="text-left px-6 py-4">Total</th>
                    <th class="text-left px-6 py-4">Estado</th>
                    <th class="text-right px-6 py-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($finances as $finance)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-dark">
                            {{ $finance->title }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $finance->finance_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 font-semibold text-green-600">
                            {{ $finance->total_formatted }}
                        </td>
                        <td class="px-6 py-4">
                            @if($finance->is_closed)
                                <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">
                                    🔒 Cerrada
                                </span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">
                                    🔓 Abierta
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('finances.show', $finance) }}"
                               class="text-primary-300 font-medium hover:underline">
                                Ver detalles →
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
            <div class="p-5 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-semibold text-dark flex-1">
                        {{ $finance->title }}
                    </h3>
                    @if($finance->is_closed)
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">
                            🔒
                        </span>
                    @else
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
                            🔓
                        </span>
                    @endif
                </div>

                <p class="text-sm text-gray-500">
                    📅 {{ $finance->finance_date->format('d/m/Y') }}
                </p>

                <p class="text-lg font-bold text-green-600 mt-2">
                    {{ $finance->total_formatted }}
                </p>

                <div class="mt-3 text-right">
                    <a href="{{ route('finances.show', $finance) }}"
                       class="text-primary-300 font-medium text-sm">
                        Ver detalles →
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    @else
        <div class="p-12 text-center">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-gray-500 mb-2 font-medium">
                No hay finanzas registradas
            </p>
            <p class="text-sm text-gray-400 mb-6">
                @if(request('search') || request('month') || request('year'))
                    No se encontraron resultados con los filtros aplicados
                @else
                    Crea tu primera finanza para comenzar
                @endif
            </p>
            
            @if(request('search') || request('month') || request('year'))
                <a href="{{ route('finances.index') }}"
                   class="inline-flex items-center gap-2 bg-gray-100 text-dark px-6 py-3 rounded-xl shadow hover:bg-gray-200 transition mr-3">
                    ← Limpiar filtros
                </a>
            @endif
            
            <a href="{{ route('finances.create') }}"
               class="inline-flex items-center gap-2 bg-primary-300 text-dark px-6 py-3 rounded-xl shadow hover:brightness-95 transition">
                ➕ Crear primera finanza
            </a>
        </div>
    @endif

</div>

{{-- Paginación --}}
@if($finances->hasPages())
    <div class="mt-6">
        {{ $finances->links() }}
    </div>
@endif

@endsection

@push('scripts')
<script>
function dateFilterModal() {
    return {
        isOpen: false,
        selectedMonth: {{ request('month', now()->month) }},
        selectedYear: {{ request('year', now()->year) }},
        months: [
            'Enero', 'Febrero', 'Marzo', 'Abril', 
            'Mayo', 'Junio', 'Julio', 'Agosto',
            'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ],

        openModal() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.isOpen = false;
            document.body.style.overflow = 'auto';
        },

        selectMonth(month) {
            this.selectedMonth = month;
        },

        changeYear(delta) {
            this.selectedYear = parseInt(this.selectedYear) + delta;
            if (this.selectedYear < 2020) this.selectedYear = 2020;
            if (this.selectedYear > 2030) this.selectedYear = 2030;
        },

        getFormattedDate() {
            const monthNames = this.months;
            return `${monthNames[this.selectedMonth - 1]} ${this.selectedYear}`;
        },

        clearFilter() {
            window.location.href = '{{ route("finances.index") }}';
        }
    }
}
</script>
@endpush