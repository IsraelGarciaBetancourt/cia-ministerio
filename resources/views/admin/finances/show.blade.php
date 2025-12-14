@extends('layouts.admin')

@section('title', $finance->title)

@section('content')

{{-- Header --}}
<div class="flex items-start justify-between gap-4 mb-6">
    <div>
        <a href="{{ route('finances.index') }}" class="text-sm text-gray-500 hover:underline">
            ← Volver a Finanzas
        </a>
        <h1 class="text-2xl font-bold text-dark mt-2">{{ $finance->title }}</h1>
        <p class="text-sm text-gray-500">{{ $finance->finance_date?->format('d/m/Y') }}</p>
    </div>

    <div class="flex items-center gap-2">
        @if($finance->is_closed)
            <form method="POST" action="{{ route('finances.reopen', $finance) }}">
                @csrf @method('PATCH')
                <button class="px-4 py-2 rounded-xl bg-green-100 text-green-700 font-medium hover:brightness-95">
                    Abrir
                </button>
            </form>
        @else
            <form method="POST" action="{{ route('finances.close', $finance) }}"
                  onsubmit="return confirm('¿Cerrar esta finanza? No se podrán registrar más movimientos.')">
                @csrf @method('PATCH')
                <button class="px-4 py-2 rounded-xl bg-red-100 text-red-700 font-medium hover:brightness-95">
                    Cerrar
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Resumen de totales --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

    {{-- Total general --}}
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Total Recaudado</p>
        <p class="text-3xl font-bold text-dark">
            S/. {{ number_format($finance->total_amount, 2) }}
        </p>
    </div>

    {{-- Totales por tipo --}}
    @foreach($totalsByType as $typeName => $sum)
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-sm text-gray-500">Total {{ $typeName }}</p>
            <p class="text-2xl font-bold text-dark">
                S/. {{ number_format($sum, 2) }}
            </p>
        </div>
    @endforeach

</div>


{{-- Formulario de movimientos --}}
@if(!$finance->is_closed)
<div class="bg-white rounded-xl shadow p-6 mb-10">
    <h2 class="font-semibold text-dark mb-4">
        Registrar Movimiento
    </h2>

    <form method="POST" action="{{ route('finances.entries.store', $finance) }}"
          class="grid grid-cols-1 md:grid-cols-6 gap-4">
        @csrf

        {{-- Tipo --}}
        <div class="md:col-span-2">
            <label class="text-sm font-medium">Tipo</label>
            <select name="finance_type_id" required
                    class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                @foreach($types as $type)
                    <option
                        value="{{ $type->id }}"
                        data-requires-brother="{{ $type->requires_brother ? 1 : 0 }}"
                    >
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Hermano (solo si aplica) --}}
        <div class="md:col-span-2">
            {{-- Combobox Hermano --}}
            <div class="md:col-span-2">
                <label class="text-sm font-medium">Hermano (si aplica)</label>

                <div class="relative mt-1" x-data="brotherCombo({{ $brothers->toJson() }})">
                    <input type="text"
                        x-model="query"
                        @input="open = true; selectedId = null"
                        @focus="open = true"
                        @keydown.escape="open = false"
                        placeholder="Buscar hermano..."
                        class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300" />

                    <input type="hidden" name="brother_id" :value="selectedId">

                    <div x-show="open"
                        @click.outside="open=false"
                        class="absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow max-h-56 overflow-auto">
                        <template x-for="b in filtered()" :key="b.id">
                            <button type="button"
                                    @click="select(b)"
                                    class="w-full text-left px-4 py-2 hover:bg-gray-50">
                                <div class="text-sm font-medium text-dark" x-text="b.full_name"></div>
                            </button>
                        </template>

                        <div x-show="filtered().length === 0" class="px-4 py-3 text-sm text-gray-500">
                            Sin resultados…
                        </div>
                    </div>
                </div>

                @error('brother_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

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

        </div>

        {{-- Monto --}}
        <div class="md:col-span-1">
            <label class="text-sm font-medium">Monto</label>
            <input type="number" step="0.01" name="amount" required
                   class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
        </div>

        {{-- Nota --}}
        <div class="md:col-span-6">
            <label class="text-sm font-medium">Notas (opcional)</label>
            <input type="text" name="notes"
                   class="w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
        </div>

        {{-- Botón --}}
        <div class="md:col-span-6 flex justify-end">
            <button
                class="bg-primary-300 text-dark px-6 py-2 rounded-xl shadow hover:brightness-95">
                Registrar
            </button>
        </div>
    </form>
</div>
@endif

{{-- Listado de movimientos --}}
<div class="bg-white rounded-xl shadow overflow-hidden">

    <h2 class="font-semibold text-dark px-6 py-4 border-b">
        Movimientos
    </h2>

    @if($finance->entries->count())

    {{-- Movimientos Desktop --}}
    <div class="hidden md:block">

        @forelse($entriesByType as $typeName => $entries)

            {{-- Header del tipo --}}
            <div class="px-6 py-3 bg-gray-100 border-t font-semibold text-dark">
                {{ $typeName }}
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-6 py-3">Persona</th>
                        <th class="text-right px-6 py-3">Monto</th>
                        <th class="text-left px-6 py-3">Notas</th>
                        <th class="text-right px-6 py-3">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($entries as $entry)
                        <tr class="border-t">
                            <td class="px-6 py-4">
                                {{ $entry->brother?->full_name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium">
                                {{ $entry->amount_formatted }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $entry->notes ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if(!$finance->is_closed)
                                    <form method="POST"
                                        action="{{ route('finances.entries.destroy', $entry) }}"
                                        onsubmit="return confirm('¿Eliminar este movimiento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400">Cerrado</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        @empty
            <p class="p-6 text-gray-500">No hay movimientos registrados.</p>
        @endforelse

    </div>

    {{-- Movimientos Mobile --}}
    <div class="md:hidden divide-y">

        @forelse($entriesByType as $typeName => $entries)

            {{-- Header del tipo --}}
            <div class="px-4 py-2 bg-gray-100 text-sm font-semibold">
                {{ $typeName }}
            </div>

            @foreach($entries as $entry)
                <div class="px-4 py-3">

                    <div class="flex justify-between items-center">
                        <p class="font-medium text-sm">
                            {{ $entry->brother?->full_name ?? 'General' }}
                        </p>

                        <p class="font-semibold text-sm">
                            {{ $entry->amount_formatted }}
                        </p>
                    </div>

                    @if($entry->notes)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $entry->notes }}
                        </p>
                    @endif

                    <div class="mt-2 text-right">
                        @if(!$finance->is_closed)
                            <form method="POST"
                                action="{{ route('finances.entries.destroy', $entry) }}"
                                onsubmit="return confirm('¿Eliminar este movimiento?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-600">
                                    Eliminar
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400">Cerrado</span>
                        @endif
                    </div>

                </div>
            @endforeach

        @empty
            <p class="p-4 text-gray-500 text-sm">
                No hay movimientos registrados.
            </p>
        @endforelse

    </div>

    @else
        <div class="p-8 text-center text-gray-500">
            No hay movimientos registrados aún
        </div>
    @endif
</div>

@endsection
