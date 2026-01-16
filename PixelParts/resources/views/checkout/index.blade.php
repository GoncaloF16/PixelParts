@extends('layouts.master')

@section('content')
    <main>
        <section class="bg-surface min-h-screen py-16">
            <div class="container mx-auto px-6">

                <!-- Header -->
                <div class="text-center mb-12 animate-fade-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-text-primary mb-4">
                        <span class="text-gradient-brand">Checkout</span>
                    </h2>
                    <p class="text-text-secondary text-lg max-w-2xl mx-auto">
                        Complete os dados para finalizar a sua encomenda.
                    </p>
                </div>

                <form method="POST" action="{{ route('checkout.process') }}" class="max-w-6xl mx-auto">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left Column: Address Forms -->
                        <div class="lg:col-span-2 space-y-8">

                            <!-- Billing Address -->
                            <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-white">
                                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                    Dados de Faturação
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="billing_name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                                        <input type="text" id="billing_name" name="billing_name" required
                                            value="{{ old('billing_name', auth()->user()->billing_name) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="billing_address" class="block text-sm font-medium mb-2">Morada *</label>
                                        <input type="text" id="billing_address" name="billing_address" required
                                            value="{{ old('billing_address', auth()->user()->billing_address) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="billing_city" class="block text-sm font-medium mb-2">Cidade *</label>
                                        <input type="text" id="billing_city" name="billing_city" required
                                            value="{{ old('billing_city', auth()->user()->billing_city) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="billing_postal_code" class="block text-sm font-medium mb-2">Código Postal *</label>
                                        <input type="text" id="billing_postal_code" name="billing_postal_code" required
                                            value="{{ old('billing_postal_code', auth()->user()->billing_postal_code) }}"
                                            placeholder="1234-567"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="billing_country" class="block text-sm font-medium mb-2">País *</label>
                                        <input type="text" id="billing_country" name="billing_country" required
                                            value="{{ old('billing_country', auth()->user()->billing_country ?? 'Portugal') }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="billing_phone" class="block text-sm font-medium mb-2">Telefone *</label>
                                        <input type="tel" id="billing_phone" name="billing_phone" required
                                            value="{{ old('billing_phone', auth()->user()->billing_phone) }}"
                                            placeholder="+351 912 345 678"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="billing_nif" class="block text-sm font-medium mb-2">NIF (opcional)</label>
                                        <input type="text" id="billing_nif" name="billing_nif"
                                            value="{{ old('billing_nif', auth()->user()->billing_nif) }}"
                                            placeholder="123456789"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-white">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold flex items-center gap-2">
                                        <i data-lucide="truck" class="w-5 h-5"></i>
                                        Dados de Entrega
                                    </h3>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" id="same-as-billing" class="w-4 h-4 text-brand-green rounded">
                                        <span class="text-sm">Igual à faturação</span>
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="shipping_name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                                        <input type="text" id="shipping_name" name="shipping_name" required
                                            value="{{ old('shipping_name', auth()->user()->shipping_name) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="shipping_address" class="block text-sm font-medium mb-2">Morada *</label>
                                        <input type="text" id="shipping_address" name="shipping_address" required
                                            value="{{ old('shipping_address', auth()->user()->shipping_address) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium mb-2">Cidade *</label>
                                        <input type="text" id="shipping_city" name="shipping_city" required
                                            value="{{ old('shipping_city', auth()->user()->shipping_city) }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium mb-2">Código Postal *</label>
                                        <input type="text" id="shipping_postal_code" name="shipping_postal_code" required
                                            value="{{ old('shipping_postal_code', auth()->user()->shipping_postal_code) }}"
                                            placeholder="1234-567"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="shipping_country" class="block text-sm font-medium mb-2">País *</label>
                                        <input type="text" id="shipping_country" name="shipping_country" required
                                            value="{{ old('shipping_country', auth()->user()->shipping_country ?? 'Portugal') }}"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>

                                    <div>
                                        <label for="shipping_phone" class="block text-sm font-medium mb-2">Telefone *</label>
                                        <input type="tel" id="shipping_phone" name="shipping_phone" required
                                            value="{{ old('shipping_phone', auth()->user()->shipping_phone) }}"
                                            placeholder="+351 912 345 678"
                                            class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-green text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-white sticky top-20">
                                <h3 class="text-xl font-bold mb-4">Resumo da Encomenda</h3>

                                <div class="space-y-3 mb-6">
                                    @foreach ($cartItems as $item)
                                        <div class="border-b border-gray-700 pb-2">
                                            <div class="flex justify-between text-sm">
                                                <span class="font-medium">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                                <span>€{{ number_format($item['subtotalComIva'], 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-2 mb-4">
                                    <div class="flex justify-between text-sm opacity-80">
                                        <span>Total s/ IVA:</span>
                                        <span>€{{ number_format($totalSemIva, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm opacity-80">
                                        <span>IVA (23%):</span>
                                        <span>€{{ number_format($totalIva, 2, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-700 pt-4 mb-6">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total:</span>
                                        <span>€{{ number_format($totalComIva, 2, ',', '.') }}</span>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-brand-green to-brand-blue text-white px-6 py-3 rounded-lg font-bold text-lg hover:scale-105 transition-transform duration-300 glow-brand shadow-lg flex items-center justify-center gap-2">
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    Proceder para Pagamento
                                </button>

                                <a href="{{ route('cart.index') }}"
                                    class="block w-full text-center mt-3 text-gray-400 hover:text-white transition text-sm">
                                    ← Voltar ao carrinho
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sameAsBillingCheckbox = document.getElementById('same-as-billing');

            // Billing fields
            const billingName = document.getElementById('billing_name');
            const billingAddress = document.getElementById('billing_address');
            const billingCity = document.getElementById('billing_city');
            const billingPostalCode = document.getElementById('billing_postal_code');
            const billingCountry = document.getElementById('billing_country');
            const billingPhone = document.getElementById('billing_phone');

            // Shipping fields
            const shippingName = document.getElementById('shipping_name');
            const shippingAddress = document.getElementById('shipping_address');
            const shippingCity = document.getElementById('shipping_city');
            const shippingPostalCode = document.getElementById('shipping_postal_code');
            const shippingCountry = document.getElementById('shipping_country');
            const shippingPhone = document.getElementById('shipping_phone');

            sameAsBillingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    shippingName.value = billingName.value;
                    shippingAddress.value = billingAddress.value;
                    shippingCity.value = billingCity.value;
                    shippingPostalCode.value = billingPostalCode.value;
                    shippingCountry.value = billingCountry.value;
                    shippingPhone.value = billingPhone.value;
                }
            });

            // Also sync when billing fields change if checkbox is checked
            [billingName, billingAddress, billingCity, billingPostalCode, billingCountry, billingPhone].forEach(field => {
                field.addEventListener('input', function() {
                    if (sameAsBillingCheckbox.checked) {
                        const fieldName = this.id.replace('billing_', 'shipping_');
                        document.getElementById(fieldName).value = this.value;
                    }
                });
            });
        });
    </script>
@endsection
