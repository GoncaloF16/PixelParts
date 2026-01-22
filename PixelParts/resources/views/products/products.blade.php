@extends('layouts.master')

@section('content')
    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none;" class="fixed inset-0 bg-background/80 backdrop-blur-sm z-50 items-center justify-center">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-brand-green mb-4"></div>
            <p class="text-text-primary text-lg font-semibold">A carregar produtos...</p>
        </div>
    </div>

    <main class="lg:min-h-screen">
        <div class="container mx-auto px-6 pt-0 pb-2 md:py-8">
            <!-- Breadcrumb -->
            <nav class="text-sm text-text-secondary mb-2" aria-label="Breadcrumb">
                <ol class="list-reset flex">
                    <li>
                        <a href="{{ route('home') }}" class="hover:underline text-base">Home</a>
                    </li>
                    <li><span class="mx-2 text-base">></span></li>
                    <li> <a href="{{ route('products.index') }}" class="text-text-primary font-semibold text-base">Produtos
                        </a>
                    </li>
                </ol>
            </nav>
            <br>
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-text-primary mb-6">
                    Todos os <span class="text-gradient-brand">Produtos</span>
                </h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <aside id="filtersSidebar" class="lg:w-72 flex-shrink-0 hidden lg:block">
                    <div class="bg-gray-900 rounded-2xl p-6 lg:sticky lg:top-28 border border-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-200">Filtros</h2>
                            <button id="closeFiltersBtn" class="lg:hidden text-gray-400 hover:text-gray-200 transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-200 mb-4 flex items-center gap-2">
                                <i data-lucide="layers" class="w-4 h-4 text-brand-green"></i>
                                Categoria
                            </h3>
                            <div class="space-y-3">
                                @foreach ($categories as $category)
                                    @if($category->slug !== 'componentes')
                                    <label class="flex items-center gap-3 cursor-pointer group relative">
                                        <div class="relative flex items-center">
                                            <input type="checkbox"
                                                class="filter-category peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-gray-700 bg-gray-800 transition-all checked:border-brand-green checked:bg-brand-green"
                                                value="{{ $category->slug }}"
                                                data-category-name="{{ strtolower($category->name) }}">
                                            <i data-lucide="check" class="w-3 h-3 text-gray-200 absolute left-1 top-1 pointer-events-none hidden peer-checked:block"></i>
                                        </div>
                                        <span class="text-gray-400 group-hover:text-gray-200 transition-colors text-sm">
                                            {{ $category->name }}
                                        </span>
                                    </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-800 my-6"></div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-200 mb-4 flex items-center gap-2">
                                <i data-lucide="tag" class="w-4 h-4 text-brand-blue"></i>
                                Marca
                            </h3>
                            <div class="space-y-3">
                                @foreach ($brands as $brand)
                                    <label class="flex items-center gap-3 cursor-pointer group relative">
                                        <div class="relative flex items-center">
                                            <input type="checkbox"
                                                class="filter-brand peer h-5 w-5 cursor-pointer appearance-none rounded border-2 border-gray-700 bg-gray-800 transition-all checked:border-brand-blue checked:bg-brand-blue"
                                                value="{{ strtolower($brand) }}"
                                                data-brand-name="{{ strtolower($brand) }}">
                                            <i data-lucide="check" class="w-3 h-3 text-gray-200 absolute left-1 top-1 pointer-events-none hidden peer-checked:block"></i>
                                        </div>
                                        <span class="text-gray-400 group-hover:text-gray-200 transition-colors text-sm">
                                            {{ $brand }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-800 my-6"></div>

                        <button id="clearFilters"
                            class="w-full bg-gradient-to-r from-gray-800 to-gray-700 hover:from-gray-700 hover:to-gray-600 text-gray-200 px-4 py-3 rounded-lg font-semibold transition-all duration-300 flex items-center justify-center gap-2 border border-gray-700 hover:border-gray-600">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Limpar Filtros
                        </button>
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="flex-1">
                    <!-- Mobile Filters Toggle Button -->
                    <button id="toggleFiltersBtn" class="lg:hidden w-full bg-surface text-text-primary px-4 py-3 rounded-lg border border-border/20 hover:bg-surface-dark transition-colors mb-6 flex items-center justify-center gap-2">
                        <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                        Filtros
                    </button>

                    <div class="flex items-center justify-between mb-6">
                        <p id="productCount" class="text-text-secondary">Mostrando {{ $products->count() }} produtos</p>

                        <div class="relative">
                            <select id="sortSelect"
                                class="appearance-none w-full bg-surface rounded-lg px-6 py-3 pr-10 text-text-primary focus:outline-none focus:ring-2 focus:ring-brand-blue cursor-pointer">
                                <option value="default" class="text-gray-200">Ordenar por</option>
                                <option value="name-asc" class="text-gray-200">Nome (A-Z)</option>
                                <option value="name-desc" class="text-gray-200">Nome (Z-A)</option>
                                <option value="price-asc" class="text-gray-200">Preço (Menor)</option>
                                <option value="price-desc" class="text-gray-200">Preço (Maior)</option>
                            </select>
                            <i data-lucide="chevron-down"
                                class="text-gray-200 absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-fr">
                        @foreach ($products as $product)
                            <div class="product-card min-h-[440px]" data-name="{{ strtolower($product->name) }}"
                                data-price="{{ $product->price }}" data-brand="{{ strtolower($product->brand) }}"
                                data-category="{{ $product->category ? $product->category->slug : 'uncategorized' }}">

                                <div
                                    class="bg-gray-900 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 h-full relative group flex flex-col">
                                    <!-- Eye Icon -->
                                    <div
                                        class="absolute top-3 right-3 bg-gray-800/70 backdrop-blur-sm p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                        <i data-lucide="eye" class="w-5 h-5 text-brand-green"></i>
                                    </div>

                                    <!-- Product Image -->
                                    <a href="{{ route('products.details', ['slug' => $product->slug]) }}">
                                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-[210px] object-cover cursor-pointer flex-shrink-0">
                                    </a>

                                    <!-- NOVO Badge -->
                                    @if ($product->created_at->gt(now()->subDays(7)))
                                        <div
                                            class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded z-10">
                                            NOVO
                                        </div>
                                    @endif

                                    <!-- Desconto Badge -->
                                    @if($product->discount_percentage && $product->discount_percentage > 0)
                                        <div class="absolute top-3 {{ $product->created_at->gt(now()->subDays(7)) ? 'left-20' : 'left-3' }} bg-yellow-500 text-gray-900 text-xs font-bold px-2 py-1 rounded z-10">
                                            -€{{ number_format($product->discount_amount, 2, ',', '.') }}
                                        </div>
                                    @endif

                                    <!-- Product Info -->
                                    <div class="p-4 flex flex-col flex-1">
                                        <div class="flex-1 min-h-0">
                                            <h3 class="text-lg font-bold text-gray-200 mb-2 line-clamp-2">
                                                {{ $product->name }}
                                            </h3>

                                            <p class="text-sm text-gray-400 leading-relaxed line-clamp-3">
                                                {{ $product->description ?? 'Descrição não disponível' }}
                                            </p>
                                        </div>

                                        <div class="mt-4 flex items-center justify-between flex-shrink-0">
                                            <div class="flex flex-col">
                                                @if($product->discount_percentage && $product->discount_percentage > 0)
                                                    <span class="text-sm text-gray-400 line-through">
                                                        €{{ number_format($product->price, 2, ',', '.') }}
                                                    </span>
                                                    <span class="text-xl font-bold bg-gradient-to-r from-brand-green to-brand-blue bg-clip-text text-transparent">
                                                        €{{ number_format($product->discounted_price, 2, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-xl font-bold text-gray-100">
                                                        €{{ $product->price ? number_format($product->price, 2, ',', '.') : 'Preço indisponível' }}
                                                    </span>
                                                @endif
                                            </div>
                                            <form class="add-to-cart-form flex-shrink-0" data-product-id="{{ $product->id }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit"
                                                    class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-3 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 transition w-[120px] justify-center">
                                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> Adicionar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- No Results -->
                    @if($products->count() === 0)
                    <div id="noResults" class="text-center py-16">
                        <i class="fas fa-search text-6xl text-text-secondary mb-4"></i>
                        <h3 class="text-2xl font-bold text-text-primary mb-2 text-gray-200">Nenhum produto encontrado</h3>
                        <p class="text-text-secondary">Tenta ajustar os filtros ou pesquisar por outro termo.</p>
                    </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $products->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.routes = {
            cartAdd: "{{ route('cart.add') }}"
        };

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Mobile Filters Toggle
            const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
            const closeFiltersBtn = document.getElementById('closeFiltersBtn');
            const filtersSidebar = document.getElementById('filtersSidebar');

            if (toggleFiltersBtn && filtersSidebar) {
                toggleFiltersBtn.addEventListener('click', () => {
                    filtersSidebar.classList.remove('hidden', 'lg:block');
                    filtersSidebar.classList.add('fixed', 'inset-0', 'z-50', 'bg-surface-dark/95', 'backdrop-blur-sm', 'p-6', 'overflow-y-auto', 'block');
                    lucide.createIcons();
                });
            }

            if (closeFiltersBtn && filtersSidebar) {
                closeFiltersBtn.addEventListener('click', () => {
                    filtersSidebar.classList.add('hidden');
                    filtersSidebar.classList.remove('fixed', 'inset-0', 'z-50', 'bg-surface-dark/95', 'backdrop-blur-sm', 'p-6', 'overflow-y-auto', 'block');

                    // Restaurar classes originais para desktop
                    if (window.innerWidth >= 1024) {
                        filtersSidebar.classList.remove('hidden');
                        filtersSidebar.classList.add('lg:block');
                    }
                });
            }

            const categoryFilters = document.querySelectorAll('.filter-category');
            const brandFilters = document.querySelectorAll('.filter-brand');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const productsGrid = document.getElementById('productsGrid');

            // === URL Parameter Handling ===
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q') || '';

            // Marcar checkboxes baseado nos parâmetros da URL (filtros aplicados)
            const selectedBrandsFromUrl = urlParams.getAll('brand[]');

            // Suporta tanto "categoria" (links do menu/hamburger) como "categoria[]" (checkboxes)
            let selectedCategoriesFromUrl = urlParams.getAll('categoria[]');
            const singleCategoryFromUrl = urlParams.get('categoria');
            if (singleCategoryFromUrl) {
                selectedCategoriesFromUrl.push(singleCategoryFromUrl);
            }

            // Se há filtros na URL, marcar os checkboxes
            if (selectedBrandsFromUrl.length > 0 || selectedCategoriesFromUrl.length > 0) {
                categoryFilters.forEach(cb => {
                    if (selectedCategoriesFromUrl.includes(cb.value)) {
                        cb.checked = true;
                    }
                });

                brandFilters.forEach(cb => {
                    if (selectedBrandsFromUrl.includes(cb.value)) {
                        cb.checked = true;
                    }
                });
            }
            // Auto-select based on visible products when searching
            else if (searchQuery.length > 0) {
                const visibleBrands = new Set();
                const visibleCategories = new Set();

                // Coletar marcas e categorias dos produtos retornados
                productsGrid.querySelectorAll('.product-card').forEach(card => {
                    const brand = card.dataset.brand;
                    const category = card.dataset.category;
                    if (brand) visibleBrands.add(brand);
                    if (category) visibleCategories.add(category);
                });

                // Marcar checkboxes correspondentes
                brandFilters.forEach(cb => {
                    if (visibleBrands.has(cb.value)) {
                        cb.checked = true;
                    }
                });

                categoryFilters.forEach(cb => {
                    if (visibleCategories.has(cb.value)) {
                        cb.checked = true;
                    }
                });
            }

            // === Filter Functions ===
            let isRedirecting = false;

            function showLoading() {
                const overlay = document.getElementById('loadingOverlay');
                if (overlay) {
                    overlay.style.display = 'flex';
                }
            }

            function updateFilters() {
                if (isRedirecting) return;
                isRedirecting = true;
                showLoading();

                const url = new URLSearchParams();

                // Keep search parameter if it exists
                const searchQuery = new URLSearchParams(window.location.search).get('q');
                if (searchQuery) {
                    url.set('q', searchQuery);
                }

                // Add selected categories
                const selectedCategories = Array.from(categoryFilters)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                selectedCategories.forEach(cat => url.append('categoria[]', cat));

                // Add selected brands
                const selectedBrands = Array.from(brandFilters)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                selectedBrands.forEach(brand => url.append('brand[]', brand));

                // Redirect with new parameters (always page 1)
                window.location.href = "{{ route('products.index') }}" + (url.toString() ? '?' + url.toString() : '');
            }

            // Filter event listeners
            categoryFilters.forEach(cb => cb.addEventListener('change', updateFilters));
            brandFilters.forEach(cb => cb.addEventListener('change', updateFilters));

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', () => {
                    if (isRedirecting) return;
                    isRedirecting = true;

                    // Mostrar loading e redirecionar imediatamente
                    showLoading();
                    window.location.href = "{{ route('products.index') }}";
                });
            }

            // === Pagination Loader ===
            // Usar setTimeout para garantir que os links foram renderizados
            setTimeout(() => {
                const paginationContainer = document.querySelector('.mt-8.flex.justify-center');
                if (paginationContainer) {
                    const paginationLinks = paginationContainer.querySelectorAll('a');
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', (e) => {
                            if (!link.getAttribute('aria-disabled') && !link.classList.contains('disabled')) {
                                showLoading();
                            }
                        });
                    });
                }
            }, 100);
        });
    </script>
@endsection
