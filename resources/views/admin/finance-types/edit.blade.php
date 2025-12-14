@extends('layouts.admin')

@section('title', 'Editar Tipo de Finanza')

@section('content')

<div class="max-w-3xl">

    <h1 class="text-2xl font-bold text-dark mb-6">Editar Tipo de Finanza</h1>

    <form method="POST" action="{{ route('finance-types.update', $type) }}"
          class="bg-white rounded-xl shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="md:col-span-2">
                <label class="text-sm font-medium text-dark">Nombre *</label>
                <input name="name" value="{{ old('name', $type->name) }}" required
                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Slug actual: <b>{{ $type->slug }}</b> (se ajusta si cambias el nombre)</p>
            </div>

            <div>
                <label class="text-sm font-medium text-dark">Icono (opcional)</label>
                <input name="icon" value="{{ old('icon', $type->icon) }}" placeholder="💰"
                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
            </div>

            <div>
                <label class="text-sm font-medium text-dark">Color (opcional)</label>
                <input name="color" value="{{ old('color', $type->color) }}" placeholder="blue"
                       class="mt-1 w-full rounded-xl border-gray-300 focus:border-primary-300 focus:ring-primary-300">
            </div>

            <div class="md:col-span-2 space-y-3 pt-2">
                <label class="flex items-center gap-3">
                    <input type="checkbox" name="requires_brother" value="1"
                           {{ old('requires_brother', $type->requires_brother) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-300 focus:ring-primary-300">
                    <span class="text-sm text-dark">Requiere hermano</span>
                </label>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="allows_multiple" value="1"
                           {{ old('allows_multiple', $type->allows_multiple) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-300 focus:ring-primary-300">
                    <span class="text-sm text-dark">Permite múltiples registros</span>
                </label>

                <label class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $type->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary-300 focus:ring-primary-300">
                    <span class="text-sm text-dark">Activo</span>
                </label>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('finance-types.index') }}"
               class="px-5 py-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button class="px-6 py-3 rounded-xl bg-primary-300 text-dark shadow hover:brightness-95 transition">
                Guardar
            </button>
        </div>
    </form>

</div>

@endsection
