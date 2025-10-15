@extends('layouts.master')

@section('content')
<main class="pt-10">
    <section class="py-20 bg-surface min-h-screen">
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

            @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-up">
                <!-- Lista de produtos -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $id => $item)
                        @php
                            $ivaRate = 0.23;
                            $unitPriceComIva = $item['price'];
                            $subtotalComIva = $unitPriceComIva * $item['quantity'];
                        @endphp

                        <div id="cart-item-{{ $item['product_id'] }}" class="bg-gray-900 rounded-lg shadow p-4 flex justify-between items-center text-white cart-item">
                            <div>
                                <span class="font-semibold text-lg block">{{ $item['name'] }}</span>
                                <span class="text-sm opacity-80">Quantidade:
                                    <span class="item-quantity">{{ $item['quantity'] }}</span>
                                </span>
                            </div>

                            <div class="text-right">
                                <button data-id="{{ $item['product_id'] }}" class="remove-from-cart text-red-500 hover:text-red-700 text-xs">
                                    Remover
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Resumo Detalhado -->
                @php
                    $totalSemIva = 0;
                    $totalIva = 0;
                    $totalComIva = 0;
                @endphp

                <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-white flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold mb-4">Resumo Detalhado</h3>

                        <!-- Tabela detalhada por produto -->
                        <div class="space-y-3 mb-6">
                            @foreach($cartItems as $item)
                                @php
                                    $unitPriceComIva = $item['price'];
                                    $unitPriceSemIva = $unitPriceComIva / (1 + $ivaRate);
                                    $unitIva = $unitPriceComIva - $unitPriceSemIva;

                                    $subtotalComIva = $unitPriceComIva * $item['quantity'];
                                    $subtotalSemIva = $unitPriceSemIva * $item['quantity'];
                                    $subtotalIva = $unitIva * $item['quantity'];

                                    $totalSemIva += $subtotalSemIva;
                                    $totalIva += $subtotalIva;
                                    $totalComIva += $subtotalComIva;
                                @endphp

                                <div class="border-b border-gray-700 pb-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="font-semibold">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                        <span class="font-semibold subtotal-com-iva">€{{ number_format($subtotalComIva, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs opacity-80">
                                        <span>Preço s/ IVA:</span>
                                        <span class="subtotal-sem-iva">€{{ number_format($subtotalSemIva, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs opacity-80">
                                        <span>IVA (23%):</span>
                                        <span class="subtotal-iva">€{{ number_format($subtotalIva, 2, ',', '.') }}</span>
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

                    <a href="#"
                       class="mt-6 inline-block text-center bg-gradient-to-r from-brand-green to-brand-blue text-white px-6 py-3 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                        Proceder para Checkout
                    </a>
                </div>
            </div>

            @else
            <!-- Carrinho vazio -->
            <div class="text-center py-20 animate-fade-up">
                <i data-lucide="shopping-cart" class="w-16 h-16 text-text-secondary mx-auto mb-6"></i>
                <h3 class="text-2xl font-bold text-text-primary mb-2">O teu carrinho está vazio</h3>
                <p class="text-text-secondary mb-6">Explora os nossos produtos e adiciona alguns itens ao teu carrinho.</p>
                <a href="{{ route('products.index') }}" class="pt-2 bg-gradient-to-r from-brand-green to-brand-blue text-white px-8 py-4 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand">
                    Explorar Produtos
                </a>
            </div>
            @endif
        </div>
    </section>
</main>
@endsection
