<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Backoffice - PixelParts')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar Desktop -->
        <aside class="hidden lg:flex w-64 bg-gray-900 text-white flex-col">
            <div class="p-6 border-b border-gray-700">
                <h1 class="text-2xl font-bold">PixelParts</h1>
                <p class="text-sm text-gray-400">Backoffice</p>
            </div>
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('backoffice.index') }}"
                            class="nav-link {{ request()->is('backoffice') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('backoffice.stock') }}"
                            class="nav-link {{ request()->is('backoffice/stock') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Gestão de Stock
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('backoffice.users') }}"
                            class="nav-link {{ request()->is('backoffice/users') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Gestão de Utilizadores
                        </a>
                    </li>
                    <li>
                        <a href="/backoffice/orders"
                            class="nav-link {{ request()->is('backoffice/orders') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Encomendas
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <a href="{{ route('home') }}"><button
                        class="w-full flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sair
                    </button> </a>
            </div>
        </aside>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden">
            <aside class="w-64 bg-gray-900 text-white h-full flex flex-col">
                <div class="p-6 border-b border-gray-700 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">PixelParts</h1>
                        <p class="text-sm text-gray-400">Backoffice</p>
                    </div>
                    <button id="close-mobile-menu" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('backoffice.index') }}"
                                class="nav-link {{ request()->is('backoffice') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('backoffice.stock') }}"
                                class="nav-link {{ request()->is('backoffice/stock') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Gestão de Stock
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('backoffice.users') }}"
                                class="nav-link {{ request()->is('backoffice/users') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Gestão de Utilizadores
                            </a>
                        </li>
                        <li>
                            <a href="/backoffice/orders"
                                class="nav-link {{ request()->is('backoffice/orders') ? 'active' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Encomendas
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="p-4 border-t border-gray-700">
                    <a href="{{ route('home') }}"><button
                            class="w-full flex items-center gap-3 px-4 py-2 text-gray-300 hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sair
                        </button> </a>
                </div>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-btn" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">@yield('page-title')</h2>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-4">
                        <div class="hidden sm:flex items-center gap-2">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 hidden sm:block">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="sm:hidden w-8 h-8 bg-gray-300 rounded-full"></div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.lucide && lucide.createIcons) {
                lucide.createIcons();
            }

            // Mobile Menu Toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const closeMobileMenu = document.getElementById('close-mobile-menu');

            if (mobileMenuBtn && mobileMenuOverlay) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenuOverlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            if (closeMobileMenu && mobileMenuOverlay) {
                closeMobileMenu.addEventListener('click', () => {
                    mobileMenuOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                });

                // Fechar ao clicar fora do menu
                mobileMenuOverlay.addEventListener('click', (e) => {
                    if (e.target === mobileMenuOverlay) {
                        mobileMenuOverlay.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Fechar menu ao clicar em um link
            const mobileMenuLinks = mobileMenuOverlay?.querySelectorAll('a');
            mobileMenuLinks?.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenuOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                });
            });
        });
    </script>
    <script src="{{ asset('js/backofficeUsers.js') }}"></script>
    <script src="{{ asset('js/backofficeStock.js') }}"></script>
</body>

</html>
