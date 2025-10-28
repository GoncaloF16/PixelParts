@extends('layouts.master')

@section('content')
    <main>
        <!-- Hero Section -->
        <section id="inicio" class="relative min-h-screen flex items-start justify-center overflow-hidden">
            <!-- Partículas -->
            <div class="particles-container absolute inset-0">
                @for ($i = 0; $i < 10; $i++)
                    <div class="particle"></div>
                @endfor
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center max-w-4xl mx-auto px-6">
                <h1 class="text-5xl md:text-7xl font-bold text-gray-200 mb-6 animate-fade-up">
                    Componentes Gaming <br>
                    de <span class="text-gradient-brand">Elite</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-400 mb-8 max-w-3xl mx-auto leading-relaxed animate-fade-up"
                    style="animation-delay: 0.2s;">
                    Eleva o teu setup gaming com os melhores componentes do mercado.
                    Performance extrema, qualidade premium, resultados extraordinários.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up" style="animation-delay: 0.4s;">
                    <a href="#produtos">
                        <button
                            class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                            Explorar Produtos
                        </button>
                    </a>
                    <a href="{{ route('about') }}">
                        <button
                            class="border-2 border-brand-green text-brand-green px-8 py-4 rounded-lg font-bold text-lg hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                            Saber Mais
                        </button>
                    </a>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-32 left-1/2 transform -translate-x-1/2 animate-bounce">
                <i data-lucide="chevron-down" class="w-6 h-6 text-gray-400"></i>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produtos" class="pb-10 bg-surface">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="text-center mb-16 animate-fade-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-200 mb-4">
                        Produtos em <span class="text-gradient-brand">Destaque</span>
                    </h2>
                    <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                        Descobre a nossa seleção de componentes de alta performance para elevar o teu setup gaming ao
                        próximo nível.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-[850px] mx-auto">
                    @foreach ($products as $product)
                        <div
                            class="bg-gray-900 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 h-[440px] relative group flex flex-col">
                            <!-- Eye Icon -->
                            <div
                                class="absolute top-3 right-3 bg-gray-800/70 backdrop-blur-sm p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                <i data-lucide="eye" class="w-5 h-5 text-brand-green"></i>
                            </div>

                            <!-- Image -->
                            <a href="{{ route('products.details', ['slug' => $product->slug]) }}">
                                <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}" class="w-full h-[210px] object-cover cursor-pointer">
                            </a>

                            @if ($product->created_at->gt(now()->subDays(7)))
                                <div
                                    class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded z-10">
                                    NOVO
                                </div>
                            @endif

                            <div class="p-4 flex flex-col flex-grow">
                                <div class="flex-grow">
                                    <h3 class="text-lg font-bold text-gray-200 mb-2 line-clamp-2">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-400 leading-relaxed line-clamp-3">
                                        {{ $product->description }}
                                    </p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xl font-bold text-gray-100">
                                        €{{ number_format($product->price, 2, ',', '.') }}
                                    </span>
                                    <form class="add-to-cart-form" data-product-id="{{ $product->id }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-3 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 transition w-[120px] justify-center" data-id="{{ $product->id }}">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Adicionar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('products.index') }}">
                        <button
                            class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                            Ver Todos os Produtos
                        </button>
                    </a>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="relative py-20 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-green to-brand-blue"></div>
            <div class="container mx-auto px-6 relative z-10 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-200 mb-6">Mantém-te Atualizado</h2>
                <p class="text-lg text-gray-200 mb-8 max-w-2xl mx-auto">
                    Recebe as últimas novidades, lançamentos e promoções exclusivas diretamente no teu email.
                </p>
                <div id="newsletter-form" class="max-w-md mx-auto space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="email" id="email-input" placeholder="O teu email"
                            class="flex-1 px-4 py-3 bg-gray-100 border border-border rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300">
                        <button id="subscribe-btn"
                            class="bg-gray-900 text-gray-200 px-6 py-3 rounded-lg font-bold hover:scale-105 transition-transform duration-300 whitespace-nowrap">
                            Subscrever
                        </button>
                    </div>
                    <div class="flex items-center justify-center gap-6 text-sm text-gray-200 mt-4">
                        <div class="flex items-center gap-2"><i data-lucide="shield-check" class="w-4 h-4"></i><span>Sem
                                spam</span></div>
                        <div class="flex items-center gap-2"><i data-lucide="star" class="w-4 h-4"></i><span>Apenas conteúdo
                                relevante</span></div>
                    </div>
                </div>
                <div id="success-message" class="hidden max-w-md mx-auto text-center">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="check" class="w-8 h-8 text-brand-green"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-200 mb-2">Obrigado!</h3>
                        <p class="text-gray-200">A tua subscrição foi confirmada. Vais receber as nossas novidades em breve!
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.replace();

            const toggle = document.getElementById('menu-toggle');
            const dropdown = document.getElementById('menu-dropdown');

            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            // Fecha ao clicar fora
            document.addEventListener('click', () => {
                if (!dropdown.classList.contains('hidden')) dropdown.classList.add('hidden');
            });

            dropdown.addEventListener('click', e => e.stopPropagation());

            // Fechar com ESC
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        window.routes = {
            cartAdd: "{{ route('cart.add') }}"
        };
    </script>
@endsection
