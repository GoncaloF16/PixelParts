document.addEventListener("DOMContentLoaded", () => {
    // --- ELEMENTOS ---
    const productModal = document.getElementById("product-modal");
    const cancelProductBtn = document.getElementById("cancel-product-btn");
    const productForm = document.getElementById("product-form");
    const addProductBtn = document.getElementById("add-product-btn");
    const productsTable = document.getElementById("products-table");

    // Filters
    const searchInput = document.getElementById("search-products");
    const categoryFilter = document.getElementById("category-filter");

    // === Page Navigation ===
    const page1 = document.getElementById("page-1");
    const page2 = document.getElementById("page-2");
    const prevPageBtn = document.getElementById("prev-page-btn");
    const nextPageBtn = document.getElementById("next-page-btn");
    const submitBtn = document.getElementById("submit-product-btn");
    const pageIndicator = document.getElementById("page-indicator");

    // === Confirmation Modals ===
    const productSaveConfirmModal = document.getElementById("product-save-confirm-modal");
    const productSaveConfirmTitle = document.getElementById("product-save-confirm-title");
    const productSaveConfirmMessage = document.getElementById("product-save-confirm-message");
    const confirmProductSaveBtn = document.getElementById("confirm-product-save");
    const cancelProductSaveConfirmBtn = document.getElementById("cancel-product-save-confirm");

    const productDeleteConfirmModal = document.getElementById("product-delete-confirm-modal");
    const productDeleteConfirmMessage = document.getElementById("product-delete-confirm-message");
    const confirmProductDeleteBtn = document.getElementById("confirm-product-delete");
    const cancelProductDeleteConfirmBtn = document.getElementById("cancel-product-delete-confirm");

    let pendingDeleteProductId = null;
    let currentPage = 1;
    let modalMode = 'add'; // 'add', 'edit', 'view'

    /* ------------------ FILTROS ------------------ */
    function applyFilters() {
        const search = searchInput.value;
        const category = categoryFilter.value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('category', category);

        if (!search) url.searchParams.delete('search');
        if (!category) url.searchParams.delete('category');

        window.location.href = url.toString();
    }

    // Search on typing (debounce)
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });

    // Category filter
    categoryFilter.addEventListener('change', applyFilters);

    // Load filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.has('category')) {
        categoryFilter.value = urlParams.get('category');
    }

    // === Navigation Functions ===
    function showPage(pageNumber) {
        currentPage = pageNumber;

        if (pageNumber === 1) {
            page1.classList.remove("hidden");
            page2.classList.add("hidden");
            prevPageBtn.classList.add("hidden");
            nextPageBtn.classList.remove("hidden");
            submitBtn.classList.add("hidden");
            pageIndicator.textContent = "Página 1 de 2";
        } else {
            page1.classList.add("hidden");
            page2.classList.remove("hidden");
            prevPageBtn.classList.remove("hidden");
            nextPageBtn.classList.add("hidden");

            if (modalMode !== 'view') {
                submitBtn.classList.remove("hidden");
            }
            pageIndicator.textContent = "Página 2 de 2";
        }
    }

    prevPageBtn.addEventListener("click", () => showPage(1));
    nextPageBtn.addEventListener("click", () => showPage(2));

    // === Dynamic Fields Functions ===
    function addSpecificationField(key = '', value = '') {
        const container = document.getElementById("specifications-container");
        const div = document.createElement("div");
        div.className = "flex gap-2";
        div.innerHTML = `
            <input type="text" name="specifications[key][]" value="${key}" placeholder="Chave (ex: Chipset)"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" ${modalMode === 'view' ? 'readonly' : ''}>
            <input type="text" name="specifications[value][]" value="${value}" placeholder="Valor (ex: Intel Z790)"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" ${modalMode === 'view' ? 'readonly' : ''}>
            ${modalMode !== 'view' ? `<button type="button" class="remove-field px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>` : ''}
        `;
        container.appendChild(div);

        if (modalMode !== 'view') {
            div.querySelector(".remove-field")?.addEventListener("click", () => div.remove());
        }

        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function addFeatureField(feature = '') {
        const container = document.getElementById("features-container");
        const div = document.createElement("div");
        div.className = "flex gap-2";
        div.innerHTML = `
            <input type="text" name="features[]" value="${feature}" placeholder="Característica (ex: RGB integrado)"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" ${modalMode === 'view' ? 'readonly' : ''}>
            ${modalMode !== 'view' ? `<button type="button" class="remove-field px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>` : ''}
        `;
        container.appendChild(div);

        if (modalMode !== 'view') {
            div.querySelector(".remove-field")?.addEventListener("click", () => div.remove());
        }

        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function addCompatibilityField(compatibility = '') {
        const container = document.getElementById("compatibility-container");
        const div = document.createElement("div");
        div.className = "flex gap-2";
        div.innerHTML = `
            <input type="text" name="compatibility[]" value="${compatibility}" placeholder="Compatibilidade (ex: Intel 12ª/13ª Geração)"
                class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm" ${modalMode === 'view' ? 'readonly' : ''}>
            ${modalMode !== 'view' ? `<button type="button" class="remove-field px-3 py-2 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>` : ''}
        `;
        container.appendChild(div);

        if (modalMode !== 'view') {
            div.querySelector(".remove-field")?.addEventListener("click", () => div.remove());
        }

        if (window.lucide && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    document.getElementById("add-specification-btn")?.addEventListener("click", () => addSpecificationField());
    document.getElementById("add-feature-btn")?.addEventListener("click", () => addFeatureField());
    document.getElementById("add-compatibility-btn")?.addEventListener("click", () => addCompatibilityField());

    // === Modal Functions ===
    function clearDynamicFields() {
        document.getElementById("specifications-container").innerHTML = '';
        document.getElementById("features-container").innerHTML = '';
        document.getElementById("compatibility-container").innerHTML = '';
    }

    function setFormMode(mode) {
        modalMode = mode;
        const allInputs = productForm.querySelectorAll('input, textarea, select');
        const addButtons = productForm.querySelectorAll('[id^="add-"]');
        const removeButtons = productForm.querySelectorAll('.remove-field');

        if (mode === 'view') {
            allInputs.forEach(input => {
                if (input.type !== 'hidden' && input.type !== 'file') {
                    input.setAttribute('readonly', 'readonly');
                    input.setAttribute('disabled', 'disabled');
                    input.classList.add('bg-gray-100', 'cursor-not-allowed');
                }
                if (input.type === 'file') {
                    input.setAttribute('disabled', 'disabled');
                    input.classList.add('cursor-not-allowed');
                }
            });
            addButtons.forEach(btn => btn.classList.add('hidden'));
            removeButtons.forEach(btn => btn.classList.add('hidden'));
            submitBtn.classList.add('hidden');
            nextPageBtn.textContent = 'Próximo →';
        } else {
            allInputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
                input.classList.remove('bg-gray-100', 'cursor-not-allowed');
            });
            addButtons.forEach(btn => btn.classList.remove('hidden'));
            removeButtons.forEach(btn => btn.classList.remove('hidden'));
            nextPageBtn.textContent = 'Próximo →';
        }
    }

    function openProductModal() {
        modalMode = 'add';
        document.getElementById("product-modal-title").textContent = "Adicionar Produto";
        productForm.reset();
        document.getElementById("product-id").value = "";
        document.getElementById("current-image-preview").classList.add("hidden");
        productForm.action = "/backoffice/stock";
        productForm.method = "POST";

        const methodInput = productForm.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        clearDynamicFields();
        setFormMode('add');
        showPage(1);
        productModal.style.display = "flex";
        productModal.classList.remove("hidden");
    }

    async function loadProductData(productId, mode = 'edit') {
        try {
            const response = await fetch(`/backoffice/stock/${productId}`, {
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                }
            });

            if (!response.ok) throw new Error('Erro ao carregar produto');

            const data = await response.json();
            const product = data.product;

            // Fill basic data
            document.getElementById("product-id").value = product.id;
            document.getElementById("product-name").value = product.name || "";
            document.getElementById("product-brand").value = product.brand || "";
            document.getElementById("product-description").value = product.description || "";
            document.getElementById("product-price").value = product.price || "";
            document.getElementById("product-discount").value = product.discount_percentage || "";
            document.getElementById("product-stock").value = product.stock || "";
            document.getElementById("product-category").value = product.category_id || "";

            // Mostrar imagem atual se existir
            if (product.image) {
                // Check if it's an external URL
                if (product.image.startsWith('http://') || product.image.startsWith('https://')) {
                    document.getElementById("current-image").src = product.image;
                } else {
                    // Local storage path
                    document.getElementById("current-image").src = `/storage/${product.image}`;
                }
                document.getElementById("current-image-preview").classList.remove("hidden");
            } else {
                document.getElementById("current-image-preview").classList.add("hidden");
            }

            // Clear dynamic fields
            clearDynamicFields();

            // Configurar modo ANTES de adicionar campos
            modalMode = mode;

            // Fill specifications
            if (product.specifications && product.specifications.length > 0) {
                product.specifications.forEach(spec => {
                    addSpecificationField(spec.key, spec.value);
                });
            }

            // Fill features
            if (product.features && product.features.length > 0) {
                product.features.forEach(feature => {
                    addFeatureField(feature.feature);
                });
            }

            // Fill compatibility
            if (product.compatibility && product.compatibility.length > 0) {
                product.compatibility.forEach(comp => {
                    addCompatibilityField(comp.compatible_with);
                });
            }

            // Apply mode styles to basic fields
            setFormMode(mode);

            if (mode === 'edit') {
                document.getElementById("product-modal-title").textContent = "Editar Produto";
                productForm.action = `/backoffice/stock/${product.id}`;
                productForm.method = "POST";

                let methodInput = productForm.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    methodInput.value = "PUT";
                    productForm.appendChild(methodInput);
                }
            } else if (mode === 'view') {
                document.getElementById("product-modal-title").textContent = "Visualizar Produto";
            }

            showPage(1);
            productModal.style.display = "flex";
            productModal.classList.remove("hidden");

            // Small delay to ensure modal stays open
            setTimeout(() => {
                productModal.style.display = "flex";
                productModal.classList.remove("hidden");
            }, 10);

        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao carregar dados do produto');
        }
    }

    function closeProductModal() {
        productModal.style.display = "none";
        productModal.classList.add("hidden");
        showPage(1);
    }

    // --- MODAL EVENTS ---
    if (addProductBtn) addProductBtn.addEventListener("click", openProductModal);
    cancelProductBtn.addEventListener("click", closeProductModal);
    productModal.addEventListener("click", (e) => {
        if (e.target === productModal) closeProductModal();
    });

    // Prevent modal from closing immediately after opening
    productModal.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    // === Save Confirmation ===
    submitBtn.addEventListener("click", function(e) {
        e.preventDefault();

        if (modalMode === 'view') {
            return; // Don't allow submission in view mode
        }

        const productId = document.getElementById("product-id").value;

        if (productId) {
            productSaveConfirmTitle.textContent = "Confirmar edição";
            productSaveConfirmMessage.textContent = "Pretende guardar as alterações ao produto?";
        } else {
            productSaveConfirmTitle.textContent = "Confirmar adição";
            productSaveConfirmMessage.textContent = "Pretende adicionar este novo produto?";
        }

        productSaveConfirmModal.classList.remove('hidden');
        productSaveConfirmModal.classList.add('flex');
    });

    confirmProductSaveBtn.addEventListener("click", () => {
        productSaveConfirmModal.classList.add('hidden');
        productSaveConfirmModal.classList.remove('flex');
        submitProductForm();
    });

    cancelProductSaveConfirmBtn.addEventListener("click", () => {
        productSaveConfirmModal.classList.add('hidden');
        productSaveConfirmModal.classList.remove('flex');
    });

    // === AJAX Form Submission ===
    function submitProductForm() {

        const productId = document.getElementById("product-id").value;
        const url = productId ? `/backoffice/stock/${productId}` : "/backoffice/stock";
        const formData = new FormData(productForm);

        if (productId) formData.append("_method", "PUT");

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                Accept: "application/json",
            },
            body: formData,
        })
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(async err => {
            const text = await err.text();
            console.error("Erro Laravel:", text);
            alert("Erro ao salvar produto. Veja console para detalhes.");
        });
    }

    // === Removal Confirmation ===
    productsTable.querySelectorAll(".delete-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = btn.dataset.id;
            pendingDeleteProductId = productId;

            // Close the dropdown manually
            const dropdown = btn.closest('[data-dropdown-menu]');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }

            const productName = btn.closest('tr').querySelector('td:nth-child(2)').textContent.trim();
            productDeleteConfirmMessage.textContent = `Tem a certeza que pretende apagar o produto "${productName}"?`;
            productDeleteConfirmModal.classList.remove('hidden');
            productDeleteConfirmModal.classList.add('flex');
        });
    });

    confirmProductDeleteBtn.addEventListener("click", () => {
        if (!pendingDeleteProductId) return;

        fetch(`/backoffice/stock/${pendingDeleteProductId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                Accept: "application/json",
            },
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            productDeleteConfirmModal.classList.add('hidden');
            productDeleteConfirmModal.classList.remove('flex');
            pendingDeleteProductId = null;
            location.reload();
        });
    });

    cancelProductDeleteConfirmBtn.addEventListener("click", () => {
        productDeleteConfirmModal.classList.add('hidden');
        productDeleteConfirmModal.classList.remove('flex');
        pendingDeleteProductId = null;
    });

    // --- VIEW PRODUCT ---
    productsTable.querySelectorAll(".view-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = btn.dataset.id;

            // Close the dropdown manually
            const dropdown = btn.closest('[data-dropdown-menu]');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }

            loadProductData(productId, 'view');
        });
    });

    // --- EDIT PRODUCT ---
    productsTable.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = btn.dataset.id;

            // Close the dropdown manually
            const dropdown = btn.closest('[data-dropdown-menu]');
            if (dropdown) {
                dropdown.classList.add('hidden');
            }

            loadProductData(productId, 'edit');
        });
    });

    // === Actions Dropdown ===
    const dropdownTriggers = document.querySelectorAll("[data-dropdown-trigger]");
    dropdownTriggers.forEach(trigger => {
        const menu = trigger.parentElement.querySelector("[data-dropdown-menu]");

        trigger.addEventListener("click", e => {
            e.stopPropagation();

            // Close other dropdowns first
            document.querySelectorAll("[data-dropdown-menu]").forEach(m => {
                if (m !== menu) {
                    m.classList.add("hidden");
                    m.style.position = '';
                    m.style.top = '';
                    m.style.left = '';
                }
            });

            // Toggle the menu first
            menu.classList.toggle("hidden");

            // Check if dropdown exceeds viewport after opening
            if (!menu.classList.contains('hidden')) {
                const rect = trigger.getBoundingClientRect();
                const viewportHeight = window.innerHeight;

                // Use fixed positioning to avoid clipping
                menu.style.position = 'fixed';
                menu.style.left = `${rect.right - menu.offsetWidth}px`;

                // If the dropdown exceeds the viewport, position it above
                if (rect.bottom + menu.offsetHeight > viewportHeight - 20) {
                    menu.style.top = `${rect.top - menu.offsetHeight - 8}px`;
                    menu.classList.remove('origin-top-right', 'mt-2');
                    menu.classList.add('origin-bottom-right');
                } else {
                    menu.style.top = `${rect.bottom + 8}px`;
                    menu.classList.remove('origin-bottom-right');
                    menu.classList.add('origin-top-right');
                }
            } else {
                // Reset positioning when closing
                menu.style.position = '';
                menu.style.top = '';
                menu.style.left = '';
            }
        });
    });    document.addEventListener("click", (e) => {
        // Don't close dropdowns if click is inside a modal
        if (e.target.closest('#product-modal') ||
            e.target.closest('#product-save-confirm-modal') ||
            e.target.closest('#product-delete-confirm-modal') ||
            e.target.closest('#bulk-delete-modal')) {
            return;
        }

        // Close all dropdowns if clicking outside
        if (!e.target.closest('[data-dropdown-trigger]') && !e.target.closest('[data-dropdown-menu]')) {
            document.querySelectorAll("[data-dropdown-menu]").forEach(menu => menu.classList.add("hidden"));
        }
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") {
            document.querySelectorAll("[data-dropdown-menu]").forEach(menu => menu.classList.add("hidden"));
            if (!productModal.classList.contains("hidden")) {
                closeProductModal();
            }
        }
    });
});
