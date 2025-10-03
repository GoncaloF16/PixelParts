@extends('layouts.master')

@section('content')
    <main class="pt-24 pb-16 mt-10">
        <div class="container mx-auto px-6">

            <!-- Breadcrumb -->
            <a href="{{ route('products.index') }}"
                class="flex items-center gap-2 text-text-muted hover:text-brand-green transition-colors mb-8 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Voltar aos Produtos</span>
            </a>

            <div class="grid lg:grid-cols-2 gap-12 mb-16">

                <!-- Imagem principal -->
                <div class="space-y-4">
                    <div class="relative bg-surface-elevated rounded-2xl overflow-hidden aspect-square">
                        <span
                            class="absolute top-4 left-4 z-10 bg-brand-green text-surface-dark px-3 py-1 rounded-full text-xs font-semibold">
                            Novo
                        </span>

                        <img src="{{ $product->image ? asset($product->image) : 'https://via.placeholder.com/400' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

                <!-- Info -->
                <!-- Info -->
                <div class="space-y-6">
                    <div>
                        <p class="text-brand-green font-semibold mb-2">{{ $product->brand }}</p>
                        <h1 class="!text-white text-4xl font-bold text-text-inverse mb-4">{{ $product->name }}</h1>

                        <!-- Rating -->
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-brand-green' : 'text-gray-500' }} fill-current"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-text-muted">{{ number_format($averageRating, 1) }}
                                ({{ $product->reviews->count() }} avaliações)</span>
                        </div>

                        <!-- Preço -->
                        <div class="flex items-baseline gap-4 mb-6">
                            <span class="text-5xl font-bold text-gradient-brand">
                                €{{ number_format($product->price, 2, ',', '.') }}
                            </span>
                        </div>

                        <!-- Stock -->
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="!text-white text-text-inverse">
                                Em stock ({{ $product->stock }} unidades disponíveis)
                            </span>
                        </div>

                        <!-- Descrição -->
                        <p class="text-text-muted leading-relaxed mb-6">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Quantidade e Botão -->
                    <div class="space-y-4 border-t border-white/10 pt-6">
                        <div class="flex items-center gap-4">
                            <label class="!text-white text-text-inverse">Quantidade:</label>
                            <div class="flex items-center border border-white/20 rounded-lg overflow-hidden">
                                <button onclick="decreaseQuantity()"
                                    class="bg-gradient-to-r from-brand-green to-brand-blue !text-white px-4 py-2 bg-surface-elevated hover:bg-brand-green/20 text-text-inverse transition-colors">
                                    -
                                </button>
                                <span id="quantity-display"
                                    class="!text-white px-6 py-2 bg-surface text-text-inverse font-semibold">1</span>
                                <button onclick="increaseQuantity({{ $product->stock }})"
                                    class="bg-gradient-to-r from-brand-green to-brand-blue !text-white px-4 py-2 bg-surface-elevated hover:bg-brand-green/20 text-text-inverse transition-colors">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button onclick="addToCart({{ $product->id }})"
                                class="flex-1 bg-gradient-to-r from-brand-green to-brand-blue text-white hover:bg-brand-green-glow text-surface-dark h-14 text-lg font-bold rounded-lg flex items-center justify-center gap-2 glow-brand transition-all">
                                Adicionar ao Carrinho
                            </button>
                        </div>
                    </div>
                </div>
            </div>



                <!-- Tabs -->
                <div class="w-full">
                    <div class="bg-surface-elevated border border-white/10 p-1 rounded-lg grid grid-cols-4 mb-8">
                        <button class="tab-btn active px-6 py-3 rounded-lg font-semibold transition-all"
                            data-tab="specs">Especificações</button>
                        <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all"
                            data-tab="features">Características</button>
                        <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all"
                            data-tab="compatibility">Compatibilidade</button>
                        <button class="tab-btn px-6 py-3 rounded-lg font-semibold transition-all" data-tab="reviews">
                            Avaliações ({{ $product->reviews->count() }})
                        </button>
                    </div>

                    <!-- Especificações -->
                    <div id="tab-specs" class="tab-content bg-surface-elevated border border-white/10 rounded-lg p-8">
                        <h3 class="text-2xl font-bold text-white text-text-inverse mb-6">Especificações Técnicas</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse ($product->specifications as $spec)
                                <div class="flex justify-between py-3 border-b border-white/10">
                                    <span class="text-text-muted !text-white">{{ $spec->key }}:</span>
                                    <span class="!text-white text-text-inverse">{{ $spec->value }}</span>
                                </div>
                            @empty
                                <p class="text-text-muted">Nenhuma especificação disponível.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Características -->
                    <div id="tab-features"
                        class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                        <h3 class="text-white text-2xl font-bold text-text-inverse mb-6">Características Principais</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse ($product->features as $feature)
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-brand-green mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-white text-text-inverse">{{ $feature->feature }}</span>
                                </div>
                            @empty
                                <p class="text-text-muted">Nenhuma característica disponível.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Compatibilidade -->
                    <div id="tab-compatibility"
                        class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                        <h3 class="text-white text-2xl font-bold text-text-inverse mb-6">Informações de Compatibilidade</h3>
                        <div class="space-y-4">
                            @forelse ($product->compatibility as $item)
                                <div
                                    class="flex items-start gap-3 p-4 bg-brand-blue/10 rounded-lg border border-brand-blue/20">
                                    <svg class="w-5 h-5 text-brand-blue mt-1 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-white text-text-inverse">{{ $item->compatible_with }}</span>
                                </div>
                            @empty
                                <p class="text-text-muted">Nenhuma informação de compatibilidade disponível.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div id="tab-reviews"
                        class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
                        <div class="flex justify-between items-center mb-8 flex-wrap gap-4">
                            <div>
                                <h3 class="text-white text-2xl font-bold text-text-inverse mb-2">Avaliações de Clientes</h3>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-brand-green' : 'text-gray-500' }} fill-current"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span
                                        class="text-white text-text-inverse text-xl font-bold">{{ number_format($averageRating, 1) }}
                                        de 5</span>
                                </div>
                            </div>
                            <button
                                class="bg-brand-green hover:bg-brand-green-glow text-surface-dark px-6 py-3 rounded-lg font-bold transition-all">
                                Escrever Avaliação
                            </button>
                        </div>

                        <div class="space-y-6">
                            @forelse ($product->reviews as $review)
                                <div class="border-b border-white/10 pb-6 last:border-0">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="text-white text-text-inverse font-bold">{{ $review->user->name }}</h4>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-brand-green' : 'text-gray-500' }} fill-current"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <span
                                            class="text-text-muted text-sm">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <p class="text-text-muted">{{ $review->comment }}</p>
                                </div>
                            @empty
                                <p class="text-text-muted">Ainda não existem avaliações para este produto.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <script>
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.add('hidden'));

                btn.classList.add('active');
                document.querySelector(`#tab-${btn.dataset.tab}`).classList.remove('hidden');
            });
        });
    </script>
@endsection
