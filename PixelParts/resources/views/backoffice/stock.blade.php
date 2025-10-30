@extends('layouts.backoffice')

@section('title', 'Gestão de Stock - Backoffice')
@section('page-title', 'Gestão de Stock')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <!-- Filtros -->
        <form method="GET" action="{{ route('backoffice.stock') }}" class="flex flex-col sm:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar produtos..."
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
            <select name="category"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todas as Categorias</option>
                <option value="Processadores" {{ request('category') == 'Processadores' ? 'selected' : '' }}>Processadores
                </option>
                <option value="Placas Gráficas" {{ request('category') == 'Placas Gráficas' ? 'selected' : '' }}>Placas
                    Gráficas</option>
                <option value="Memória RAM" {{ request('category') == 'Memória RAM' ? 'selected' : '' }}>Memória RAM
                </option>
                <option value="Motherboard" {{ request('category') == 'Motherboard' ? 'selected' : '' }}>Motherboard
                </option>
                <option value="Portáteis" {{ request('category') == 'Portáteis' ? 'selected' : '' }}>Portáteis</option>
                <option value="Storage" {{ request('category') == 'Storage' ? 'selected' : '' }}>Storage</option>
                <option value="PSU" {{ request('category') == 'PSU' ? 'selected' : '' }}>PSU</option>
            </select>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filtrar</button>
        </form>

        <!-- Botões de ação -->
        <div class="flex gap-2 mt-4 sm:mt-0">
            <button id="add-product-btn"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar Produto
            </button>

            <a href="{{ route('backoffice.stock.pdf', request()->all()) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exportar PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="products-table" class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 max-w-xs truncate"
                            title="{{ $product->name }}">
                            {{ $product->name }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->stock }}
                        </td>
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if ($product->stock > 10)
                                <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Em
                                    Stock</span>
                            @elseif ($product->stock >= 5 && $product->stock <= 10)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Stock
                                    Médio</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">Pouco
                                    Stock</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative overflow-visible">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="inline-flex items-center gap-2 rounded-md bg-gray-600 text-white px-4 py-2 text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-dropdown-trigger>
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                                    </svg>
                                    Ações
                                </button>

                                <div class="absolute right-0 mt-2 w-40 origin-top-right rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 hidden z-50"
                                    data-dropdown-menu>
                                    <div class="py-1">
                                        <button
                                            class="view-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $product->id }}">
                                            <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                            Visualizar
                                        </button>
                                        <button
                                            class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $product->id }}">
                                            <i data-lucide="edit" class="w-4 h-4 text-gray-500"></i>
                                            Editar
                                        </button>
                                        <button
                                            class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $product->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    @if($products->hasPages())
        <div class="mt-6">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Add/Edit/View Product Modal -->
    <div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 py-8">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto my-8">
                <form id="product-form" class="p-6 space-y-4" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" id="product-id" name="product_id">
                    <input type="hidden" id="modal-mode" value="add">

                    <!-- Header -->
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                        <h3 id="product-modal-title" class="text-xl font-semibold text-gray-800">Adicionar Produto</h3>
                        <div class="flex gap-2">
                            <span id="page-indicator" class="text-sm text-gray-500">Página 1 de 2</span>
                        </div>
                    </div>

                    <!-- Página 1: Dados Básicos -->
                    <div id="page-1" class="page-content">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="product-category" class="block text-sm font-medium text-gray-700 mb-1">Categoria <span class="text-red-500">*</span></label>
                                <select id="product-category" name="category_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Selecionar...</option>
                                    @foreach ($allCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="product-name" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                                <input type="text" id="product-name" name="name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="product-brand" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                                <input type="text" id="product-brand" name="brand"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="product-price" class="block text-sm font-medium text-gray-700 mb-1">Preço <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" id="product-price" name="price" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="product-stock" class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
                                <input type="number" id="product-stock" name="stock" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="product-image" class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
                                <input type="file" id="product-image" name="image" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div id="current-image-preview" class="mt-2 hidden">
                                    <img id="current-image" src="" alt="Imagem atual" class="h-20 w-20 object-cover rounded">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="product-description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                                <textarea id="product-description" name="description" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Página 2: Especificações, Características e Compatibilidade -->
                    <div id="page-2" class="page-content hidden">
                        <div class="space-y-6">
                            <!-- Especificações -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Especificações</label>
                                    <button type="button" id="add-specification-btn"
                                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Adicionar
                                    </button>
                                </div>
                                <div id="specifications-container" class="space-y-2">
                                    <!-- Dinâmico via JS -->
                                </div>
                            </div>

                            <!-- Características -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Características</label>
                                    <button type="button" id="add-feature-btn"
                                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Adicionar
                                    </button>
                                </div>
                                <div id="features-container" class="space-y-2">
                                    <!-- Dinâmico via JS -->
                                </div>
                            </div>

                            <!-- Compatibilidade -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Compatibilidade</label>
                                    <button type="button" id="add-compatibility-btn"
                                        class="text-sm text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        Adicionar
                                    </button>
                                </div>
                                <div id="compatibility-container" class="space-y-2">
                                    <!-- Dinâmico via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer com navegação -->
                    <div class="flex justify-between items-center gap-2 pt-6 border-t border-gray-200">
                        <div>
                            <button type="button" id="prev-page-btn"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition hidden">
                                ← Anterior
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="cancel-product-btn"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Cancelar
                            </button>
                            <button type="button" id="next-page-btn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Próximo →
                            </button>
                            <button type="submit" id="submit-product-btn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition hidden">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
