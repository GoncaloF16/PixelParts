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

const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewForm = document.getElementById('reviewForm');

    if(writeReviewBtn){
        writeReviewBtn.addEventListener('click', () => {
            reviewForm.classList.toggle('hidden');
        });
    }

    // Stars interativas
    const stars = document.querySelectorAll('input[name="rating"] + label');
    stars.forEach((star, idx) => {
        star.addEventListener('click', () => {
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i <= idx);
            });
        });
    });

    // Seleciona todos os forms de adicionar ao carrinho
  const forms = document.querySelectorAll(".add-to-cart-form");

forms.forEach((form) => {
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(form);

        fetch(window.routes.cartAdd, {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const cartCount = document.querySelector("#cart-count");
                if (cartCount) cartCount.textContent = data.cart_count;

                // Clonar template do toast
                const template = document.querySelector("#toast-template > div");
                const toast = template.cloneNode(true);

                // Atualizar mensagem
                toast.querySelector(".toast-message").textContent = "Produto adicionado ao carrinho!";

                // Adicionar ao body
                document.body.appendChild(toast);

                // Inicializar ícones Lucide
                if (typeof lucide !== 'undefined') lucide.createIcons();

                // Mostrar com animação
                setTimeout(() => {
                    toast.style.opacity = '1';
                    toast.style.transform = 'translateY(0)';
                }, 10);

                // Auto-fechar após 3 segundos
                setTimeout(() => closeToast(toast), 3000);
            } else {
                console.error("Erro ao adicionar o produto");
            }
        })
        .catch(err => console.error("Erro AJAX:", err));
    });
});

function closeToast(buttonOrToast) {
    const toast = buttonOrToast.tagName === 'DIV' ? buttonOrToast : buttonOrToast.parentElement;
    if (!toast) return;
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(16px)';
    setTimeout(() => toast.remove(), 300);
}



