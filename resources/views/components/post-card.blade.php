@props(['post'])

<x-card class="overflow-hidden">
    <a href="{{ route('blog.show', $post) }}" class="block group">

        {{-- Imagen --}}
        <div class="relative h-48 bg-dark overflow-hidden">
            @if($post->cover_image)
                <img src="{{ asset('storage/' . $post->cover_image) }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="text-5xl text-dark-2">📄</span>
                </div>
            @endif

            {{-- Fecha --}}
            <x-badge class="absolute top-3 right-3" color="dark">
                {{ \Carbon\Carbon::parse($post->post_date)->format('d M Y') }}
            </x-badge>
        </div>

        <div class="p-5">
            <h2 class="text-xl font-semibold text-text-primary mb-2 group-hover:text-gold-400 transition">
                {{ $post->title }}
            </h2>

            <p class="text-text-primary/70 text-sm mb-4 line-clamp-3">
                {{ Str::limit(strip_tags($post->content), 120) }}
            </p>

            <div class="flex justify-between items-center pt-3 border-t border-dark/10">
                <span class="text-gold-500 font-medium">Leer más →</span>

                @if($post->media->count() > 0)
                    <span class="text-dark/50 text-xs flex items-center gap-1">
                        📷 {{ $post->media->count() }}
                    </span>
                @endif
            </div>
        </div>

    </a>
</x-card>
