document.addEventListener("DOMContentLoaded", () => {
    // --- ELEMENTOS ---
    const productModal = document.getElementById("product-modal");
    const cancelProductBtn = document.getElementById("cancel-product-btn");
    const productForm = document.getElementById("product-form");
    const addProductBtn = document.getElementById("add-product-btn");
    const productsTable = document.getElementById("products-table");

    // Navegação entre páginas
    const page1 = document.getElementById("page-1");
    const page2 = document.getElementById("page-2");
    const prevPageBtn = document.getElementById("prev-page-btn");
    const nextPageBtn = document.getElementById("next-page-btn");
    const submitBtn = document.getElementById("submit-product-btn");
    const pageIndicator = document.getElementById("page-indicator");

    let currentPage = 1;
    let modalMode = 'add'; // 'add', 'edit', 'view'

    // --- FUNÇÕES DE NAVEGAÇÃO ---
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

            // Mostrar botão de submit apenas se não estiver em modo visualização
            if (modalMode !== 'view') {
                submitBtn.classList.remove("hidden");
            }
            pageIndicator.textContent = "Página 2 de 2";
        }
    }

    prevPageBtn.addEventListener("click", () => showPage(1));
    nextPageBtn.addEventListener("click", () => showPage(2));

    // --- FUNÇÕES PARA ESPECIFICAÇÕES/CARACTERÍSTICAS/COMPATIBILIDADE ---
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

    // --- FUNÇÕES MODAL ---
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
        productModal.style.display = "block";
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

            // Preencher dados básicos
            document.getElementById("product-id").value = product.id;
            document.getElementById("product-name").value = product.name || "";
            document.getElementById("product-brand").value = product.brand || "";
            document.getElementById("product-description").value = product.description || "";
            document.getElementById("product-price").value = product.price || "";
            document.getElementById("product-stock").value = product.stock || "";
            document.getElementById("product-category").value = product.category_id || "";

            // Mostrar imagem atual se existir
            if (product.image) {
                // Verifica se é uma URL externa (começa com http:// ou https://)
                if (product.image.startsWith('http://') || product.image.startsWith('https://')) {
                    document.getElementById("current-image").src = product.image;
                } else {
                    // É um caminho local do storage
                    document.getElementById("current-image").src = `/storage/${product.image}`;
                }
                document.getElementById("current-image-preview").classList.remove("hidden");
            } else {
                document.getElementById("current-image-preview").classList.add("hidden");
            }

            // Limpar campos dinâmicos
            clearDynamicFields();

            // Configurar modo ANTES de adicionar campos
            modalMode = mode;

            // Preencher especificações
            if (product.specifications && product.specifications.length > 0) {
                product.specifications.forEach(spec => {
                    addSpecificationField(spec.key, spec.value);
                });
            }

            // Preencher características
            if (product.features && product.features.length > 0) {
                product.features.forEach(feature => {
                    addFeatureField(feature.feature);
                });
            }

            // Preencher compatibilidade
            if (product.compatibility && product.compatibility.length > 0) {
                product.compatibility.forEach(comp => {
                    addCompatibilityField(comp.compatible_with);
                });
            }

            // Aplicar estilos do modo aos campos básicos
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
            productModal.style.display = "block";
            productModal.classList.remove("hidden");

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

    // --- EVENTOS MODAL ---
    if (addProductBtn) addProductBtn.addEventListener("click", openProductModal);
    cancelProductBtn.addEventListener("click", closeProductModal);
    productModal.addEventListener("click", (e) => {
        if (e.target === productModal) closeProductModal();
    });

    // --- SUBMISSÃO FORMULÁRIO VIA AJAX ---
    productForm.addEventListener("submit", function (e) {
        e.preventDefault();

        if (modalMode === 'view') {
            return; // Não permitir submissão em modo visualização
        }

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
    });

    // --- DELETAR PRODUTO ---
    productsTable.querySelectorAll(".delete-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const productId = btn.dataset.id;

            if (confirm("Deseja realmente apagar este produto?")) {
                fetch(`/backoffice/stock/${productId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        Accept: "application/json",
                    },
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                });
            }
        });
    });

    // --- VISUALIZAR PRODUTO ---
    productsTable.querySelectorAll(".view-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const productId = btn.dataset.id;
            loadProductData(productId, 'view');
        });
    });

    // --- EDITAR PRODUTO ---
    productsTable.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const productId = btn.dataset.id;
            loadProductData(productId, 'edit');
        });
    });

    // --- DROPDOWN DE AÇÕES ---
    const dropdownTriggers = document.querySelectorAll("[data-dropdown-trigger]");
    dropdownTriggers.forEach(trigger => {
        const menu = trigger.parentElement.querySelector("[data-dropdown-menu]");

        trigger.addEventListener("click", e => {
            e.stopPropagation();

            // Verificar se o dropdown está perto do final da página
            const rect = trigger.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            const spaceBelow = viewportHeight - rect.bottom;

            // Se não há espaço suficiente abaixo (menos de 200px), abre para cima
            if (spaceBelow < 200) {
                menu.classList.remove('origin-top-right');
                menu.classList.add('origin-bottom-right', 'bottom-full', 'mb-2');
                menu.classList.remove('mt-2');
            } else {
                menu.classList.add('origin-top-right');
                menu.classList.remove('origin-bottom-right', 'bottom-full', 'mb-2');
                menu.classList.add('mt-2');
            }

            menu.classList.toggle("hidden");
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll("[data-dropdown-menu]").forEach(menu => menu.classList.add("hidden"));
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
