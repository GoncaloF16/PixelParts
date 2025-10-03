@extends('layouts.master')

@section('content')
    <main class="pt-24 min-h-screen">
        <div class="container mx-auto px-6 py-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-text-primary mb-6">
                    Todos os <span class="text-gradient-brand">Produtos</span>
                </h1>

                <!-- Search Bar -->
                <div class="relative max-w-2xl p-[2px] rounded-lg bg-gradient-to-r from-brand-green to-brand-blue">
                    <input type="text" id="searchInput" placeholder="Pesquisar produtos..."
                        class="w-full px-6 py-4 bg-surface rounded-lg text-text-primary placeholder-text-secondary focus:outline-none">
                    <i class="fas fa-search absolute right-6 top-1/2 -translate-y-1/2 text-text-secondary"></i>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filters Sidebar -->
                <aside class="lg:w-72 flex-shrink-0">
                    <div class="bg-surface rounded-lg p-6 sticky top-28">
                        <h2 class="text-xl font-bold text-text-primary mb-6">Filtros</h2>

                        <!-- Category Filter -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-text-primary mb-3">Categoria</h3>
                            <div class="space-y-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox"
                                            class="filter-category w-4 h-4 rounded text-brand-blue focus:ring-brand-blue"
                                            value="{{ $category->slug }}" checked>
                                        <span class="text-text-secondary group-hover:text-text-primary transition-colors">
                                            {{ $category->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-text-primary mb-3">Marca</h3>
                            <div class="space-y-2">
                                @foreach ($brands as $brand)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox"
                                            class="filter-brand w-4 h-4 rounded text-brand-blue focus:ring-brand-blue"
                                            value="{{ strtolower($brand) }}" checked>
                                        <span class="text-text-secondary group-hover:text-text-primary transition-colors">
                                            {{ $brand }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button id="clearFilters"
                            class="w-full bg-surface text-text-primary px-4 py-2 rounded-lg border border-border/20 hover:bg-surface-dark transition-colors">
                            Limpar Filtros
                        </button>
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6">
                        <p id="productCount" class="text-text-secondary">Mostrando {{ $products->count() }} produtos</p>

                        <div class="relative">
                            <select id="sortSelect"
                                class="appearance-none bg-surface rounded-lg px-6 py-3 pr-10 text-text-primary focus:outline-none focus:ring-2 focus:ring-brand-blue cursor-pointer">
                                <option value="default">Ordenar por</option>
                                <option value="name-asc">Nome (A-Z)</option>
                                <option value="name-desc">Nome (Z-A)</option>
                                <option value="price-asc">Preço (Menor)</option>
                                <option value="price-desc">Preço (Maior)</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <a href="{{ route('products.details', ['slug' => $product->slug]) }}"><div class="product-card" data-name="{{ strtolower($product->name) }}"
                                data-price="{{ $product->price }}" data-brand="{{ strtolower($product->brand) }}"
                                data-category="{{ $product->category ? $product->category->slug : 'uncategorized' }}">
                                <div
                                    class="bg-surface rounded-lg overflow-hidden group transition-transform duration-300 hover:scale-[1.02] flex flex-col h-full">

                                    <!-- Product Image -->
                                    <div class="relative aspect-square overflow-hidden">
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-5 flex flex-col justify-between h-full">
                                        <div class="mb-4">
                                            <h3
                                                class="text-lg font-bold text-text-primary group-hover:text-brand-blue transition-colors">
                                                {{ $product->name }}
                                            </h3>
                                            <p class="text-text-secondary text-sm line-clamp-2 mt-1">
                                                {{ $product->description }}
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <span class="text-xl font-bold text-gradient-brand">
                                                €{{ number_format($product->price, 2, ',', '.') }}
                                            </span>
                                            <button
                                                class="bg-gradient-to-r from-brand-green to-brand-blue text-white px-4 py-2 rounded-lg font-semibold hover:scale-105 transition-transform flex items-center gap-2">
                                                <i data-lucide="shopping-cart"></i>
                                                Adicionar
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- No Results -->
                    <div id="noResults" class="hidden text-center py-16">
                        <i class="fas fa-search text-6xl text-text-secondary mb-4"></i>
                        <h3 class="text-2xl font-bold text-text-primary mb-2">Nenhum produto encontrado</h3>
                        <p class="text-text-secondary">Tenta ajustar os filtros ou pesquisar por outro termo.</p>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        {{ $products->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Filtering Script -->
    <script>
        const searchInput = document.getElementById('searchInput');
        const categoryFilters = document.querySelectorAll('.filter-category');
        const brandFilters = document.querySelectorAll('.filter-brand');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const productsGrid = document.getElementById('productsGrid');
        const noResults = document.getElementById('noResults');

        function filterProducts() {
            const searchValue = searchInput.value.toLowerCase();
            const selectedCategories = Array.from(categoryFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            const selectedBrands = Array.from(brandFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            let visibleCount = 0;

            productsGrid.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name;
                const category = card.dataset.category;
                const brand = card.dataset.brand;

                const matchesSearch = name.includes(searchValue);
                const matchesCategory = selectedCategories.includes(category);
                const matchesBrand = selectedBrands.includes(brand);

                if (matchesSearch && matchesCategory && matchesBrand) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            noResults.classList.toggle('hidden', visibleCount > 0);
            document.getElementById('productCount').textContent = `Mostrando ${visibleCount} produtos`;
        }

        // Eventos
        searchInput.addEventListener('input', filterProducts);
        categoryFilters.forEach(cb => cb.addEventListener('change', filterProducts));
        brandFilters.forEach(cb => cb.addEventListener('change', filterProducts));
        clearFiltersBtn.addEventListener('click', () => {
            categoryFilters.forEach(cb => cb.checked = true);
            brandFilters.forEach(cb => cb.checked = true);
            searchInput.value = '';
            filterProducts();
        });

        filterProducts();
    </script>
@endsection
