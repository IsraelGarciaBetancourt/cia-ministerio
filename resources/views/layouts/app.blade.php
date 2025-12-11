<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'CIA - Ministerio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    @stack('css')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    
    {{-- HEADER --}}
    <header class="bg-white shadow-md sticky top-0 z-40">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                {{-- Logo / Nombre --}}
                <div class="flex items-center">
                    <a href="{{ route('blog.index') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">C</span>
                        </div>
                        <span class="text-xl font-bold text-gray-800">CIA Ministerio</span>
                    </a>
                </div>

                {{-- Navegación --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('blog.index') }}" 
                       class="text-gray-700 hover:text-blue-600 transition font-medium {{ request()->routeIs('blog.*') ? 'text-blue-600' : '' }}">
                        📰 Blog
                    </a>
                    
                    @auth
                        <a href="{{ route('posts.index') }}" 
                           class="text-gray-700 hover:text-blue-600 transition font-medium {{ request()->routeIs('posts.*') && !request()->routeIs('posts.show') ? 'text-blue-600' : '' }}">
                            ⚙️ Admin
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 transition font-medium">
                                🚪 Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" 
                           class="text-gray-700 hover:text-blue-600 transition font-medium">
                            🔐 Iniciar Sesión
                        </a>
                    @endauth
                </div>

                {{-- Menú móvil (hamburguesa) --}}
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-gray-700 hover:text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        {{-- Menú móvil desplegable --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ route('blog.index') }}" 
                   class="block text-gray-700 hover:text-blue-600 transition font-medium">
                    📰 Blog
                </a>
                
                @auth
                    <a href="{{ route('posts.index') }}" 
                       class="block text-gray-700 hover:text-blue-600 transition font-medium">
                        ⚙️ Admin
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left text-gray-700 hover:text-red-600 transition font-medium">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       class="block text-gray-700 hover:text-blue-600 transition font-medium">
                        🔐 Iniciar Sesión
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Columna 1: Acerca de --}}
                <div>
                    <h3 class="text-lg font-bold mb-3">CIA Ministerio</h3>
                    <p class="text-gray-400 text-sm">
                        Comparte nuestras noticias y eventos con la comunidad.
                    </p>
                </div>

                {{-- Columna 2: Enlaces rápidos --}}
                <div>
                    <h3 class="text-lg font-bold mb-3">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('blog.index') }}" class="text-gray-400 hover:text-white transition">
                                Blog
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('posts.index') }}" class="text-gray-400 hover:text-white transition">
                                    Panel de Administración
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>

                {{-- Columna 3: Contacto --}}
                <div>
                    <h3 class="text-lg font-bold mb-3">Contacto</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📧 contacto@ciaministerio.com</li>
                        <li>📍 Paccha, Junín, PE</li>
                    </ul>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} CIA Ministerio. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- Script para menú móvil --}}
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    @stack('scripts')
</body>
</html>