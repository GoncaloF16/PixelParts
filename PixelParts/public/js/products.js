document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const productCards = document.querySelectorAll('.product-card');
    const productsGrid = document.getElementById('productsGrid');
    const noResults = document.getElementById('noResults');
    const productCount = document.getElementById('productCount');

    // Get all filter checkboxes
    const categoryFilters = document.querySelectorAll('.filter-category');
    const brandFilters = document.querySelectorAll('.filter-brand');

    // Apply filters and search
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();

        // Get selected categories
        const selectedCategories = Array.from(categoryFilters)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        // Get selected brands
        const selectedBrands = Array.from(brandFilters)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        let visibleCount = 0;

        productCards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;
            const brand = card.dataset.brand;

            // Check if matches search
            const matchesSearch = name.includes(searchTerm);

            // Check if matches category filter
            const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category);

            // Check if matches brand filter
            const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(brand);

            // Show/hide card
            if (matchesSearch && matchesCategory && matchesBrand) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update product count
        productCount.textContent = `Mostrando ${visibleCount} produto${visibleCount !== 1 ? 's' : ''}`;

        // Show/hide no results message
        if (visibleCount === 0) {
            productsGrid.style.display = 'none';
            noResults.classList.remove('hidden');
        } else {
            productsGrid.style.display = 'grid';
            noResults.classList.add('hidden');
        }
    }

    // Sort products
    function sortProducts() {
        const sortValue = sortSelect.value;
        const cardsArray = Array.from(productCards);

        cardsArray.sort((a, b) => {
            switch(sortValue) {
                case 'name-asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name-desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                case 'price-asc':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-desc':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                default:
                    return 0;
            }
        });

        // Reorder DOM elements
        cardsArray.forEach(card => {
            productsGrid.appendChild(card);
        });
    }

    // Clear all filters
    function clearFilters() {
        searchInput.value = '';
        sortSelect.value = 'default';

        categoryFilters.forEach(cb => cb.checked = true);
        brandFilters.forEach(cb => cb.checked = true);

        applyFilters();
        sortProducts();
    }

    // Event listeners
    searchInput.addEventListener('input', applyFilters);
    sortSelect.addEventListener('change', sortProducts);
    clearFiltersBtn.addEventListener('click', clearFilters);

    categoryFilters.forEach(cb => {
        cb.addEventListener('change', applyFilters);
    });

    brandFilters.forEach(cb => {
        cb.addEventListener('change', applyFilters);
    });

    // Header scroll effect
    const header = document.getElementById('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
