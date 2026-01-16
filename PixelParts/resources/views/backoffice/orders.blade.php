@extends('layouts.backoffice')

@section('title', 'Gestão de Encomendas - Backoffice')
@section('page-title', 'Gestão de Encomendas')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="search-orders" placeholder="Pesquisar encomendas..."
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
            <div class="relative">
                <select id="status-filter"
                    class="appearance-none px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer hover:border-gray-400 transition min-w-[180px]">
                    <option value="">Todos os Estados</option>
                    <option value="0">Pendente</option>
                    <option value="1">Processando</option>
                    <option value="2">Enviado</option>
                    <option value="3">Entregue</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Encomenda</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="orders-table" class="bg-white divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <!-- Order Number -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #ORD-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- Cliente -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            €{{ number_format($order->amount / 100, 2, ',', '.') }}
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @switch($order->status)
                                @case(0)
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">Pendente</span>
                                    @break
                                @case(1)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Processando</span>
                                    @break
                                @case(2)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Enviado</span>
                                    @break
                                @case(3)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Entregue</span>
                                    @break
                            @endswitch
                        </td>

                        <!-- Data -->
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="inline-flex items-center gap-2 rounded-md bg-gray-600 text-white px-4 py-2 text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-dropdown-trigger>
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <button
                                            class="view-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $order->id }}">
                                            <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                            Visualizar
                                        </button>
                                        <button
                                            class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                            <i data-lucide="edit" class="w-4 h-4 text-gray-500"></i>
                                            Editar Estado
                                        </button>
                                        <button
                                            class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $order->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-lg font-medium">Nenhuma encomenda encontrada</p>
                                <p class="text-sm">Tente ajustar os filtros de pesquisa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links('vendor.pagination.backoffice') }}
        </div>
    @endif

    <!-- View Order Modal -->
    <div id="view-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800" id="view-order-number"></h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                        <p id="view-customer-name" class="text-gray-900"></p>
                        <p id="view-customer-email" class="text-sm text-gray-500"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                        <p id="view-order-date" class="text-gray-900"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <span id="view-order-status" class="px-3 py-1 text-xs font-medium rounded-full inline-block"></span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                        <p id="view-order-total" class="text-gray-900 font-semibold text-lg"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Produtos</label>
                    <div id="view-order-products" class="space-y-2"></div>
                </div>

                <!-- Billing Address -->
                <div id="view-billing-section" class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Endereço de Faturação</label>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-1">
                        <p id="view-billing-name" class="font-medium text-gray-900"></p>
                        <p id="view-billing-address" class="text-sm text-gray-600"></p>
                        <p id="view-billing-city-postal" class="text-sm text-gray-600"></p>
                        <p id="view-billing-country" class="text-sm text-gray-600"></p>
                        <p id="view-billing-phone" class="text-sm text-gray-600"></p>
                        <p id="view-billing-nif" class="text-sm text-gray-600"></p>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div id="view-shipping-section">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Endereço de Entrega</label>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-1">
                        <p id="view-shipping-name" class="font-medium text-gray-900"></p>
                        <p id="view-shipping-address" class="text-sm text-gray-600"></p>
                        <p id="view-shipping-city-postal" class="text-sm text-gray-600"></p>
                        <p id="view-shipping-country" class="text-sm text-gray-600"></p>
                        <p id="view-shipping-phone" class="text-sm text-gray-600"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stripe ID</label>
                    <p id="view-stripe-id" class="text-gray-600 text-sm font-mono"></p>
                </div>
            </div>
            <div class="flex justify-end gap-2 p-6 border-t border-gray-200">
                <button type="button" id="close-view-btn"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div id="order-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Editar Estado da Encomenda</h3>
            </div>
            <form id="order-form" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="order-id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="order-status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0">Pendente</option>
                        <option value="1">Processando</option>
                        <option value="2">Enviado</option>
                        <option value="3">Entregue</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancel-order-btn"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                    <button type="button" id="save-order-btn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Save confirmation modal -->
    <div id="save-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Sucesso</h3>
                        <p class="text-sm text-gray-500 mt-1" id="save-confirm-message">Encomenda atualizada com sucesso</p>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button id="close-save-confirm"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div id="delete-confirm-modal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar exclusão</h3>
                        <p class="text-sm text-gray-500 mt-1">Tem certeza que deseja excluir esta encomenda?</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button id="cancel-delete"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                    <button id="confirm-delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dropdown functionality
        document.querySelectorAll('[data-dropdown-trigger]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;

                // Fechar outros dropdowns e resetar posicionamento
                document.querySelectorAll('[data-dropdown-menu]').forEach(m => {
                    if (m !== menu) {
                        m.classList.add('hidden');
                        m.style.position = '';
                        m.style.top = '';
                        m.style.left = '';
                    }
                });

                menu.classList.toggle('hidden');

                // Adjust position if outside viewport
                if (!menu.classList.contains('hidden')) {
                    const rect = button.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;

                    // Usar posicionamento fixed para evitar cortes
                    menu.style.position = 'fixed';
                    menu.style.left = `${rect.right - menu.offsetWidth}px`;

                    // Se o menu ficaria fora da viewport por baixo, abrir para cima
                    if (rect.bottom + menu.offsetHeight > viewportHeight - 20) {
                        menu.style.top = `${rect.top - menu.offsetHeight - 8}px`;
                    } else {
                        menu.style.top = `${rect.bottom + 8}px`;
                    }
                } else {
                    // Reset positioning when closing
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                }
            });
        });

        document.addEventListener('click', function() {
            document.querySelectorAll('[data-dropdown-menu]').forEach(menu => {
                menu.classList.add('hidden');
                menu.style.position = '';
                menu.style.top = '';
                menu.style.left = '';
            });
        });

        // Search functionality
        const searchInput = document.getElementById('search-orders');
        const statusFilter = document.getElementById('status-filter');

        function applyFilters() {
            const search = searchInput.value;
            const status = statusFilter.value;
            const url = new URL(window.location.href);

            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');

            window.location.href = url.toString();
        }

        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') applyFilters();
        });

        statusFilter.addEventListener('change', applyFilters);

        // Set filter values from URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('search')) searchInput.value = urlParams.get('search');
        if (urlParams.has('status')) statusFilter.value = urlParams.get('status');

        // View order
        let currentOrderId = null;

        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const orderId = this.dataset.id;

                try {
                    const response = await fetch(`/backoffice/orders/${orderId}`);
                    const data = await response.json();
                    const order = data.order;

                    document.getElementById('view-order-number').textContent = data.order_number;
                    document.getElementById('view-customer-name').textContent = order.user.name;
                    document.getElementById('view-customer-email').textContent = order.user.email;
                    document.getElementById('view-order-date').textContent = new Date(order.created_at).toLocaleString('pt-PT');
                    const formatter = new Intl.NumberFormat('pt-PT', { style: 'currency', currency: 'EUR' });
                    document.getElementById('view-order-total').textContent = formatter.format((data.amount_cents || 0) / 100);
                    document.getElementById('view-stripe-id').textContent = order.stripe_id || 'N/A';

                    const statusBadge = document.getElementById('view-order-status');
                    statusBadge.textContent = data.status_label;
                    statusBadge.className = 'px-3 py-1 text-xs font-medium rounded-full inline-block ';

                    switch(order.status) {
                        case 0: statusBadge.classList.add('bg-yellow-100', 'text-yellow-800'); break;
                        case 1: statusBadge.classList.add('bg-blue-100', 'text-blue-800'); break;
                        case 2:
                        case 3: statusBadge.classList.add('bg-green-100', 'text-green-800'); break;
                    }

                    // Display products
                    const productsContainer = document.getElementById('view-order-products');
                    productsContainer.innerHTML = '';

                    order.products.forEach(product => {
                        const productDiv = document.createElement('div');
                        productDiv.className = 'flex justify-between items-center p-3 bg-gray-50 rounded-lg';
                        const name = product.product && product.product.name ? product.product.name : `Produto ID: ${product.product_id}`;
                        const priceCents = Number(product.price_display_cents ?? 0);
                        productDiv.innerHTML = `
                            <div>
                                <p class=\"font-medium text-gray-900\">${name}</p>
                                <p class=\"text-sm text-gray-500\">Quantidade: ${product.quantity}</p>
                            </div>
                            <p class=\"font-semibold text-gray-900\">${formatter.format(priceCents / 100)}</p>
                        `;
                        productsContainer.appendChild(productDiv);
                    });

                    // Display billing address
                    const billingSection = document.getElementById('view-billing-section');
                    if (order.billing_name || order.billing_address) {
                        billingSection.style.display = 'block';
                        document.getElementById('view-billing-name').textContent = order.billing_name || 'N/A';
                        document.getElementById('view-billing-address').textContent = order.billing_address || '';
                        document.getElementById('view-billing-city-postal').textContent =
                            `${order.billing_postal_code || ''} ${order.billing_city || ''}`.trim();
                        document.getElementById('view-billing-country').textContent = order.billing_country || '';
                        document.getElementById('view-billing-phone').textContent =
                            order.billing_phone ? `Tel: ${order.billing_phone}` : '';
                        document.getElementById('view-billing-nif').textContent =
                            order.billing_nif ? `NIF: ${order.billing_nif}` : '';
                    } else {
                        billingSection.style.display = 'none';
                    }

                    // Display shipping address
                    const shippingSection = document.getElementById('view-shipping-section');
                    if (order.shipping_name || order.shipping_address) {
                        shippingSection.style.display = 'block';
                        document.getElementById('view-shipping-name').textContent = order.shipping_name || 'N/A';
                        document.getElementById('view-shipping-address').textContent = order.shipping_address || '';
                        document.getElementById('view-shipping-city-postal').textContent =
                            `${order.shipping_postal_code || ''} ${order.shipping_city || ''}`.trim();
                        document.getElementById('view-shipping-country').textContent = order.shipping_country || '';
                        document.getElementById('view-shipping-phone').textContent =
                            order.shipping_phone ? `Tel: ${order.shipping_phone}` : '';
                    } else {
                        shippingSection.style.display = 'none';
                    }

                    const viewModal = document.getElementById('view-modal');
                    viewModal.classList.remove('hidden');
                    viewModal.classList.add('flex');
                } catch (error) {
                    console.error('Erro ao carregar encomenda:', error);
                }
            });
        });

        document.getElementById('close-view-btn').addEventListener('click', function() {
            const viewModal = document.getElementById('view-modal');
            viewModal.classList.add('hidden');
            viewModal.classList.remove('flex');
        });

        // Edit order
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentOrderId = this.dataset.id;
                document.getElementById('order-id').value = currentOrderId;
                document.getElementById('order-status').value = this.dataset.status;
                const editModal = document.getElementById('order-modal');
                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
            });
        });

        document.getElementById('cancel-order-btn').addEventListener('click', function() {
            const editModal = document.getElementById('order-modal');
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        });

        document.getElementById('save-order-btn').addEventListener('click', async function() {
            const orderId = document.getElementById('order-id').value;
            const status = document.getElementById('order-status').value;

            try {
                const response = await fetch(`/backoffice/orders/${orderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();

                document.getElementById('order-modal').classList.add('hidden');
                document.getElementById('save-confirm-message').textContent = data.message;
                document.getElementById('save-confirm-modal').classList.remove('hidden');
                document.getElementById('save-confirm-modal').classList.add('flex');

                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } catch (error) {
                console.error('Erro ao atualizar encomenda:', error);
            }
        });

        document.getElementById('close-save-confirm').addEventListener('click', function() {
            document.getElementById('save-confirm-modal').classList.add('hidden');
            document.getElementById('save-confirm-modal').classList.remove('flex');
        });

        // Delete order
        let deleteOrderId = null;

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                deleteOrderId = this.dataset.id;
                document.getElementById('delete-confirm-modal').classList.remove('hidden');
                document.getElementById('delete-confirm-modal').classList.add('flex');
            });
        });

        document.getElementById('cancel-delete').addEventListener('click', function() {
            document.getElementById('delete-confirm-modal').classList.add('hidden');
            document.getElementById('delete-confirm-modal').classList.remove('flex');
        });

        document.getElementById('confirm-delete').addEventListener('click', async function() {
            try {
                const response = await fetch(`/backoffice/orders/${deleteOrderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                document.getElementById('delete-confirm-modal').classList.add('hidden');
                document.getElementById('delete-confirm-modal').classList.remove('flex');

                window.location.reload();
            } catch (error) {
                console.error('Erro ao excluir encomenda:', error);
            }
        });

        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endsection
