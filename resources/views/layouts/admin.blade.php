<!DOCTYPE html>
<html lang="es">

<head>

    <x-fav-icons />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin — CIA')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>

<body class="bg-light text-dark md:flex">

    {{-- ================================================================================= --}}
    {{-- BOTÓN HAMBURGUESA (SÓLO MÓVIL) --}}
    {{-- ================================================================================= --}}
    <button id="openSidebar"
        class="md:hidden fixed top-4 left-4 z-50 bg-primary-300 text-dark rounded-lg p-2 shadow">
        ☰
    </button>

    {{-- ================================================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ================================================================================= --}}
    <aside id="sidebar"
        class="w-64 bg-dark text-light h-screen
            fixed md:static md:sticky top-0 left-0  
            {{-- Cambié sm:static por md:static --}}
            -translate-x-full md:translate-x-0
            transition-transform duration-300 z-40 shadow-xl">

        {{-- HEADER LOGO --}}
        <div class="flex items-center gap-3 px-6 h-20 border-b border-white/10">
            <img src="{{ asset('images/logo.webp') }}" class="h-12" alt="Logo">
            <span class="text-lg font-semibold">CIA Admin</span>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <a href="{{ route('posts.index') }}"
                class="block px-4 py-3 rounded-lg transition font-medium
            {{ request()->routeIs('posts.*') ? 'bg-primary-300 text-dark' : 'text-light hover:bg-white/10' }}">
                📰 Posts
            </a>

            <a href="{{ route('files.groups.index') }}"
                class="block px-4 py-3 rounded-lg transition font-medium
            {{ request()->routeIs('files.*') ? 'bg-primary-300 text-dark' : 'text-light hover:bg-white/10' }}">
                📂 Archivos Privados
            </a>

            <a href="{{ route('finances.index') }}"
                class="block px-4 py-3 rounded-lg transition font-medium
            {{ request()->routeIs('finances.*') ? 'bg-primary-300 text-dark' : 'text-light hover:bg-white/10' }}">
                💰 Finanzas
            </a>

            <a href="{{ route('brothers.index') }}"
                class="block px-4 py-3 rounded-lg transition font-medium
            {{ request()->routeIs('brothers.*') ? 'bg-primary-300 text-dark' : 'text-light hover:bg-white/10' }}">
                👤 Hermanos
            </a>
        </nav>

        {{-- LOGOUT --}}
        <div class="px-4 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 transition text-white font-semibold">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ================================================================================= --}}
    {{-- OVERLAY RESPONSIVE --}}
    {{-- ================================================================================= --}}
    <div id="overlay"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-30 md:hidden">
    </div>

    {{-- ================================================================================= --}}
    {{-- CONTENIDO --}}
    {{-- ================================================================================= --}}
    <main class="flex-1 p-6 md:p-10 min-h-screen transition-all">

        @hasSection('title-section')
            <h1 class="text-2xl font-bold mb-6">@yield('title-section')</h1>
        @endif

        @yield('content')
    </main>

    {{-- ================================================================================= --}}
    {{-- SCRIPT DEL SIDEBAR --}}
    {{-- ================================================================================= --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');

        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>

    @stack('scripts')
</body>

</html>
