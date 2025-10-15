document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    /** TOAST FUNCTION **/
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.className = `fixed bottom-6 right-6 px-4 py-2 rounded shadow-lg text-white z-50 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    /** QUANTITY SELECTOR **/
    function updateQuantityDisplay(productId, newQuantity) {
        const itemEl = document.getElementById(`cart-item-${productId}`);
        if (itemEl) {
            itemEl.querySelector('.item-quantity').textContent = newQuantity;
        }
    }

    document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', () => {
            const display = btn.closest('.quantity-selector').querySelector('.item-quantity');
            const stock = parseInt(display.dataset.stock);
            let qty = parseInt(display.textContent);
            if (qty < stock) {
                qty++;
                display.textContent = qty;
            }
        });
    });

    document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', () => {
            const display = btn.closest('.quantity-selector').querySelector('.item-quantity');
            let qty = parseInt(display.textContent);
            if (qty > 1) {
                qty--;
                display.textContent = qty;
            }
        });
    });

    /** ADD TO CART **/
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const productId = this.querySelector('[name="product_id"]').value;
            const quantity = this.closest('.product-card').querySelector('.item-quantity')?.textContent || 1;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId, quantity: parseInt(quantity) })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    showToast('Produto adicionado ao carrinho!');
                    // Atualiza contagem do carrinho no header
                    const cartCount = document.getElementById('cart-count');
                    if(cartCount) cartCount.textContent = data.cart_count;
                } else {
                    showToast('Erro ao adicionar produto', 'error');
                }
            })
            .catch(err => showToast('Erro ao adicionar produto', 'error'));
        });
    });

    /** REMOVE FROM CART **/
    function updateCartTotals(data) {
        document.getElementById('total-com-iva').textContent = data.total_com_iva_formatted;
        document.getElementById('total-sem-iva').textContent = data.total_sem_iva_formatted;
        document.getElementById('total-iva').textContent = data.total_iva_formatted;
    }

    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.id;

            fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    if(data.quantity > 0){
                        updateQuantityDisplay(productId, data.quantity);
                    } else {
                        const itemEl = document.getElementById(`cart-item-${productId}`);
                        if(itemEl) itemEl.remove();
                    }

                    updateCartTotals(data);
                    showToast('Item removido do carrinho!');
                } else {
                    showToast('Erro ao remover item', 'error');
                }
            })
            .catch(err => showToast('Erro ao remover item', 'error'));
        });
    });
});
