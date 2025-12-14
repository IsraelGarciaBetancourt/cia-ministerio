@extends('layouts.admin')

@section('title', $finance->title)

@section('content')

{{-- Breadcrumb y Header --}}
<div class="mb-6">
    <a href="{{ route('finances.index') }}" 
       class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary-300 transition mb-3">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Volver a Finanzas
    </a>
    
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-dark">{{ $finance->title }}</h1>
            <div class="flex items-center gap-3 mt-2">
                <p class="text-sm text-gray-600">
                    📅 {{ $finance->finance_date?->format('d/m/Y') }}
                </p>
                @if($finance->is_closed)
                    <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full font-semibold">
                        🔒 Cerrada
                    </span>
                @else
                    <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">
                        🔓 Abierta
                    </span>
                @endif
            </div>
            @if($finance->description)
                <p class="text-sm text-gray-500 mt-2">{{ $finance->description }}</p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if($finance->is_closed)
                <form method="POST" action="{{ route('finances.reopen', $finance) }}">
                    @csrf @method('PATCH')
                    <button class="px-5 py-3 rounded-xl bg-green-100 text-green-700 font-semibold hover:bg-green-200 transition shadow-sm">
                        🔓 Reabrir Finanza
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('finances.close', $finance) }}"
                      onsubmit="return confirm('¿Cerrar esta finanza? No se podrán registrar más movimientos.')">
                    @csrf @method('PATCH')
                    <button class="px-5 py-3 rounded-xl bg-red-100 text-red-700 font-semibold hover:bg-red-200 transition shadow-sm">
                        🔒 Cerrar Finanza
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- Resumen de totales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Total general (destacado) --}}
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white lg:col-span-2">
        <p class="text-sm opacity-90 mb-1">💰 Total Recaudado</p>
        <p class="text-4xl font-bold">
            S/. {{ number_format($finance->total_amount, 2) }}
        </p>
        <p class="text-xs opacity-75 mt-2">
            {{ $finance->entries->count() }} {{ $finance->entries->count() == 1 ? 'movimiento' : 'movimientos' }}
        </p>
    </div>

    {{-- Totales por tipo --}}
    @foreach($totalsByType as $typeName => $sum)
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-primary-300">
            <p class="text-xs text-gray-500 mb-1">{{ $typeName }}</p>
            <p class="text-2xl font-bold text-dark">
                S/. {{ number_format($sum, 2) }}
            </p>
        </div>
    @endforeach

</div>


{{-- Formulario de movimientos --}}
@if(!$finance->is_closed)
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 mb-8 border border-blue-200">
    <h2 class="font-bold text-dark mb-4 text-lg flex items-center gap-2">
        ➕ Registrar Nuevo Movimiento
    </h2>

    <form method="POST" action="{{ route('finances.entries.store', $finance) }}"
          class="grid grid-cols-1 md:grid-cols-12 gap-4">
        @csrf

        {{-- Tipo --}}
        <div class="md:col-span-3">
            <label class="text-sm font-medium text-gray-700">Tipo *</label>
            <select name="finance_type_id" required
                    class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @foreach($types as $type)
                    <option
                        value="{{ $type->id }}"
                        data-requires-brother="{{ $type->requires_brother ? 1 : 0 }}"
                    >
                        {{ $type->icon ?? '💰' }} {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Hermano (combobox) --}}
        <div class="md:col-span-4">
            <label class="text-sm font-medium text-gray-700">Hermano (si aplica)</label>

            <div class="relative mt-1" x-data="brotherCombo({{ $brothers->toJson() }})">
                <input type="text"
                    x-model="query"
                    @input="open = true; selectedId = null"
                    @focus="open = true"
                    @keydown.escape="open = false"
                    placeholder="🔍 Buscar hermano..."
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" />

                <input type="hidden" name="brother_id" :value="selectedId">

                <div x-show="open"
                    @click.outside="open=false"
                    x-transition
                    style="display: none;"
                    class="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-56 overflow-auto">
                    <template x-for="b in filtered()" :key="b.id">
                        <button type="button"
                                @click="select(b)"
                                class="w-full text-left px-4 py-2 hover:bg-blue-50 transition">
                            <div class="text-sm font-medium text-dark" x-text="b.full_name"></div>
                        </button>
                    </template>

                    <div x-show="filtered().length === 0" class="px-4 py-3 text-sm text-gray-500">
                        Sin resultados...
                    </div>
                </div>
            </div>

            @error('brother_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Monto --}}
        <div class="md:col-span-2">
            <label class="text-sm font-medium text-gray-700">Monto *</label>
            <input type="number" step="0.01" name="amount" required
                   placeholder="0.00"
                   class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Nota --}}
        <div class="md:col-span-12">
            <label class="text-sm font-medium text-gray-700">Notas (opcional)</label>
            <input type="text" name="notes"
                   placeholder="Agrega una nota descriptiva..."
                   class="w-full mt-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        {{-- Botón --}}
        <div class="md:col-span-12 flex justify-end">
            <button
                class="bg-primary-300 text-dark px-8 py-3 rounded-xl shadow-md hover:brightness-95 transition font-semibold">
                ✓ Registrar Movimiento
            </button>
        </div>
    </form>
