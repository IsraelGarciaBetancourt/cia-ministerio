<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CIA - Ministerio')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('css')
</head>

<body class="bg-base text-text-primary min-h-screen flex flex-col">

    {{-- ===================================================== --}}
    {{--                     HEADER (FIGMA)                    --}}
    {{-- ===================================================== --}}

    <header class="sticky top-0 z-40 bg-[#0D0703]">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

            {{-- LOGO + NOMBRE --}}
            <a href="{{ route('landing.inicio') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto" alt="CIA Ministerio">

                <span class="text-xl font-semibold text-white">
                    CIA Ministerio
                </span>
            </a>

            {{-- NAV DESKTOP (como Figma: alineado derecha) --}}
            <div class="hidden md:flex items-center gap-10 text-lg font-semibold">

                {{-- Inicio --}}
                <a href="{{ route('landing.inicio') }}"
                   class="transition 
                          {{ request()->routeIs('landing.*') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Inicio
                </a>

                {{-- Blog --}}
                <a href="{{ route('blog.index') }}"
                   class="transition 
                          {{ request()->routeIs('blog.*') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Blog
                </a>

                {{-- Sobre Nosotros --}}
                <a href="{{ url('/about') }}"
                   class="transition 
                          {{ request()->is('about') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Sobre Nosotros
                </a>

                @auth
                    <a href="{{ route('posts.index') }}"
                       class="text-sm transition text-white hover:text-[#F2CB05]">
                        Admin
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="ml-4 text-sm text-white hover:text-red-400 transition">
                            Cerrar Sesión
                        </button>
                    </form>
                @endauth
            </div>

            {{-- BOTÓN MENÚ MÓVIL --}}
            <button id="mobile-menu-btn" class="md:hidden text-white hover:text-[#F2CB05] transition">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

        </nav>

        {{-- NAV MÓVIL --}}
        <div id="mobile-menu" class="hidden md:hidden bg-[#0D0703] border-t border-white/10">
            <div class="px-4 py-4 space-y-3 text-base font-semibold">

                <a href="{{ route('landing.inicio') }}"
                   class="block transition 
                          {{ request()->routeIs('landing.*') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Inicio
                </a>

                <a href="{{ route('blog.index') }}"
                   class="block transition 
                          {{ request()->routeIs('blog.*') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Blog
                </a>

                <a href="{{ url('/about') }}"
                   class="block transition 
                          {{ request()->is('about') 
                                ? 'text-[#F2CB05]' 
                                : 'text-white hover:text-[#F2CB05]' }}">
                    Sobre Nosotros
                </a>

                @auth
                    <a href="{{ route('posts.index') }}"
                       class="block text-sm text-white hover:text-[#F2CB05] transition">
                        Admin
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full text-left text-sm text-white hover:text-red-400 transition">
                            Cerrar Sesión
                        </button>
                    </form>
                @endauth

            </div>
        </div>
    </header>


    {{-- ===================================================== --}}
    {{--                    MAIN CONTENT                      --}}
    {{-- ===================================================== --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- ===================================================== --}}
    {{--                        FOOTER (FIGMA)                 --}}
    {{-- ===================================================== --}}
    <footer class="bg-cia-dark text-cia-light mt-20 pt-12 pb-8">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">

                {{-- ABOUT --}}
                <div>
                    <h3 class="text-lg font-bold text-gold-300 mb-3">CIA Ministerio</h3>
                    <p class="text-cia-light/70 text-sm">
                        Comparte nuestras noticias y eventos con la comunidad.
                    </p>
                </div>

                {{-- LINKS --}}
                <div>
                    <h3 class="text-lg font-bold text-gold-300 mb-3">Enlaces</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ url('/') }}" class="text-cia-light/70 hover:text-gold-300 transition">Inicio</a>
                        </li>
                        <li>
                            <a href="{{ route('blog.index') }}" class="text-cia-light/70 hover:text-gold-300 transition">Blog</a>
                        </li>
                        <li>
                            <a href="{{ url('/about') }}" class="text-cia-light/70 hover:text-gold-300 transition">Sobre Nosotros</a>
                        </li>
                    </ul>
                </div>

                {{-- CONTACT --}}
                <div>
                    <h3 class="text-lg font-bold text-gold-300 mb-3">Contacto</h3>
                    <ul class="space-y-2 text-sm text-cia-light/70">
                        <li>📧 contacto@ciaministerio.com</li>
                        <li>📍 Paccha, Junín, PE</li>
                    </ul>
                </div>

            </div>

            {{-- COPYRIGHT --}}
            <div class="border-t border-gold-300/20 mt-10 pt-6 text-center text-sm text-cia-light/60">
                &copy; {{ date('Y') }} CIA Ministerio — Todos los derechos reservados.
            </div>

        </div>
    </footer>

    {{-- MOBILE MENU SCRIPT --}}
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        btn?.addEventListener('click', () => menu.classList.toggle('hidden'));
    </script>

    @stack('scripts')
</body>
</html>
