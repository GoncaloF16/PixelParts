document.addEventListener("DOMContentLoaded", () => {
    // --- ELEMENTOS ---
    const productModal = document.getElementById("product-modal");
    const cancelProductBtn = document.getElementById("cancel-product-btn");
    const productForm = document.getElementById("product-form");
    const addProductBtn = document.getElementById("add-product-btn");
    const productsTable = document.getElementById("products-table");

    // --- FUNÇÕES MODAL ---
    function openProductModal() {
        document.getElementById("product-modal-title").textContent = "Adicionar Produto";
        productForm.reset();
        document.getElementById("product-id").value = "";
        productForm.action = "/backoffice/stock";
        productForm.method = "POST";

        const methodInput = productForm.querySelector('input[name="_method"]');
        if (methodInput) methodInput.remove();

        productModal.classList.remove("hidden");
    }

    function openEditProductModal(produto) {
        document.getElementById("product-modal-title").textContent = "Editar Produto";
        document.getElementById("product-id").value = produto.id;
        document.getElementById("product-name").value = produto.name;
        document.getElementById("product-brand").value = produto.brand || "";
        document.getElementById("product-description").value = produto.description || "";
        document.getElementById("product-price").value = produto.price;
        document.getElementById("product-stock").value = produto.stock;
        document.getElementById("product-category").value = produto.category_id;

        productForm.action = `/backoffice/stock/${produto.id}`;
        productForm.method = "POST";

        let methodInput = productForm.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "PUT";
            productForm.appendChild(methodInput);
        }

        productModal.classList.remove("hidden");
    }

    function closeProductModal() {
        productModal.classList.add("hidden");
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
            const row = btn.closest("tr");
            const productId = row.dataset.id;

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

    // --- EDITAR PRODUTO ---
    productsTable.querySelectorAll(".edit-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const row = btn.closest("tr");
            const produto = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                stock: btn.dataset.stock,
                brand: btn.dataset.brand || "",
                description: btn.dataset.description || "",
                price: btn.dataset.price || "",
                category_id: btn.dataset.category || "",
            };
            openEditProductModal(produto);
        });
    });

    // --- DROPDOWN DE AÇÕES ---
    const dropdownTriggers = document.querySelectorAll("[data-dropdown-trigger]");
    dropdownTriggers.forEach(trigger => {
        const menu = trigger.parentElement.querySelector("[data-dropdown-menu]");

        trigger.addEventListener("click", e => {
            e.stopPropagation(); // evita fechar imediatamente
            menu.classList.toggle("hidden");
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll("[data-dropdown-menu]").forEach(menu => menu.classList.add("hidden"));
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") {
            document.querySelectorAll("[data-dropdown-menu]").forEach(menu => menu.classList.add("hidden"));
        }
    });
});
