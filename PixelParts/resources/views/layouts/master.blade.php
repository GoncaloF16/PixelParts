<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PixelParts - Componentes Gaming de Elite</title>
    <meta name="description" content="Descobre componentes gaming de alta performance. Placas gráficas, processadores, memórias e mais.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        :root { --white-darkmode: #E5E7EB; }
        .text-white { color: var(--white-darkmode) !important; }
        .bg-white { background-color: var(--white-darkmode) !important; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 0 15px rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body class="min-h-screen bg-background pt-[150px] md:pt-0 text-gray-200">

    <!-- Floating Cart Button -->
    <a href="{{ route('cart.index') }}"
       class="fixed bottom-6 right-6 w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300 z-50">
        <i data-lucide="shopping-cart" class="w-7 h-7 text-white"></i>
        <span id="cart-count" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
            {{ count(session('cart', [])) }}
        </span>
    </a>

    <!-- Header -->
    <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-surface/90 backdrop-blur-md">
        <div class="container mx-auto px-6 py-3">
            <div class="flex flex-col md:flex-row items-center justify-between gap-3 md:gap-6">
                <!-- Menu Hambúrguer + Logo -->
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <div class="hidden md:block relative">
                        <button id="menu-toggle" aria-expanded="false" class="p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green">
                            <i data-lucide="menu" class="w-6 h-6 text-gray-200"></i>
                        </button>
                        <div id="menu-dropdown" class="hidden absolute top-full left-0 mt-2 bg-gray-900 rounded-xl shadow-xl w-56 p-2 ring-1 ring-black ring-opacity-20 origin-top-left" role="menu" aria-hidden="true">
                            @php
                                $categorias = ['Processadores','Placas Gráficas','Motherboards','Memória RAM','Armazenamento','Portáteis'];
                            @endphp
                            @foreach ($categorias as $categoria)
                                <a href="{{ route('products.index', ['categoria' => Str::slug($categoria)]) }}"
                                   class="block px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-green transition" role="menuitem" tabindex="0">{{ $categoria }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="logo-hover">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo" class="h-[80px] w-[100px] object-contain">
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <form action="{{ route('products.index') }}" method="GET" class="flex-grow w-full md:max-w-xl md:ml-2 mx-0 md:mx-4">
                    <div class="relative p-[1px] rounded-lg bg-gradient-to-r from-brand-green to-brand-blue">
                        <input type="text" name="q" id="searchInput" placeholder="Pesquisar produtos..." class="w-full px-4 py-2 bg-surface rounded-lg text-gray-200 placeholder-gray-400 focus:outline-none text-sm" value="{{ request('q') }}">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-green text-sm">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-4 md:space-x-8 mt-2 md:mt-0 pr-2">
                    @guest
                        <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md font-semibold text-sm flex items-center gap-2 transition-transform duration-300">
                            <i data-lucide="user" class="w-4 h-4"></i> Entrar
                        </a>
                    @endguest
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="flex items-center m-0 p-0 ml-2">
                            @csrf
                            <button type="submit" class="inline-flex items-center bg-red-600 text-white px-4 py-2 rounded-md font-semibold text-sm hover:bg-red-700 transition-colors duration-300">
                                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
                            </button>
                        </form>
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-200 mt-2">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <nav id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-border">
                <div class="flex flex-col space-y-4 pt-4">
                        <h4 class="text-gray-200 font-medium mb-2">Categorias</h4>
                        @foreach ($categorias as $categoria)
                            <a href="{{ route('products.index', ['categoria' => Str::slug($categoria)]) }}" class="block text-gray-400 hover:text-brand-green transition-colors duration-300 py-1">{{ $categoria }}</a>
                        @endforeach
                    <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md font-bold text-lg flex items-center gap-2 transition-transform duration-300">
                        <i data-lucide="user" class="w-4 h-4"></i> Entrar
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-32">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-surface-card border-t border-border py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info, Quick Links, Categories, Contact... -->
                <!-- Mantive o mesmo do teu código original -->
            </div>
            <div class="border-t border-gray-700 border-border pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">© 2024 PixelParts. Todos os direitos reservados.</p>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-brand-green transition-colors duration-300">Política de Privacidade</a>
                    <a href="#" class="text-gray-400 hover:text-brand-green transition-colors duration-300">Termos de Serviço</a>
                    <a href="#" class="text-gray-400 hover:text-brand-green transition-colors duration-300">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Include your master JS -->
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
