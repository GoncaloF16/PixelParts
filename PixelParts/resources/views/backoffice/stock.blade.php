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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="products-table" class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->stock }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end gap-2 min-w-[120px]">
                                <button class="edit-btn text-blue-600 hover:text-blue-900 mr-2">Editar</button>
                                <button class="delete-btn text-red-600 hover:text-red-900">Excluir</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="product-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4">
            <form id="product-form" class="p-6 space-y-4" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" id="product-id" name="product_id">

                <div class="p-6 border-b border-gray-200">
                    <h3 id="product-modal-title" class="text-xl font-semibold text-gray-800">Adicionar Produto</h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="product-category" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                        <select id="product-category" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecionar...</option>
                            @foreach ($allCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="product-name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <input type="text" id="product-name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="product-brand" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
                        <input type="text" id="product-brand" name="brand"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="product-description"
                            class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea id="product-description" name="description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="product-price" class="block text-sm font-medium text-gray-700 mb-1">Preço</label>
                        <input type="number" step="0.01" id="product-price" name="price" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="product-stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" id="product-stock" name="stock" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="product-image" class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
                        <input type="file" id="product-image" name="image" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-6 border-t border-gray-200">
                    <button type="button" id="cancel-product-btn"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    </div>

@endsection
