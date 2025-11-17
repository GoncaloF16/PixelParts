@extends('layouts.master')

@section('content')
    <main>
        <section class="bg-surface min-h-screen">
            <div class="container mx-auto px-6">

                <!-- Header -->
                <div class="text-center mb-12 animate-fade-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-text-primary mb-4">
                        O Teu <span class="text-gradient-brand">Carrinho</span>
                    </h2>
                    <p class="text-text-secondary text-lg max-w-2xl mx-auto">
                        Revê os teus produtos antes de finalizar a compra.
                    </p>
                </div>

                <!-- Carrinho vazio -->
                <div class="text-center py-20 animate-fade-up cart-empty"
                    style="{{ count($cartItems) ? 'display:none;' : 'display:block;' }}">
                    <i data-lucide="shopping-cart" class="w-16 h-16 text-text-secondary mx-auto mb-6"></i>
                    <h3 class="text-2xl font-bold text-text-primary mb-2">O teu carrinho está vazio</h3>
                    <p class="text-text-secondary mb-6">Explora os nossos produtos e adiciona alguns itens ao teu carrinho.
                    </p>
                    <a href="{{ route('products.index') }}"
                        class="pt-2 bg-gradient-to-r from-brand-green to-brand-blue text-white px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                        Explorar Produtos
                    </a>
                </div>

                <!-- Container do carrinho -->
                <div class="cart-content {{ count($cartItems) ? '' : 'hidden' }}">
                    @if (count($cartItems) > 0)
                        <!-- Container grid -->
                        <div
                            class="card-content grid grid-cols-1 lg:grid-cols-3 gap-8 w-full max-w-7xl mx-auto animate-fade-up">

                            <!-- Lista de produtos scrollable -->
                            <div class="lg:col-span-2 space-y-4 overflow-y-auto max-h-[700px]">
                                @foreach ($cartItems as $item)
                                    <div id="cart-item-{{ $item['product_id'] }}"
                                        class="bg-gray-900 rounded-lg shadow p-4 flex justify-between items-center text-white cart-item break-words transition-all duration-300"
                                        style="opacity: 1; transform: translateX(0);">
                                        <div>
                                            <span class="font-semibold text-lg block">{{ $item['name'] }}</span>
                                            <span class="text-sm opacity-80">Quantidade:
                                                <span class="item-quantity">{{ $item['quantity'] }}</span>
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <button data-id="{{ $item['product_id'] }}"
                                                class="remove-from-cart text-red-500 hover:text-red-700 text-xs">
                                                Remover
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Resumo Detalhado sticky -->
                            <div
                                class="lg:col-span-1 bg-gray-900 rounded-lg shadow-lg p-6 text-white flex flex-col justify-between sticky top-20">
                                <div>
                                    <h3 class="text-xl font-bold mb-4">Resumo Detalhado</h3>
                                    <div class="space-y-3 mb-6" id="cart-summary-items">
                                        @foreach ($cartItems as $item)
                                            <div class="border-b border-gray-700 pb-2 cart-summary-item transition-all duration-300"
                                                data-id="{{ $item['product_id'] }}"
                                                style="opacity: 1; transform: translateX(0);">
                                                <div class="flex justify-between text-sm">
                                                    <span class="font-semibold break-words item-name">{{ $item['name'] }} ×
                                                        <span class="item-summary-quantity">{{ $item['quantity'] }}</span></span>
                                                    <span
                                                        class="font-semibold subtotal-com-iva">€{{ number_format($item['subtotalComIva'], 2, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs opacity-80">
                                                    <span>Preço s/ IVA:</span>
                                                    <span
                                                        class="subtotal-sem-iva">€{{ number_format($item['subtotalSemIva'], 2, ',', '.') }}</span>
                                                </div>
                                                <div class="flex justify-between text-xs opacity-80">
                                                    <span>IVA ({{ $ivaRate * 100 }}%):</span>
                                                    <span
                                                        class="subtotal-iva">€{{ number_format($item['subtotalIva'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Totais -->
                                    <div class="flex justify-between mb-2 text-sm">
                                        <span class="opacity-80">Total s/ IVA:</span>
                                        <span id="total-sem-iva">€{{ number_format($totalSemIva, 2, ',', '.') }}</span>
                                    </div>

                                    <div class="flex justify-between mb-2 text-sm">
                                        <span class="opacity-80">Total IVA (23%):</span>
                                        <span id="total-iva">€{{ number_format($totalIva, 2, ',', '.') }}</span>
                                    </div>

                                    <div class="border-t border-gray-700 my-4"></div>

                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total c/ IVA:</span>
                                        <span id="total-com-iva">€{{ number_format($totalComIva, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('order.post') }}">
                                    @csrf
                                    <button
                                        class="mt-6 inline-block text-center bg-gradient-to-r from-brand-green to-brand-blue text-white px-6 py-3 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                                        Proceder para Checkout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
        </section>
    </main>
@endsection
