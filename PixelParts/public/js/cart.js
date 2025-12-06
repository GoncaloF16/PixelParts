document.addEventListener("DOMContentLoaded", function () {
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.content : "";

    function updateTotals(cartData) {
        document.getElementById("total-sem-iva").textContent =
            "€" + cartData.totalSemIva.toFixed(2).replace(".", ",");
        document.getElementById("total-iva").textContent =
            "€" + cartData.totalIva.toFixed(2).replace(".", ",");
        document.getElementById("total-com-iva").textContent =
            "€" + cartData.totalComIva.toFixed(2).replace(".", ",");
    }

    function checkEmptyCart() {
        const cartItems = document.querySelectorAll(".cart-item");
        const cartEmpty = document.querySelector(".cart-empty");
        const cartContent = document.querySelector(".cart-content");

        if (cartItems.length === 0) {
            // Show empty cart message
            if (cartEmpty) {
                cartEmpty.style.display = "block";
                cartEmpty.style.opacity = "0";
                setTimeout(() => {
                    cartEmpty.style.transition = "opacity 0.5s";
                    cartEmpty.style.opacity = "1";
                }, 10);
            }

            if (cartContent) {
                cartContent.style.transition = "opacity 0.3s";
                cartContent.style.opacity = "0";
                setTimeout(() => {
                    cartContent.style.display = "none";
                }, 300);
            }
        } else {
            if (cartEmpty) cartEmpty.style.display = "none";
            if (cartContent) {
                cartContent.style.display = "block";
                cartContent.style.opacity = "1";
            }
        }
    }

    async function removeFromCart(productId) {
        console.log("Removing item with ID:", productId);
        try {
            const response = await fetch(`/cart/remove/${productId}`, {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            if (!response.ok) throw new Error("Erro na requisição");

            const data = await response.json();

            if (data.success) {
                if (data.cartItems && data.cartItems[productId]) {
                    // Update quantity in main item
                    const quantityElement = document.querySelector(
                        `#cart-item-${productId} .item-quantity`
                    );
                    if (quantityElement)
                        quantityElement.textContent =
                            data.cartItems[productId].quantity;

                    // Update the item in the detailed summary
                    const summaryItem = document.querySelector(
                        `.cart-summary-item[data-id="${productId}"]`
                    );
                    if (summaryItem) {
                        // Update quantity in summary
                        const summaryQuantity = summaryItem.querySelector(".item-summary-quantity");
                        if (summaryQuantity) {
                            summaryQuantity.textContent = data.cartItems[productId].quantity;
                        }

                        // Update subtotal with IVA
                        summaryItem.querySelector(
                            ".subtotal-com-iva"
                        ).textContent =
                            "€" +
                            data.cartItems[productId].subtotalComIva
                                .toFixed(2)
                                .replace(".", ",");

                        // Update subtotal without IVA
                        summaryItem.querySelector(
                            ".subtotal-sem-iva"
                        ).textContent =
                            "€" +
                            data.cartItems[productId].subtotalSemIva
                                .toFixed(2)
                                .replace(".", ",");

                        // Update IVA value
                        summaryItem.querySelector(".subtotal-iva").textContent =
                            "€" +
                            data.cartItems[productId].subtotalIva
                                .toFixed(2)
                                .replace(".", ",");
                    }
                } else {
                    // Remove o item da lista principal
                    const itemEl = document.getElementById(
                        `cart-item-${productId}`
                    );
                    if (itemEl) {
                        itemEl.style.opacity = "0";
                        itemEl.style.transform = "translateX(-20px)";
                        setTimeout(() => {
                            itemEl.remove();
                            // Check if cart is empty after removing item
                            checkEmptyCart();
                        }, 300);
                    }

                    // Remove o item do resumo detalhado
                    const summaryItem = document.querySelector(
                        `.cart-summary-item[data-id="${productId}"]`
                    );
                    if (summaryItem) {
                        summaryItem.style.opacity = "0";
                        summaryItem.style.transform = "translateX(20px)";
                        setTimeout(() => summaryItem.remove(), 300);
                    }
                }

                if (data.total) updateTotals(data.total);

                // Atualiza contador do carrinho
                if (data.cart_count !== undefined && typeof window.updateCartCount === 'function') {
                    window.updateCartCount(data.cart_count);
                }

                // Mostra toast de sucesso
                if (typeof window.showToast === 'function') {
                    window.showToast("Item removido com sucesso!");
                }

                // Se ainda houver itens, verifica o estado do carrinho
                if (data.cartItems && data.cartItems[productId]) {
                    checkEmptyCart();
                }
            } else {
                if (typeof window.showToast === 'function') {
                    window.showToast(data.message || "Erro ao remover o item", true);
                }
            }
        } catch (error) {
            console.error("Erro AJAX:", error);
            if (typeof window.showToast === 'function') {
                window.showToast("Ocorreu um erro ao remover o item", true);
            }
        }
    }

    document.querySelectorAll(".remove-from-cart").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const productId = this.dataset.id;
            removeFromCart(productId);
        });
    });

    checkEmptyCart(); // Initial check
});
