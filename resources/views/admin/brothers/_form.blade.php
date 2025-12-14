@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-medium mb-1">Nombre *</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $brother->name ?? '') }}"
            required
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >
    </div>

    {{-- Apellido --}}
    <div>
        <label class="block text-sm font-medium mb-1">Apellido</label>
        <input
            type="text"
            name="lastname"
            value="{{ old('lastname', $brother->lastname ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >
    </div>

    {{-- Teléfono --}}
    <div>
        <label class="block text-sm font-medium mb-1">Teléfono</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $brother->phone ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $brother->email ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >
    </div>

    {{-- Zona --}}
    <div>
        <label class="block text-sm font-medium mb-1">Zona</label>
        <input
            type="text"
            name="zona"
            value="{{ old('zona', $brother->zona ?? '') }}"
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >
    </div>

    {{-- Notas --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium mb-1">Notas</label>
        <textarea
            name="notes"
            rows="3"
            class="w-full rounded-lg border-gray-300 focus:border-gold-400 focus:ring-gold-400"
        >{{ old('notes', $brother->notes ?? '') }}</textarea>
    </div>

    {{-- Activo --}}
    <div class="md:col-span-2 flex items-center gap-3">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $brother->is_active ?? true) ? 'checked' : '' }}
            class="rounded border-gray-300 text-gold-500 focus:ring-gold-400"
        >
        <span class="text-sm">Hermano activo</span>
    </div>
</div>

<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('brothers.index') }}"
       class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
        Cancelar
    </a>

    <button
        type="submit"
        class="px-5 py-2 rounded-lg bg-gold-500 text-dark font-semibold hover:bg-gold-400 transition">
        Guardar
    </button>
</div>
