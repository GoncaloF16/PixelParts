@extends('layouts.master')

<main class="pt-24 pb-16 mt-10">
    <div class="container mx-auto px-6">

        <!-- Breadcrumb -->
        <a href="{{ route('home') }}"
            class="flex items-center gap-2 text-text-muted hover:text-brand-green transition-colors mb-8 inline-flex">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Voltar aos Produtos</span>
        </a>

        <div class="grid lg:grid-cols-2 gap-12 mb-16">

            <!-- Galeria de Imagens -->
            <div class="space-y-4">
                <div class="relative bg-surface-elevated rounded-2xl overflow-hidden aspect-square"
                    id="main-image-container">
                    <span class="absolute top-4 left-4 z-10 bg-brand-green text-surface-dark px-3 py-1 rounded-full text-xs font-semibold">
                        Novo
                    </span>

                    <img src="https://via.placeholder.com/400" alt="Produto Principal"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        id="main-image">
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-4">
                    @for ($i = 0; $i < 4; $i++)
                        <button onclick="changeImage({{ $i }})"
                            class="thumbnail-btn relative aspect-square rounded-lg overflow-hidden border-2 transition-all {{ $i === 0 ? 'border-brand-green shadow-lg shadow-brand-green/30' : 'border-surface-elevated hover:border-brand-green/50' }}"
                            data-index="{{ $i }}">
                            <img src="https://via.placeholder.com/100" alt="Thumbnail {{ $i + 1 }}"
                                class="w-full h-full object-cover">
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Informações do Produto -->
            <div class="space-y-6">
                <div>
                    <p class="text-brand-green font-semibold mb-2">Marca Exemplo</p>
                    <h1 class="!text-white text-4xl font-bold text-text-inverse mb-4">Nome do Produto</h1>

                    <!-- Rating -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 text-brand-green fill-current" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-text-muted">4.5 (12 avaliações)</span>
                    </div>

                    <!-- Preço -->
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-5xl font-bold text-gradient-brand">€199,99</span>
                    </div>

                    <!-- Stock -->
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="!text-white text-text-inverse">Em stock (10 unidades disponíveis)</span>
                    </div>

                    <!-- Descrição -->
                    <p class="text-text-muted leading-relaxed mb-6">
                        Descrição do produto com informações fictícias para teste da view.
                    </p>
                </div>

                <!-- Quantidade e Ações -->
                <div class="space-y-4 border-t border-white/10 pt-6">
                    <div class="flex items-center gap-4">
                        <label class="!text-white text-text-inverse">Quantidade:</label>
                        <div class="flex items-center border border-white/20 rounded-lg overflow-hidden">
                            <button onclick="decreaseQuantity()"
                                class="bg-gradient-to-r from-brand-green to-brand-blue !text-white px-4 py-2 bg-surface-elevated hover:bg-brand-green/20 text-text-inverse transition-colors">
                                -
                            </button>
                            <span id="quantity-display" class="!text-white px-6 py-2 bg-surface text-text-inverse font-semibold">1</span>
                            <button onclick="increaseQuantity(10)"
                                class="bg-gradient-to-r from-brand-green to-brand-blue !text-white px-4 py-2 bg-surface-elevated hover:bg-brand-green/20 text-text-inverse transition-colors">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button onclick="addToCart()"
                            class="flex-1 bg-gradient-to-r from-brand-green to-brand-blue text-white hover:bg-brand-green-glow text-surface-dark h-14 text-lg font-bold rounded-lg flex items-center justify-center gap-2 glow-brand transition-all">
                            Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs de Informação Detalhada -->
        <div class="w-full">
            <div class="bg-surface-elevated border border-white/10 p-1 rounded-lg grid grid-cols-4 mb-8">
                <button class="tab-btn active px-6 py-3 rounded-lg font-semibold transition-all">Especificações</button>
                <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all">Características</button>
                <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all">Compatibilidade</button>
                <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all">Avaliações (12)</button>
            </div>

            <div id="tab-specs" class="tab-content bg-surface-elevated border border-white/10 rounded-lg p-8">
                <h3 class="text-2xl font-bold text-text-inverse mb-6">Especificações Técnicas</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex justify-between py-3 border-b border-white/10">
                        <span class="text-text-muted font-semibold">Dimensão:</span>
                        <span class="text-text-inverse">10x20cm</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-white/10">
                        <span class="text-text-muted font-semibold">Peso:</span>
                        <span class="text-text-inverse">500g</span>
                    </div>
                </div>
            </div>

            <div id="tab-features" class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                <h3 class="text-2xl font-bold text-text-inverse mb-6">Características Principais</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-green mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-text-inverse">Resistente à água</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-green mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-text-inverse">Bateria de longa duração</span>
                    </div>
                </div>
            </div>

            <div id="tab-compatibility" class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                <h3 class="text-2xl font-bold text-text-inverse mb-6">Informações de Compatibilidade</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-4 bg-brand-blue/10 rounded-lg border border-brand-blue/20">
                        <svg class="w-5 h-5 text-brand-blue mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-text-inverse">Compatível com todos os modelos recentes</span>
                    </div>
                </div>
            </div>

            <div id="tab-reviews" class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-text-inverse mb-2">Avaliações de Clientes</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 text-brand-green fill-current" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-text-inverse text-xl font-bold">4.5 de 5</span>
                        </div>
                    </div>
                    <button class="bg-brand-green hover:bg-brand-green-glow text-surface-dark px-6 py-3 rounded-lg font-bold transition-all">
                        Escrever Avaliação
                    </button>
                </div>

                <div class="space-y-6">
                    <div class="border-b border-white/10 pb-6 last:border-0">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="text-text-inverse font-bold">João Silva</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 text-brand-green fill-current" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="text-text-muted text-sm">01/10/2025</span>
                        </div>
                        <h5 class="text-text-inverse font-semibold mb-2">Ótimo Produto</h5>
                        <p class="text-text-muted">Produto de ótima qualidade, recomendo a todos!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
