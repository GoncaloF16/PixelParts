@extends('layouts.master')

@section('content')
    <main class="lg:min-h-screen">
        <div class="container mx-auto px-6 py-8">
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
                    <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <div class="product-card" data-name="{{ strtolower($product->name) }}"
                                data-price="{{ $product->price }}" data-brand="{{ strtolower($product->brand) }}"
                                data-category="{{ $product->category ? $product->category->slug : 'uncategorized' }}">

                                <div
                                    class="bg-gray-900 rounded-2xl overflow-hidden hover:shadow-2xl transition-all duration-300 group flex flex-col h-[400px] relative">
                                    <!-- Eye Icon -->
                                    <div
                                        class="absolute top-3 right-3 bg-gray-800/70 backdrop-blur-sm p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                        <i data-lucide="eye" class="w-5 h-5 text-brand-green"></i>
                                    </div>

                                    <!-- Product Image -->
                                    <a href="{{ route('products.details', ['slug' => $product->slug]) }}">
                                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-[210px] object-cover cursor-pointer">
                                    </a>

                                    <!-- NOVO Badge -->
                                    @if ($product->created_at->gt(now()->subDays(7)))
                                        <div
                                            class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded z-10">
                                            NOVO
                                        </div>
                                    @endif

                                    <!-- Product Info -->
                                    <div class="flex flex-col justify-between flex-grow p-4 min-h-0">
                                        <div class="flex-shrink-0">
                                            <h3 class="text-lg font-bold text-gray-200 mb-2 line-clamp-2">
                                                {{ $product->name }}</h3>
                                            <p class="text-sm text-gray-400 leading-relaxed line-clamp-4">
                                                {{ $product->description ?? 'Descrição não disponível' }}
                                            </p>
                                        </div>

                                        <div class="mt-3 flex items-center justify-between flex-shrink-0">
                                            <span class="text-xl font-bold text-gray-200">
                                                €{{ $product->price ? number_format($product->price, 2, ',', '.') : 'Preço indisponível' }}
                                            </span>
                                            <form class="add-to-cart-form" data-product-id="{{ $product->id }}">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit"
                                                    class="bg-gradient-to-r bg-gray-700 hover:bg-gray-600 text-gray-200 font-semibold px-4 py-2 rounded-lg text-sm  transition-transform flex items-center gap-2">
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
                    <div id="noResults" class="hidden text-center py-16">
                        <i class="fas fa-search text-6xl text-text-secondary mb-4"></i>
                        <h3 class="text-2xl font-bold text-text-primary mb-2 text-gray-200">Nenhum produto encontrado</h3>
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

    <script>
        window.routes = {
            cartAdd: "{{ route('cart.add') }}"
        };

        lucide.replace();

        const searchInput = document.getElementById('searchInput');
        const categoryFilters = document.querySelectorAll('.filter-category');
        const brandFilters = document.querySelectorAll('.filter-brand');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const productsGrid = document.getElementById('productsGrid');
        const noResults = document.getElementById('noResults');

        function filterProducts() {
            const searchValue = searchInput ? searchInput.value.toLowerCase() : '';
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

        categoryFilters.forEach(cb => cb.addEventListener('change', filterProducts));
        brandFilters.forEach(cb => cb.addEventListener('change', filterProducts));
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                categoryFilters.forEach(cb => cb.checked = true);
                brandFilters.forEach(cb => cb.checked = true);
                if (searchInput) searchInput.value = '';
                filterProducts();
            });
        }

        filterProducts();
    </script>
@endsection
