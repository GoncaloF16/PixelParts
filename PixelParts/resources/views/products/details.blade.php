@extends('layouts.master')

@section('content')
    <main>
        <div class="container mx-auto px-6">

            <!-- Breadcrumb -->
            <nav class="text-sm text-text-secondary mb-2" aria-label="Breadcrumb">
                <ol class="list-reset flex flex-wrap items-center">
                    <li>
                        <a href="{{ route('home') }}" class="hover:underline text-base">Home</a>
                    </li>
                    <li><span class="mx-2 text-base">></span></li>
                    <li> <a href="{{ route('products.index') }}" class="text-text-primary font-semibold text-base">Produtos
                        </a>
                    </li>
                    @if (isset($product))
                        <li><span class="mx-2 text-base">></span></li>
                        <li class="max-w-xs truncate">
                            <span class="text-text-primary font-semibold text-base">
                                {{ $product->name }}
                            </span>
                        </li>
                    @endif
                </ol>
            </nav>
            <br>
            <div class="grid lg:grid-cols-2 gap-12 mb-16 items-start">
                <!-- Imagem principal -->
                <div class="space-y-4">
                    <div class="relative bg-surface-elevated rounded-2xl overflow-hidden aspect-square">
                        @if ($product->created_at->gt(now()->subDays(7)))
                        <span
                            class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded z-10">
                            NOVO
                        </span>
                        @endif
                        <img src="{{ $product->image
                            ? (Str::startsWith($product->image, ['http://', 'https://'])
                                ? $product->image
                                : asset('storage/' . $product->image))
                            : 'https://via.placeholder.com/400' }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">

                    </div>

                    <!-- Produtos Recomendados -->
                    @if($recommendedProducts->count() > 0)
                    <div id="recommended-section" class="mt-8 bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 border border-white/10">
                        <h3 class="text-white text-2xl font-bold mb-2">A Combinação Perfeita</h3>
                        <p class="text-gray-400 text-sm mb-6">Produtos compatíveis frequentemente comprados juntos</p>

                        <div class="flex items-center gap-3 mb-6 overflow-x-auto pb-4" style="scrollbar-width: none; -ms-overflow-style: none;">
                            <style>
                                .flex.items-center.gap-3::-webkit-scrollbar {
                                    display: none;
                                }
                            </style>
                            <!-- Produto principal (sempre selecionado) -->
                            <div class="relative flex-shrink-0 pt-3 pr-3">
                                <input type="checkbox"
                                       checked
                                       disabled
                                       class="absolute top-1 right-1 w-5 h-5 rounded border-2 border-white bg-brand-green z-10 cursor-not-allowed">
                                <div class="w-24 h-24 rounded-lg overflow-hidden bg-white">
                                    <img src="{{ $product->image
                                        ? (Str::startsWith($product->image, ['http://', 'https://'])
                                            ? $product->image
                                            : asset('storage/' . $product->image))
                                        : 'https://via.placeholder.com/96' }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>

                            @foreach($recommendedProducts->take(3) as $index => $rec)
                            <div class="text-white text-2xl font-light flex-shrink-0">+</div>
                            <div class="relative flex-shrink-0 pt-3 pr-3">
                                <input type="checkbox"
                                       class="bundle-checkbox absolute top-1 right-1 w-5 h-5 rounded border-2 border-white cursor-pointer z-10 checked:bg-brand-green checked:border-brand-green"
                                       data-product-id="{{ $rec->id }}"
                                       data-price="{{ $rec->discount_percentage && $rec->discount_percentage > 0 ? $rec->discounted_price : $rec->price }}"
                                       checked
                                       onchange="updateBundleTotal()">
                                <div class="w-24 h-24 rounded-lg overflow-hidden bg-white">
                                    <img src="{{ $rec->image
                                        ? (Str::startsWith($rec->image, ['http://', 'https://'])
                                            ? $rec->image
                                            : asset('storage/' . $rec->image))
                                        : 'https://via.placeholder.com/96' }}"
                                        alt="{{ $rec->name }}"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Product list with prices -->
                        <div class="space-y-3 mb-6" id="bundle-products-list">
                            @foreach($recommendedProducts->take(3) as $rec)
                                <div class="flex items-start justify-between text-sm bundle-product-item" data-product-id="{{ $rec->id }}">
                                    <a href="{{ route('products.details', $rec->slug) }}" class="text-white hover:text-brand-green transition-colors flex-1 line-clamp-2">
                                        {{ $rec->name }}
                                    </a>
                                    <div class="flex flex-col items-end ml-4 flex-shrink-0">
                                        @if($rec->discount_percentage && $rec->discount_percentage > 0)
                                            <span class="text-xs text-gray-400 line-through">€{{ number_format($rec->price, 2, ',', '.') }}</span>
                                            <span class="text-gray-100 font-bold">€{{ number_format($rec->discounted_price, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-gray-100 font-bold">€{{ number_format($rec->price, 2, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total and button -->
                        <div class="flex items-center justify-between pt-4 border-t border-white/10">
                            <div>
                                <div class="text-gray-100 text-3xl font-bold" id="bundle-total">€{{ number_format(($product->discount_percentage && $product->discount_percentage > 0 ? $product->discounted_price : $product->price) + $recommendedProducts->take(3)->sum(function($p) { return $p->discount_percentage && $p->discount_percentage > 0 ? $p->discounted_price : $p->price; }), 2, ',', '.') }}</div>
                            </div>
                            <button type="button"
                                    id="add-bundle-btn"
                                    onclick="addSelectedBundle()"
                                    class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-8 py-4 rounded-lg font-bold text-lg transition-all">
                                <span id="bundle-count">ADICIONAR {{ $recommendedProducts->take(3)->count() + 1 }} ARTIGOS</span>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="lg:sticky lg:top-6 space-y-6" id="product-info-section" style="align-self: start;">
                    <p class="text-brand-green font-semibold mb-2">{{ $product->brand }}</p>
                    <h1 class="!text-white text-4xl font-bold text-text-inverse mb-4">{{ $product->name }}</h1>

                    <!-- Rating -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg viewBox="0 0 20 20" class="w-5 h-5">
                                    <defs>
                                        <linearGradient id="star-gradient" x1="0" y1="0" x2="1"
                                            y2="0">
                                            <stop offset="0%" stop-color="#22C55E" />
                                            <stop offset="100%" stop-color="#3B82F6" />
                                        </linearGradient>
                                    </defs>
                                    <path fill="{{ $i <= round($averageRating) ? 'url(#star-gradient)' : '#4B5563' }}"
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-text-muted">{{ number_format($averageRating, 1) }}
                            ({{ $product->reviews->count() }} avaliações)</span>
                    </div>

                    <!-- Price -->
                    <div class="flex items-baseline gap-4 mb-6">
                        @if($product->discount_percentage && $product->discount_percentage > 0)
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="text-5xl font-bold bg-gradient-to-r from-brand-green to-brand-blue bg-clip-text text-transparent">
                                        €{{ number_format($product->discounted_price, 2, ',', '.') }}
                                    </span>
                                    <span class="bg-yellow-500 text-gray-900 text-sm font-bold px-3 py-1 rounded">
                                        -€{{ number_format($product->discount_amount, 2, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-2xl text-gray-400 line-through">
                                    €{{ number_format($product->price, 2, ',', '.') }}
                                </span>
                            </div>
                        @else
                            <span class="text-5xl font-bold text-gray-100">
                                €{{ number_format($product->price, 2, ',', '.') }}
                            </span>
                        @endif
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

                    <!-- Description -->
                    <p class="text-text-muted leading-relaxed mb-6">{{ $product->description }}</p>

                    <!-- Quantity and Button -->
                    <div class="space-y-4 border-t border-white/10 pt-6">
                        <form class="add-to-cart-form" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <!-- Hidden input de quantidade -->
                            <input type="hidden" name="quantity" id="quantity-input" value="1">

                            <div class="flex items-center gap-4 mb-4">
                                <label class="!text-white text-text-inverse">Quantidade:</label>
                                <div class="flex items-center border border-white/20 rounded-lg overflow-hidden">
                                    <button type="button" onclick="decreaseQuantity()"
                                        class="bg-gray-700 hover:bg-gray-600 !text-white px-4 py-2 transition-colors">-</button>
                                    <span id="quantity-display" class="!text-white px-6 py-2 font-semibold"
                                        data-stock="{{ $product->stock }}">1</span>
                                    <button type="button" onclick="increaseQuantity({{ $product->stock }})"
                                        class="bg-gray-700 hover:bg-gray-600 !text-white px-4 py-2 transition-colors">+</button>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button type="submit"
                                    class="flex-1 bg-gray-700 hover:bg-gray-600 text-gray-200 h-14 text-lg font-semibold rounded-lg flex items-center justify-center gap-2 transition-all">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Tabs -->
            <div class="w-full pb-5">
                <div class="bg-surface-elevated border border-white/10 p-1 rounded-lg grid grid-cols-2 md:grid-cols-4 gap-2 mb-8">
                    <button class="tab-btn active px-3 md:px-6 py-3 rounded-lg font-semibold transition-all text-sm md:text-base"
                        data-tab="specs">Especificações</button>
                    <button class="tab-btn px-3 md:px-6 py-3 rounded-lg font-semibold transition-all text-sm md:text-base"
                        data-tab="features">Características</button>
                    <button class="tab-btn px-3 md:px-6 py-3 rounded-lg font-semibold transition-all text-sm md:text-base"
                        data-tab="compatibility">Compatibilidade</button>
                    <button class="tab-btn px-3 md:px-6 py-3 rounded-lg font-semibold transition-all text-sm md:text-base" data-tab="reviews">Avaliações
                        ({{ $product->reviews->count() }})</button>
                </div>

                <!-- Specifications -->
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

                <!-- Features -->
                <div id="tab-features" class="tab-content hidden bg-surface-elevated border border-white/10 rounded-lg p-8">
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
                                        <svg viewBox="0 0 20 20" class="w-6 h-6">
                                            <defs>
                                                <linearGradient id="star-gradient-main" x1="0" y1="0"
                                                    x2="1" y2="0">
                                                    <stop offset="0%" stop-color="#22C55E" />
                                                    <stop offset="100%" stop-color="#3B82F6" />
                                                </linearGradient>
                                            </defs>
                                            <path
                                                fill="{{ $i <= round($averageRating) ? 'url(#star-gradient-main)' : '#4B5563' }}"
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span
                                    class="text-white text-text-inverse text-xl font-bold">{{ number_format($averageRating, 1) }}
                                    de 5</span>
                            </div>
                        </div>

                        @auth
                            <button id="showReviewFormBtn"
                                class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-6 py-3 rounded-lg font-semibold transition-all">
                                Escrever Avaliação
                            </button>
                        @endauth
                    </div>

                    <!-- Review form -->
                    @auth
                        <form id="reviewForm" action="{{ route('reviews.store', $product->slug) }}" method="POST"
                            class="mb-8 hidden">
                            @csrf
                            <div class="flex items-center gap-2 mb-4">
                                <label class="text-white font-semibold">Nota:</label>
                                <div id="starRating" class="flex gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg data-value="{{ $i }}" viewBox="0 0 20 20"
                                            class="w-6 h-6 cursor-pointer">
                                            <defs>
                                                <linearGradient id="star-gradient-form" x1="0" y1="0"
                                                    x2="1" y2="0">
                                                    <stop offset="0%" stop-color="#22C55E" />
                                                    <stop offset="100%" stop-color="#3B82F6" />
                                                </linearGradient>
                                            </defs>
                                            <path fill="#4B5563"
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput">
                            </div>
                            <div class="mb-4">
                                <textarea name="comment"
                                    class="text-white w-full p-4 rounded-lg bg-surface text-text-inverse placeholder:text-text-muted" rows="4"
                                    placeholder="Escreva sua avaliação..."></textarea>
                            </div>
                            <button type="submit"
                                class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-6 py-3 rounded-lg font-semibold transition-all">
                                Enviar Avaliação
                            </button>
                        </form>
                    @endauth

                    <!-- Lista de reviews -->
                    <div class="space-y-6">
                        @forelse ($product->reviews as $review)
                            <div class="border-b border-white/10 pb-6 last:border-0 flex justify-between items-start">
                                <!-- Review content -->
                                <div class="flex-1 pr-4">
                                    <h4 class="text-white text-text-inverse font-bold">{{ $review->user->name }}</h4>
                                    <div class="flex items-center gap-1 mt-1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg viewBox="0 0 20 20" class="w-4 h-4">
                                                <defs>
                                                    <linearGradient id="star-gradient-review-{{ $review->id }}"
                                                        x1="0" y1="0" x2="1" y2="0">
                                                        <stop offset="0%" stop-color="#22C55E" />
                                                        <stop offset="100%" stop-color="#3B82F6" />
                                                    </linearGradient>
                                                </defs>
                                                <path
                                                    fill="{{ $i <= $review->rating ? 'url(#star-gradient-review-' . $review->id . ')' : '#4B5563' }}"
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-white text-text-muted mt-2">{{ $review->comment }}</p>
                                </div>

                                <!-- Delete button -->
                                @auth
                                    @if (auth()->id() === $review->user_id)
                                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                            class="flex-shrink-0 ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <br>
                                            <button type="submit"
                                                class="rounded-lg px-4 py-2 text-white bg-red-700 hover:bg-red-600 font-bold">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <p class="text-text-muted">Ainda não há avaliações para este produto.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast template -->
    <div id="toast-template" class="hidden">
        <div
            class="fixed bottom-4 right-4 bg-surface-elevated border border-brand-green/30 px-6 py-4 rounded-lg shadow-lg text-white flex items-center gap-3 opacity-0 transform translate-y-4 transition-all duration-300">
            <svg data-lucide="check-circle" class="w-5 h-5 text-brand-green"></svg>
            <span class="toast-message">Produto adicionado ao carrinho!</span>
            <button onclick="closeToast(this)" class="ml-2 text-white hover:text-brand-green transition">×</button>
        </div>
    </div>

    <script>
        // Tabs
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

        // Toggle form
        const showFormBtn = document.getElementById('showReviewFormBtn');
        const reviewForm = document.getElementById('reviewForm');
        if (showFormBtn) showFormBtn.addEventListener('click', () => reviewForm.classList.toggle('hidden'));

        // Star rating form
        const starRating = document.getElementById('starRating');
        const ratingInput = document.getElementById('ratingInput');
        if (starRating) {
            const stars = starRating.querySelectorAll('svg');
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    ratingInput.value = star.dataset.value;
                    stars.forEach(s => s.querySelector('path').setAttribute('fill', '#4B5563'));
                    for (let i = 0; i < star.dataset.value; i++) {
                        stars[i].querySelector('path').setAttribute('fill', 'url(#star-gradient-form)');
                    }
                });
                star.addEventListener('mouseenter', () => {
                    const val = star.dataset.value;
                    stars.forEach(s => s.querySelector('path').setAttribute('fill', '#4B5563'));
                    for (let i = 0; i < val; i++) {
                        stars[i].querySelector('path').setAttribute('fill', 'url(#star-gradient-form)');
                    }
                });
                star.addEventListener('mouseleave', () => {
                    const val = ratingInput.value || 0;
                    stars.forEach(s => s.querySelector('path').setAttribute('fill', '#4B5563'));
                    for (let i = 0; i < val; i++) {
                        stars[i].querySelector('path').setAttribute('fill', 'url(#star-gradient-form)');
                    }
                });
            });
        }

        window.routes = {
            cartAdd: "{{ route('cart.add') }}"
        };

        // Quick add to cart for recommended products
        function addToCartQuick(productId) {
            fetch(window.routes.cartAdd, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.showToast(data.message);
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.cart_count);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Add bundle to cart (multiple products)
        async function addBundleToCart(productIds) {
            let successCount = 0;

            for (const productId of productIds) {
                try {
                    const response = await fetch(window.routes.cartAdd, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        successCount++;
                        if (typeof window.updateCartCount === 'function') {
                            window.updateCartCount(data.cart_count);
                        }
                    }
                } catch (error) {
                    console.error('Error adding product:', error);
                }
            }

            if (successCount > 0) {
                window.showToast(`${successCount} produtos adicionados ao carrinho!`);
            }
        }

        // Update bundle total based on selected checkboxes
        function updateBundleTotal() {
            const checkboxes = document.querySelectorAll('.bundle-checkbox:checked');
            const mainProductPrice = {{ $product->discount_percentage && $product->discount_percentage > 0 ? $product->discounted_price : $product->price }};
            let total = mainProductPrice;
            let count = 1; // Main product always included

            checkboxes.forEach(checkbox => {
                const price = parseFloat(checkbox.dataset.price);
                total += price;
                count++;
            });

            // Update total display
            document.getElementById('bundle-total').textContent =
                '€' + total.toFixed(2).replace('.', ',');

            // Update button text
            document.getElementById('bundle-count').textContent =
                `ADICIONAR ${count} ${count === 1 ? 'ARTIGO' : 'ARTIGOS'}`;
        }

        // Add selected products from bundle
        async function addSelectedBundle() {
            const selectedCheckboxes = document.querySelectorAll('.bundle-checkbox:checked');
            const productIds = [{{ $product->id }}]; // Always include main product

            selectedCheckboxes.forEach(checkbox => {
                productIds.push(parseInt(checkbox.dataset.productId));
            });

            await addBundleToCart(productIds);
        }
    </script>
@endsection
