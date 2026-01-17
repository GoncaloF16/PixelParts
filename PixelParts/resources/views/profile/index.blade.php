@extends('layouts.master')

@section('content')
<section class="container mx-auto px-6 lg:px-16 xl:px-28 pb-12 lg:pb-16">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Perfil</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-gradient-to-r from-brand-green/10 to-brand-blue/10 border border-brand-green/30 text-white px-4 py-3 rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-brand-green"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Layout 2 Colunas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Coluna Esquerda - Info Básica -->
        <div class="lg:col-span-4">
            <div class="bg-gradient-to-br from-gray-900/40 to-gray-900/60 rounded-2xl p-6 border border-gray-700/50 backdrop-blur-sm h-full flex flex-col">
                <!-- Avatar -->
                <div class="flex flex-col items-center text-center mb-6 pb-6 border-b border-border/30">
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand-green via-brand-green to-brand-blue flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-brand-green/20 mb-4">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                    <p class="text-xs text-gray-500 mt-2">Membro desde {{ $user->created_at->format('d/m/Y') }}</p>
                </div>

                <!-- Form Editar Nome -->
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 flex-1 flex flex-col">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Nome</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-3 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Email</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full bg-gray-900/50 border border-gray-800 rounded-lg px-4 py-3 pr-10 text-sm text-gray-300 cursor-not-allowed"/>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-600"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">O email não pode ser alterado</p>
                    </div>

                    <div class="flex-1"></div>

                    <button type="submit" class="w-full bg-[#374151] hover:bg-[#4B5563] text-[#E5E7EB] px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 mt-auto">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Guardar Alterações
                    </button>
                </form>
            </div>
        </div>

        <!-- Coluna Direita - Tabs -->
        <div class="lg:col-span-8">
            <!-- Tabs Navigation -->
            <div class="flex gap-2 border-b border-gray-700/50 mb-6">
                <button onclick="switchTab('addresses')" id="tab-addresses" class="tab-btn px-6 py-3 text-sm font-semibold transition-all border-b-2 border-brand-green text-brand-green">
                    <i data-lucide="map-pin" class="w-4 h-4 inline-block mr-2"></i>
                    Endereços
                </button>
                <button onclick="switchTab('orders')" id="tab-orders" class="tab-btn px-6 py-3 text-sm font-semibold text-gray-400 hover:text-white transition-all border-b-2 border-transparent">
                    <i data-lucide="package" class="w-4 h-4 inline-block mr-2"></i>
                    Pedidos
                </button>
            </div>

            <!-- Addresses Tab -->
            <div id="content-addresses" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Billing Address -->
                    <div class="bg-gradient-to-br from-gray-900/40 to-gray-900/60 rounded-2xl p-6 border border-gray-700/50 backdrop-blur-sm h-full flex flex-col">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-10 h-10 bg-brand-green/10 rounded-lg flex items-center justify-center border border-brand-green/20">
                                <i data-lucide="file-text" class="w-5 h-5 text-brand-green"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Faturação</h3>
                                <p class="text-xs text-gray-400">Dados para emissão de faturas</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.update.billing') }}" class="space-y-4 flex-1 flex flex-col">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Nome Completo</label>
                                <input type="text" name="billing_name" value="{{ $user->billing_name }}"
                            class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                    </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Morada</label>
                                <input type="text" name="billing_address" value="{{ $user->billing_address }}"
                                    class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Código Postal</label>
                                    <input type="text" name="billing_postal_code" value="{{ $user->billing_postal_code }}"
                                        placeholder="1234-567"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Cidade</label>
                                    <input type="text" name="billing_city" value="{{ $user->billing_city }}"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">País</label>
                                    <input type="text" name="billing_country" value="{{ $user->billing_country ?? 'Portugal' }}"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Telefone</label>
                                    <input type="tel" name="billing_phone" value="{{ $user->billing_phone }}"
                                        placeholder="+351 912 345 678"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2">NIF (opcional)</label>
                                <input type="text" name="billing_nif" value="{{ $user->billing_nif }}"
                                    placeholder="123456789"
                                    class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                            </div>

                            <div class="flex-1"></div>

                            <button type="submit" class="w-full bg-[#374151] hover:bg-[#4B5563] text-[#E5E7EB] px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 mt-auto">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Guardar Faturação
                            </button>
                        </form>
                    </div>

                    <!-- Shipping Address -->
                    <div class="bg-gradient-to-br from-gray-900/40 to-gray-900/60 rounded-2xl p-6 border border-gray-700/50 backdrop-blur-sm h-full flex flex-col">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-10 h-10 bg-brand-blue/10 rounded-lg flex items-center justify-center border border-brand-blue/20">
                                <i data-lucide="truck" class="w-5 h-5 text-brand-blue"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Entrega</h3>
                                <p class="text-xs text-gray-400">Endereço de envio das encomendas</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.update.shipping') }}" class="space-y-4 flex-1 flex flex-col">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Nome Completo</label>
                                <input type="text" name="shipping_name" value="{{ $user->shipping_name }}"
                                    class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Morada</label>
                                <input type="text" name="shipping_address" value="{{ $user->shipping_address }}"
                                    class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Código Postal</label>
                                    <input type="text" name="shipping_postal_code" value="{{ $user->shipping_postal_code }}"
                                        placeholder="1234-567"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Cidade</label>
                                    <input type="text" name="shipping_city" value="{{ $user->shipping_city }}"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">País</label>
                                    <input type="text" name="shipping_country" value="{{ $user->shipping_country ?? 'Portugal' }}"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Telefone</label>
                                    <input type="tel" name="shipping_phone" value="{{ $user->shipping_phone }}"
                                        placeholder="+351 912 345 678"
                                        class="w-full bg-gray-900/90 border border-gray-700 rounded-lg px-4 py-2.5 text-sm text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                                </div>
                            </div>

                            <div class="flex-1"></div>

                            <button type="submit" class="w-full bg-[#374151] hover:bg-[#4B5563] text-[#E5E7EB] px-6 py-3 rounded-lg text-sm font-semibold transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 mt-auto">
                                <i data-lucide="save" class="w-4 h-4"></i>
                                Guardar Entrega
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Orders Tab -->
            <div id="content-orders" class="tab-content hidden">
                <div class="bg-gradient-to-br from-gray-900/40 to-gray-900/60 rounded-2xl p-6 border border-gray-700/50 backdrop-blur-sm">
                    @if($orders->count())
                        <div class="space-y-3">
                            @foreach($orders as $order)
                                @php
                                    $statusConfig = [
                                        0 => ['label' => 'Pendente', 'icon' => 'clock', 'color' => 'text-gray-400', 'bg' => 'bg-gray-500/10', 'border' => 'border-gray-500/20'],
                                        1 => ['label' => 'Processando', 'icon' => 'loader', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20'],
                                        2 => ['label' => 'Enviado', 'icon' => 'truck', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/10', 'border' => 'border-yellow-500/20'],
                                        3 => ['label' => 'Entregue', 'icon' => 'check-circle', 'color' => 'text-green-400', 'bg' => 'bg-green-500/10', 'border' => 'border-green-500/20']
                                    ];
                                    $status = $statusConfig[$order->status];
                                @endphp

                                <div class="group bg-gray-900/60 border border-gray-700/60 rounded-xl overflow-hidden hover:border-brand-green/40 transition-all duration-300">
                                    <!-- Header do Pedido -->
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-brand-green/10 rounded-lg flex items-center justify-center border border-brand-green/20">
                                                <i data-lucide="package" class="w-4 h-4 text-brand-green"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-white font-semibold text-sm">Pedido #{{ $order->id }}</h3>
                                                <p class="text-gray-500 text-xs">{{ $order->created_at->format('d/m/Y • H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-[#e5e7eb]">€{{ number_format($order->amount / 100, 2, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->products->sum('quantity') }} {{ $order->products->sum('quantity') == 1 ? 'item' : 'itens' }}</p>
                                        </div>
                                    </div>

                                    <!-- Status and Action -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 {{ $status['bg'] }} {{ $status['border'] }} border px-3 py-1.5 rounded-lg">
                                            <i data-lucide="{{ $status['icon'] }}" class="w-3.5 h-3.5 {{ $status['color'] }}"></i>
                                            <span class="text-xs font-semibold {{ $status['color'] }}">{{ $status['label'] }}</span>
                                        </div>
                                        <button onclick="toggleOrder({{ $order->id }})"
                                            class="text-gray-400 hover:text-brand-green px-3 py-1.5 rounded-lg text-xs font-medium transition-all hover:bg-brand-green/5 flex items-center gap-1.5">
                                            <span>Ver Detalhes</span>
                                            <i id="icon-{{ $order->id }}" data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300"></i>
                                        </button>
                                    </div>
                                    </div>

                                    <!-- Expandable Details -->
                                    <div id="details-{{ $order->id }}" class="hidden border-t border-gray-700/50">
                                        <div class="p-4 bg-gray-900/40 space-y-4">

                                            <!-- Produtos -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-400 uppercase mb-3">Produtos</h4>
                                                <div class="space-y-2">
                                                @foreach($order->products as $item)
                                                    <div class="flex items-center justify-between bg-gray-900/60 border border-gray-700/50 p-3 rounded-lg hover:border-brand-green/20 transition-all">
                                                        <div class="flex items-center gap-3">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                     alt="{{ $item->product->name }}"
                                                                     class="w-10 h-10 rounded-lg object-cover border border-gray-700/50"/>
                                                            @else
                                                                <div class="w-10 h-10 bg-brand-green/10 rounded-lg flex items-center justify-center border border-brand-green/20">
                                                                    <i data-lucide="box" class="w-4 h-4 text-brand-green"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <p class="text-white font-medium text-sm">{{ $item->product->name ?? 'Produto removido' }}</p>
                                                                <p class="text-xs text-gray-500">{{ $item->quantity }}x • €{{ number_format($item->price / 100, 2, ',', '.') }}</p>
                                                            </div>
                                                        </div>
                                                        <p class="text-white font-bold text-sm">€{{ number_format(($item->price * $item->quantity) / 100, 2, ',', '.') }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                            <!-- Info Pagamento -->
                                            <div class="bg-gray-900/60 border border-gray-700/50 rounded-lg p-4">
                                                <h4 class="text-xs font-semibold text-gray-400 uppercase mb-3">Pagamento</h4>
                                                <div class="space-y-2">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-400">Método</span>
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="credit-card" class="w-3.5 h-3.5 text-brand-green"></i>
                                                        <span class="text-white font-medium text-sm">Cartão</span>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-400">ID Transação</span>
                                                    <span class="text-xs text-gray-500 font-mono">***{{ substr($order->stripe_id, -8) }}</span>
                                                </div>
                                                <div class="pt-2 border-t border-gray-700/50">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-white font-semibold text-sm">Total Pago</span>
                                                        <span class="text-xl font-bold text-[#e5e7eb]">€{{ number_format($order->amount / 100, 2, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($orders->hasPages())
                            <div class="mt-8 pt-6 border-t border-gray-700/50">
                                {{ $orders->links('vendor.pagination.tailwind') }}
                            </div>
                        @endif

                        @else
                        <div class="text-center py-20">
                            <div class="w-20 h-20 bg-gray-900/60 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-gray-700/50">
                                <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-600"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white mb-2">Nenhum pedido ainda</h3>
                            <p class="text-gray-400 mb-8">Comece a explorar nossos produtos</p>
                            <a href="{{ route('products.index') }}"
                                class="inline-flex items-center gap-3 bg-[#374151] hover:bg-[#4B5563] text-[#E5E7EB] px-8 py-4 rounded-xl font-semibold transition-all hover:scale-105">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                Explorar Produtos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</section>

<script>
function switchTab(tab) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active state from all tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-brand-green', 'text-brand-green');
        btn.classList.add('border-transparent', 'text-gray-400');
    });

    // Show selected tab content
    document.getElementById('content-' + tab).classList.remove('hidden');

    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.remove('border-transparent', 'text-gray-400');
    activeTab.classList.add('border-brand-green', 'text-brand-green');

    // Reinitialize lucide icons
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

function toggleOrder(id) {
    const details = document.getElementById(`details-${id}`);
    const icon = document.getElementById(`icon-${id}`);
    details.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>
@endsection