</div>
@else
    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 mb-8 text-center">
        <div class="text-4xl mb-2">🔒</div>
        <p class="text-gray-600 font-medium">Esta finanza está cerrada</p>
        <p class="text-sm text-gray-500 mt-1">No se pueden agregar más movimientos</p>
    </div>
@endif

{{-- Listado de movimientos --}}
<div class="bg-white rounded-xl shadow-lg overflow-hidden">

    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b flex items-center justify-between">
        <h2 class="font-bold text-dark text-lg">📋 Movimientos Registrados</h2>
        <span class="text-sm text-gray-600">
            Total: {{ $finance->entries->count() }}
        </span>
    </div>

    @if($finance->entries->count())

    {{-- Movimientos Desktop --}}
    <div class="hidden md:block">

        @forelse($entriesByType as $typeName => $entries)

            {{-- Header del tipo --}}
            <div class="px-6 py-3 bg-gradient-to-r from-primary-300/20 to-primary-300/5 border-t border-primary-300/30 font-bold text-dark flex items-center justify-between">
                <span>{{ $typeName }}</span>
                <span class="text-sm font-normal text-gray-600">
                    {{ $entries->count() }} {{ $entries->count() == 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Persona</th>
                        <th class="text-right px-6 py-3 font-semibold">Monto</th>
                        <th class="text-left px-6 py-3 font-semibold">Notas</th>
                        <th class="text-right px-6 py-3 font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($entries as $entry)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-medium text-dark">
                                    {{ $entry->brother?->full_name ?? '—' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-green-600">
                                    {{ $entry->amount_formatted }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $entry->notes ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if(!$finance->is_closed)
                                    <form method="POST"
                                        action="{{ route('finances.entries.destroy', $entry) }}"
                                        onsubmit="return confirm('¿Eliminar este movimiento?')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 hover:underline text-sm font-medium transition">
                                            🗑️ Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">🔒 Cerrado</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        @empty
            <div class="p-8 text-center text-gray-500">
                <div class="text-4xl mb-2">📭</div>
                <p class="font-medium">No hay movimientos registrados</p>
            </div>
        @endforelse

    </div>

    {{-- Movimientos Mobile --}}
    <div class="md:hidden">

        @forelse($entriesByType as $typeName => $entries)

            {{-- Header del tipo --}}
            <div class="px-4 py-3 bg-primary-300/20 border-t border-primary-300/30 text-sm font-bold text-dark flex items-center justify-between">
                <span>{{ $typeName }}</span>
                <span class="text-xs font-normal text-gray-600">
                    {{ $entries->count() }}
                </span>
            </div>

            <div class="divide-y">
                @foreach($entries as $entry)
                    <div class="px-4 py-4 hover:bg-gray-50 transition">

                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-dark">
                                    {{ $entry->brother?->full_name ?? 'General' }}
                                </p>
                                @if($entry->notes)
                                    <p class="text-xs text-gray-500 mt-1">
                                        💬 {{ $entry->notes }}
                                    </p>
                                @endif
                            </div>

                            <p class="font-bold text-green-600 text-lg ml-3">
                                {{ $entry->amount_formatted }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-400">
                                {{ $entry->created_at->format('d/m/Y H:i') }}
                            </span>
                            
                            @if(!$finance->is_closed)
                                <form method="POST"
                                    action="{{ route('finances.entries.destroy', $entry) }}"
                                    onsubmit="return confirm('¿Eliminar este movimiento?')"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-600 font-medium">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">🔒</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        @empty
            <div class="p-8 text-center text-gray-500">
                <div class="text-4xl mb-2">📭</div>
                <p class="text-sm font-medium">No hay movimientos registrados</p>
            </div>
        @endforelse

    </div>

    @else
        <div class="p-12 text-center">
            <div class="text-6xl mb-4">📭</div>
            <p class="text-gray-600 font-medium mb-2">No hay movimientos registrados aún</p>
            <p class="text-sm text-gray-500">Comienza agregando el primer movimiento arriba</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function brotherCombo(brothers){
    return {
        open: false,
        query: '',
        selectedId: null,
        brothers: brothers,
        filtered(){
            const q = this.query.toLowerCase().trim();
            if(!q) return this.brothers.slice(0, 20);
            return this.brothers.filter(b =>
                (b.full_name || '').toLowerCase().includes(q)
            ).slice(0, 20);
        },
        select(b){
            this.query = b.full_name;
            this.selectedId = b.id;
            this.open = false;
        }
    }
}
</script>
@endpush