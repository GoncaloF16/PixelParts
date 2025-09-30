 @extends('layouts.master')

 <main>
        <!-- Hero Section -->
        <section id="inicio" class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('assets/hero-bg.jpg');">
                <div class="absolute inset-0 bg-gradient-to-r from-surface-dark/90 via-surface-dark/60 to-surface-dark/90"></div>
            </div>

            <!-- Particles -->
            <div class="particles-container absolute inset-0">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center max-w-4xl mx-auto px-6">
                <h1 class="text-5xl md:text-7xl font-bold text-text-primary mb-6 animate-fade-up">
                    Componentes Gaming <br>
                    de <span class="text-gradient-brand">Elite</span>
                </h1>
                <p class="text-xl md:text-2xl text-text-secondary mb-8 max-w-3xl mx-auto leading-relaxed animate-fade-up" style="animation-delay: 0.2s;">
                    Eleva o teu setup gaming com os melhores componentes do mercado.
                    Performance extrema, qualidade premium, resultados extraordinários.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-up" style="animation-delay: 0.4s;">
                    <button class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                        Explorar Produtos
                    </button>
                    <button class="border-2 border-brand-green text-brand-green px-8 py-4 rounded-lg font-bold text-lg hover:bg-brand-green hover:text-surface-dark transition-all duration-300">
                        Saber Mais
                    </button>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <i data-lucide="chevron-down" class="w-6 h-6 text-text-secondary"></i>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produtos" class="py-20 bg-surface">
            <div class="container mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-16 animate-fade-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-text-primary mb-4">
                        Produtos em <span class="text-gradient-brand">Destaque</span>
                    </h2>
                    <p class="text-text-secondary text-lg max-w-2xl mx-auto">
                        Descobre a nossa seleção cuidadosa de componentes de alta performance
                        para elevar o teu setup gaming ao próximo nível.
                    </p>
                </div>

                <!-- Products Grid -->

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($products as $product)
        <div class="product-card w-100 bg-white shadow-lg rounded-lg overflow-hidden animate-scale-in">
            <!-- Imagem do produto -->
            <div class="relative">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/400x300' }}"
                     alt="{{ $product->name }}"
                     class="w-full h-48 object-cover">

                <!-- Badge NOVO -->
                @if($loop->first) <!-- Exemplo: só o primeiro produto como NOVO -->
                    <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                        NOVO
                    </div>
                @endif
            </div>

            <!-- Parte inferior preta com info e botão -->
            <div class="bg-gray-900 text-white p-4 flex flex-col gap-2">
                <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                <p class="text-sm text-gray-300">{{ $product->description }}</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="font-bold text-lg">€{{ number_format($product->price, 2, ',', '.') }}</span>
                    <button class="bg-gradient-to-r from-brand-green to-brand-blue text-surface-dark px-3 py-1 rounded-md font-semibold text-sm hover:scale-105 transition-transform duration-300 glow-brand flex items-center gap-1">
                        <i data-lucide="shopping-cart"></i>
                        Adicionar
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>


                <!-- View All Button -->
                <div class="text-center mt-16">
                    <button class="bg-gradient-to-r from-brand-green to-brand-blue text-white  px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                        Ver Todos os Produtos
                    </button>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="relative py-20 overflow-hidden">
            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-r from-brand-green to-brand-blue"></div>


            <div class="container mx-auto px-6 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="animate-fade-up">
                        <h2 class="text-4xl md:text-5xl font-bold text-text-primary mb-6">
                            Mantém-te Atualizado
                        </h2>
                        <p class="text-4xl text-lg mb-8 max-w-2xl mx-auto">
                            Recebe as últimas novidades sobre lançamentos, ofertas exclusivas e dicas de gaming
                            diretamente no teu email.
                        </p>

                        <!-- Newsletter Form -->
                        <div id="newsletter-form" class="max-w-md mx-auto space-y-4">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input
                                    type="email"
                                    id="email-input"
                                    placeholder="O teu email"
                                    class="flex-1 px-4 py-3 bg-surface-card border border-border rounded-lg text-text-primary placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-300"
                                >
                                <button
                                    id="subscribe-btn"
                                    class="bg-white text-black px-6 py-3 rounded-lg font-bold hover:scale-105 transition-transform duration-300 whitespace-nowrap"
                                >
                                    Subscrever
                                </button>
                            </div>

                            <div class="flex items-center justify-center gap-6 text-sm text-text-secondary">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                                    <span class="text-white">Sem spam</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="star" class="w-4 h-4 text-white"></i>
                                    <span class="text-white">Apenas conteúdo relevante</span>
                                </div>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <div id="success-message" class="hidden max-w-md mx-auto text-center">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-brand-green to-brand-blue rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="check" class="w-8 h-8 text-surface-dark"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-text-primary mb-2">Obrigado!</h3>
                                <p class="text-text-secondary">A tua subscrição foi confirmada. Vais receber as nossas novidades em breve!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
