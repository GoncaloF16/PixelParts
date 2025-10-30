@extends('layouts.backoffice')

@section('title', 'Dashboard - Backoffice')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500">Total de Produtos</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{$totalProducts}}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500">Encomendas (Mês)</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">342</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500">Utilizadores</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{$totalUsers}}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500">Receita (Mês)</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">€45.2k</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Encomendas Recentes</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-2024-001</p>
                        <p class="text-sm text-gray-500">João Silva</p>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Enviado</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-2024-002</p>
                        <p class="text-sm text-gray-500">Maria Santos</p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Pendente</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-2024-003</p>
                        <p class="text-sm text-gray-500">Carlos Oliveira</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Processando</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
   <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Produtos com Stock Baixo</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse ($products as $product)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $product->category_name }}</p>
                            </div>
                            @if ($product->stock < 5)
                                <span class="text-red-600 font-semibold">{{ $product->stock }} unidades</span>
                            @else
                                <span class="text-orange-600 font-semibold">{{ $product->stock }} unidades</span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500">Nenhum produto com stock baixo</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($products->hasPages())
                <div class="px-6 pb-6">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
</div>
@endsection
