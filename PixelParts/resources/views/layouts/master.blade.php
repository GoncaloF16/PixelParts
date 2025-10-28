<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PixelParts - Componentes Gaming de Elite</title>
    <meta name="description"
        content="Descobre componentes gaming de alta performance. Placas gráficas, processadores, memórias e mais.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/n8n-chat-widget.css') }}">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        :root {
            --white-darkmode: #E5E7EB;
        }

        .text-white {
            color: var(--white-darkmode) !important;
        }

        .bg-white {
            background-color: var(--white-darkmode) !important;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>

<body class="min-h-screen bg-background pt-[150px] md:pt-0 text-gray-200">

    <!-- Header -->
    <header id="header"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-surface/90 backdrop-blur-md">
        <div class="container mx-auto px-6 py-3 flex flex-col md:flex-row items-center justify-between gap-3 md:gap-6">


            <!-- Header com Dropdown e Logo -->
            <div class="flex items-center justify-start space-x-4 md:space-x-6">

                <!-- Desktop Dropdown Menu -->
                <div class="relative">
                    <button id="menu-toggle"
                        class="hidden md:block p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green cursor-pointer">
                        <i data-lucide="menu" class="w-6 h-6 text-gray-200"></i>
                    </button>
                    <div id="menu-dropdown"
                        class="hidden absolute top-full left-0 mt-2 bg-gray-900 rounded-xl shadow-xl w-56 p-2">
                        @php
                            $categorias = [
                                'Processadores',
                                'Placas Gráficas',
                                'Motherboards',
                                'Memória RAM',
                                'Armazenamento',
                                'Portáteis',
                            ];
                        @endphp
                        @foreach ($categorias as $categoria)
                            <a href="{{ route('products.index', ['categoria' => Str::slug($categoria)]) }}"
                                class="block px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-brand-green transition">
                                {{ $categoria }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Logo -->
                <div class="logo-hover">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo"
                            class="h-[80px] w-[100px] object-contain">
                    </a>
                </div>
            </div>


            <!-- Search -->
            <form action="{{ route('products.index') }}" method="GET"
                class="flex-grow w-full md:max-w-xl md:ml-2 mx-0 md:mx-4">
                <div class="relative p-[1px] rounded-lg bg-gradient-to-r from-brand-green to-brand-blue">
                    <input type="text" name="q" placeholder="Pesquisar produtos..."
                        class="w-full px-4 py-2 bg-surface rounded-lg text-gray-200 placeholder-gray-400 focus:outline-none text-sm"
                        value="{{ request('q') }}">
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-green text-sm">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </div>
            </form>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-200">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <!-- Desktop User / Auth -->
            <nav class="hidden md:flex items-center space-x-4 md:space-x-8 mt-2 md:mt-0 pr-2">
                <!-- Ícone do Carrinho -->
                <a href="{{ route('cart.index') }}"
                    class="relative flex items-center justify-center hover:text-brand-green transition">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-gray-200"></i>

                    <span id="cart-count"
                        class="absolute -top-1.5 -right-1.5 bg-red-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px] {{ count(session('cart', [])) === 0 ? 'hidden' : '' }}">
                        {{ count(session('cart', [])) }}
                    </span>
                </a>

                <!-- Container global do Toast -->
                <div id="toast-container" class="fixed bottom-5 left-5 z-[9999] space-y-2"></div>


                @guest
                    <a href="{{ route('login') }}"
                        class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md font-semibold text-sm flex items-center gap-2 transition-transform duration-300">
                        <i data-lucide="user" class="w-4 h-4"></i> Entrar
                    </a>
                @endguest
                @auth
                    <div class="relative inline-block text-left">
                        <!-- Botão fixo para todos -->
                        <button id="userMenuButton" type="button"
                            class="inline-flex justify-between items-center  text-white px-4 py-2 rounded-md font-semibold text-sm  transition-colors duration-300 focus:outline-none"
                            aria-expanded="true" aria-haspopup="true">
                            <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                            Olá, {{ Auth::user()->name }}
                            <i data-lucide="chevron-down" class="w-4 h-4 ml-2"></i>
                        </button>

                        <!-- Dropdown -->
                        <div id="userDropdown"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-20">
                            <div class="py-1 flex flex-col">
                                <!-- user -->
                                @if (Auth::user()->role === 'user')
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 mr-2"></i> Perfil
                                    </a>
                                @endif

                                <!-- admin -->
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('backoffice.index') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i> Backoffice
                                    </a>
                                @endif

                                <!-- Logout sempre -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

            </nav>
        </div>

        <!-- Mobile Navigation -->
        <nav id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-border">
            <div class="flex flex-col space-y-4 pt-4">
                <h4 class="text-gray-200 font-medium mb-2">Categorias</h4>
                @foreach ($categorias as $categoria)
                    <a href="{{ route('products.index', ['categoria' => Str::slug($categoria)]) }}"
                        class="block text-gray-400 hover:text-brand-green transition-colors duration-300 py-1">{{ $categoria }}</a>
                @endforeach
                <a href="{{ route('login') }}"
                    class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md font-bold text-lg flex items-center gap-2 transition-transform duration-300">
                    <i data-lucide="user" class="w-4 h-4"></i> Entrar
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-32">
        @yield('content')
    </main>

    <footer class="bg-surface-card border-t border-border py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 logo-hover">
                        <img src="{{ asset('images/PixelParts.png') }}" alt="PixelParts Logo"
                            class="h-[40px] w-[50px]">
                        <span class="text-2xl font-bold text-gradient-brand">PixelParts</span>
                    </div>
                    <p class="text-text-secondary">
                        A tua loja de confiança para componentes gaming de elite.
                        Performance, qualidade e inovação em cada produto.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-brand-green/20 rounded-lg flex items-center justify-center text-brand-green hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-bold text-text-primary mb-4">Links Rápidos</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Início</a>
                        </li>
                        <li><a href="{{ route('products.index') }}"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Produtos</a>
                        </li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Serviços</a>
                        </li>
                        <li><a href="{{ route('about') }}"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Sobre
                                Nós</a></li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Contacto</a>
                        </li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="text-lg font-bold text-text-primary mb-4">Categorias</h4>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Placas
                                Gráficas</a></li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Processadores</a>
                        </li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Memórias</a>
                        </li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Armazenamento</a>
                        </li>
                        <li><a href="#"
                                class="text-text-secondary hover:text-brand-green transition-colors duration-300">Motherboards</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-bold text-text-primary mb-4">Contacto</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3 text-text-secondary">
                            <i data-lucide="map-pin" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                            <span>Rua da Inovação, 123<br>Porto, Portugal</span>
                        </li>
                        <li class="flex items-center space-x-3 text-text-secondary">
                            <i data-lucide="phone" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                            <span>+351 123 456 789</span>
                        </li>
                        <li class="flex items-center space-x-3 text-text-secondary">
                            <i data-lucide="mail" class="w-4 h-4 text-brand-green flex-shrink-0"></i>
                            <span>info@pixelparts.pt</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div
                class="border-t border-gray-700 border-border pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-text-secondary text-sm mb-4 md:mb-0">
                    © 2024 PixelParts. Todos os direitos reservados.
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="#"
                        class="text-text-secondary hover:text-brand-green transition-colors duration-300">Política de
                        Privacidade</a>
                    <a href="#"
                        class="text-text-secondary hover:text-brand-green transition-colors duration-300">Termos de
                        Serviço</a>
                    <a href="#"
                        class="text-text-secondary hover:text-brand-green transition-colors duration-300">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/products.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/n8n-chat-widget.js') }}"></script>
    <!-- Chatbot do n8n -->
    <div id="n8n-chat" data-endpoint="http://localhost:5678/webhook/29b79101-e2b3-4556-9ebe-2c922853687f"
        data-title="Assistente PixelParts"
        data-welcome="Olá 👋 Sou a Pixel, assistente virtual da loja PixelParts! Em que posso ajudar?"
        data-color="#10B981">
    </div>


</body>

</html>
