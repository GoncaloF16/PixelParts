@extends('layouts.backoffice')

@section('title', 'Gestão de Stock - Backoffice')
@section('page-title', 'Gestão de Stock')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
        <!-- Filtros -->
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="search-products" value="{{ request('search') }}" placeholder="Pesquisar produtos..."
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
            <div class="relative">
                <select id="category-filter"
                    class="appearance-none px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer hover:border-gray-400 transition min-w-[180px]">
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
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 mt-4 sm:mt-0">
            <button id="add-product-btn"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Adicionar Produto
            </button>

            <a href="{{ route('backoffice.stock.excel', request()->all()) }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exportar Excel
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('backoffice.stock.bulk-delete') }}">
        @csrf
        <!-- Preserve current filters after delete -->
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif

        <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                        <input id="select-all" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="products-table" class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="selected[]" value="{{ $product->id }}" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </td>
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

                                <div class="absolute right-0 mt-2 w-40 origin-top-right rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 hidden z-[9999]"
                                    data-dropdown-menu>
                                    <div class="py-1">
                                        <button type="button"
                                            class="view-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $product->id }}">
                                            <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                            Visualizar
                                        </button>
                                        <button type="button"
                                            class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $product->id }}">
                                            <i data-lucide="edit" class="w-4 h-4 text-gray-500"></i>
                                            Editar
                                        </button>
                                        <button type="button"
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

        <!-- Bulk actions + Pagination aligned -->
        <div class="mt-6 flex items-center justify-between gap-3 flex-col sm:flex-row">
            <button id="bulk-delete-btn" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
                </svg>
                Apagar Seleção
            </button>

            @if($products->hasPages())
                <div class="self-stretch sm:self-auto">
                    {{ $products->appends(request()->query())->links('vendor.pagination.backoffice') }}
                </div>
            @endif
        </div>
    </form>

    <!-- Bulk removal confirmation modal -->
    <div id="bulk-delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Apagar produtos selecionados?</h3>
                        <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
                <p id="bulk-delete-count" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-bulk-delete"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-bulk-delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Sim, apagar
                    </button>
                </div>
            </div>
        </div>
    </div>

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

                    <!-- Page 1: Basic Data -->
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
                                <label for="product-discount" class="block text-sm font-medium text-gray-700 mb-1">Desconto (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="product-discount" name="discount_percentage"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ex: 20 para 20%">
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

                    <!-- Page 2: Specifications, Features and Compatibility -->
                    <div id="page-2" class="page-content hidden">
                        <div class="space-y-6">
                            <!-- Specifications -->
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
                                    <!-- Dynamic via JS -->
                                </div>
                            </div>

                            <!-- Features -->
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
                                    <!-- Dynamic via JS -->
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
                                    <!-- Dynamic via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer with navigation -->
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
                            <button type="button" id="submit-product-btn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition hidden">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product save confirmation modal -->
    <div id="product-save-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="product-save-confirm-title">Confirmar ação</h3>
                        <p class="text-sm text-gray-500 mt-1" id="product-save-confirm-message">Pretende guardar as alterações?</p>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-product-save-confirm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-product-save"
                        class="px-4 py-2 bg-blue-400 text-white rounded-lg hover:bg-yellow-500 transition">
                        Sim, guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual product removal confirmation modal -->
    <div id="product-delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Apagar produto?</h3>
                        <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
                <p id="product-delete-confirm-message" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-product-delete-confirm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-product-delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Sim, apagar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = () => Array.from(document.querySelectorAll('.row-checkbox'));
        const bulkBtn = document.getElementById('bulk-delete-btn');
        const bulkModal = document.getElementById('bulk-delete-modal');
        const bulkForm = bulkBtn.closest('form');
        const confirmBtn = document.getElementById('confirm-bulk-delete');
        const cancelBtn = document.getElementById('cancel-bulk-delete');
        const countText = document.getElementById('bulk-delete-count');

        function updateBulkState() {
            const anyChecked = rowCheckboxes().some(cb => cb.checked);
            bulkBtn.disabled = !anyChecked;

            // Atualizar estado do select-all (indeterminate)
            const total = rowCheckboxes().length;
            const checked = rowCheckboxes().filter(cb => cb.checked).length;
            if (checked === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            } else if (checked === total) {
                selectAll.checked = true;
                selectAll.indeterminate = false;
            } else {
                selectAll.checked = false;
                selectAll.indeterminate = true;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes().forEach(cb => cb.checked = selectAll.checked);
                updateBulkState();
            });
        }

        rowCheckboxes().forEach(cb => cb.addEventListener('change', updateBulkState));

        // Open modal when delete selection is clicked
        if (bulkBtn) {
            bulkBtn.addEventListener('click', function() {
                const checked = rowCheckboxes().filter(cb => cb.checked).length;
                if (checked > 0) {
                    countText.textContent = `Tem a certeza que pretende apagar ${checked} produto${checked > 1 ? 's' : ''}?`;
                    bulkModal.classList.remove('hidden');
                    bulkModal.classList.add('flex');
                }
            });
        }

        // Confirm removal
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                bulkForm.submit();
            });
        }

        // Cancelar modal
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                bulkModal.classList.add('hidden');
                bulkModal.classList.remove('flex');
            });
        }

        // Fechar modal ao clicar fora
        if (bulkModal) {
            bulkModal.addEventListener('click', function(e) {
                if (e.target === bulkModal) {
                    bulkModal.classList.add('hidden');
                    bulkModal.classList.remove('flex');
                }
            });
        }

        // Inicializar estado no load
        updateBulkState();
    })();
    </script>
@endsection
