document.addEventListener('DOMContentLoaded', function () {
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
        const searchTerm = searchInput?.value.toLowerCase() || '';

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
            const name = card.dataset.name.toLowerCase();
            const category = card.dataset.category;
            const brand = card.dataset.brand;

            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(category);
            const matchesBrand = selectedBrands.length === 0 || selectedBrands.includes(brand);

            if (matchesSearch && matchesCategory && matchesBrand) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (productCount)
            productCount.textContent = `Mostrando ${visibleCount} produto${visibleCount !== 1 ? 's' : ''}`;

        if (productsGrid && noResults) {
            if (visibleCount === 0) {
                productsGrid.style.display = 'none';
                noResults.classList.remove('hidden');
            } else {
                productsGrid.style.display = 'grid';
                noResults.classList.add('hidden');
            }
        }
    }

    // Sort products
    function sortProducts() {
        if (!sortSelect || !productsGrid) return;
        const sortValue = sortSelect.value;
        const cardsArray = Array.from(productCards);

        cardsArray.sort((a, b) => {
            switch (sortValue) {
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

        cardsArray.forEach(card => productsGrid.appendChild(card));
    }

    // Clear filters
    function clearFilters() {
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.value = 'default';

        categoryFilters.forEach(cb => cb.checked = true);
        brandFilters.forEach(cb => cb.checked = true);

        applyFilters();
        sortProducts();
    }

    // Event listeners
    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (sortSelect) sortSelect.addEventListener('change', sortProducts);
    if (clearFiltersBtn) clearFiltersBtn.addEventListener('click', clearFilters);

    categoryFilters.forEach(cb => cb.addEventListener('change', applyFilters));
    brandFilters.forEach(cb => cb.addEventListener('change', applyFilters));

    // Header scroll effect
    const header = document.getElementById('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        });
    }

    // Review form toggle
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewForm = document.getElementById('reviewForm');
    if (writeReviewBtn && reviewForm) {
        writeReviewBtn.addEventListener('click', () => {
            reviewForm.classList.toggle('hidden');
        });
    }

    // Interactive stars
    const stars = document.querySelectorAll('input[name="rating"] + label');
    if (stars.length > 0) {
        stars.forEach((star, idx) => {
            star.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    s.classList.toggle('text-yellow-400', i <= idx);
                });
            });
        });
    }

    // Add to cart (AJAX)
    const forms = document.querySelectorAll('.add-to-cart-form');
    forms.forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            const originalWidth = submitBtn ? submitBtn.offsetWidth : null;

            if (submitBtn) {
                // Preserve original width
                if (originalWidth) {
                    submitBtn.style.width = `${originalWidth}px`;
                }
                submitBtn.innerHTML = '<i data-lucide="loader-2" class="animate-spin w-5 h-5"></i>';
                submitBtn.disabled = true;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            try {
                const response = await fetch(window.routes.cartAdd, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });

                const data = await response.json();

                // Check if response is unauthenticated and redirect
                if (response.status === 401 && data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (data.success) {
                    // Update cart count
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.cart_count);
                    }

                    // Show success toast
                    if (typeof window.showToast === 'function') {
                        window.showToast(data.message || 'Produto adicionado ao carrinho!');
                    }
                } else {
                    if (typeof window.showToast === 'function') {
                        window.showToast(data.message || 'Erro ao adicionar o produto', true);
                    }
                }
            } catch (err) {
                console.error('Erro AJAX:', err);
                if (typeof window.showToast === 'function') {
                    window.showToast('Erro de ligação ao servidor', true);
                }
            } finally {
                if (submitBtn) {
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        // Remove fixed width
                        submitBtn.style.width = '';
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }, 1000);
                }
            }
        });
    });

    // Quantity counter
    const quantityDisplay = document.getElementById('quantity-display');
const quantityInput = document.getElementById('quantity-input');
let quantity = 1;
const maxStock = parseInt(quantityDisplay.dataset.stock || 99);

function updateQuantityDisplay() {
    quantityDisplay.textContent = quantity;
    quantityInput.value = quantity;
}

window.increaseQuantity = function () {
    if (quantity < maxStock) {
        quantity++;
        updateQuantityDisplay();
    }
};

window.decreaseQuantity = function () {
    if (quantity > 1) {
        quantity--;
        updateQuantityDisplay();
    }
};

updateQuantityDisplay();

});
