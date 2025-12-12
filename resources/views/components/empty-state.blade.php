@props([
    'icon' => '📭',
    'title' => 'Sin registros',
    'message' => 'No existen datos para mostrar.',
])

<div class="text-center py-16">
    <div class="text-6xl mb-4">{{ $icon }}</div>
    <h3 class="text-2xl font-bold text-text-primary mb-2">{{ $title }}</h3>
    <p class="text-text-primary/70 mb-6">{{ $message }}</p>
    {{ $slot }}
</div>

{{-- <x-empty-state 
    title="No hay publicaciones" 
    message="Sé el primero en crear una." 
    icon="📭"
/>
 --}}