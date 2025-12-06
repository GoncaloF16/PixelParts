@extends('layouts.master')

@section('content')
<section class="container mx-auto px-6 lg:px-16 xl:px-28 pb-12 lg:pb-16">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Perfil</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Card Perfil -->
        <div class="lg:col-span-4">
            <div class="h-full bg-gradient-to-br from-surface-card to-surface-dark rounded-2xl p-6 border border-border/30 backdrop-blur-sm">

                <!-- Avatar -->
                <div class="flex flex-col items-center text-center mb-6 pb-6 border-b border-border/30">
                    <div class="relative mb-3">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-brand-green via-brand-green to-brand-blue flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-brand-green/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-brand-green rounded-lg flex items-center justify-center shadow-lg">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </div>
                    </div>
                    <h2 class="text-lg font-bold text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Nome</label>
                        <input type="text" name="name" value="{{ $user->name }}"
                            class="w-full bg-surface-dark/80 border border-border/40 rounded-lg px-3 py-2.5 text-sm text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-green/50 focus:border-brand-green/50 transition-all"/>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Email</label>
                        <div class="relative">
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full bg-surface-dark/40 border border-border/20 rounded-lg px-3 py-2.5 pr-10 text-sm text-gray-900 cursor-not-allowed"/>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-600"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 uppercase mb-2">Membro desde</label>
                        <input type="text" value="{{ $user->created_at->format('d/m/Y') }}" disabled
                            class="w-full bg-surface-dark/40 border border-border/20 rounded-lg px-3 py-2.5 text-sm text-gray-900 cursor-not-allowed"/>
                    </div>

                    <button type="submit" class="w-full bg-brand-green hover:bg-brand-green/90 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all shadow-lg shadow-brand-green/20 hover:shadow-brand-green/30 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 mt-6">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Guardar Alterações
                    </button>
                </form>
            </div>
        </div>

        <!-- Card Encomendas -->
        <div class="lg:col-span-8">
            <div class="h-full bg-gradient-to-br from-surface-card to-surface-dark rounded-2xl p-6 border border-border/30 backdrop-blur-sm">

                <!-- Header -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-border/30">
                    <div>
                        <h2 class="text-xl font-bold text-white mb-1">Pedidos</h2>
                        <p class="text-gray-400 text-sm">Histórico de compras</p>
                    </div>
                    <div class="bg-brand-green/10 text-brand-green px-3 py-1.5 rounded-lg font-bold text-sm border border-brand-green/20">
                        {{ $orders->total() }}
                    </div>
                </div>

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

                            <div class="group bg-surface-dark/60 border border-border/40 rounded-xl overflow-hidden hover:border-brand-green/40 transition-all duration-300">
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
                                <div id="details-{{ $order->id }}" class="hidden border-t border-border/30">
                                    <div class="p-4 bg-surface-dark/40 space-y-4">

                                        <!-- Produtos -->
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-400 uppercase mb-3">Produtos</h4>
                                            <div class="space-y-2">
                                                @foreach($order->products as $item)
                                                    <div class="flex items-center justify-between bg-surface-dark/60 border border-border/30 p-3 rounded-lg hover:border-brand-green/20 transition-all">
                                                        <div class="flex items-center gap-3">
                                                            @if($item->product && $item->product->image)
                                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                     alt="{{ $item->product->name }}"
                                                                     class="w-10 h-10 rounded-lg object-cover border border-border/30"/>
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
                                        <div class="bg-surface-dark/60 border border-border/30 rounded-lg p-4">
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
                                                <div class="pt-2 border-t border-border/30">
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
                        <div class="mt-8 pt-6 border-t border-border/30">
                            {{ $orders->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif

                @else
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-surface-dark/60 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-border/30">
                            <i data-lucide="shopping-bag" class="w-10 h-10 text-gray-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Nenhum pedido ainda</h3>
                        <p class="text-gray-400 mb-8">Comece a explorar nossos produtos</p>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-brand-green to-brand-blue text-white px-8 py-4 rounded-xl font-semibold hover:shadow-xl hover:shadow-brand-green/30 transition-all hover:scale-105">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            Explorar Produtos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<script>
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
