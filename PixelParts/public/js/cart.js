document.addEventListener('DOMContentLoaded', function () {
    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenElement ? csrfTokenElement.content : '';

    function showToast(message, success = true) {
        console.log('Attempting to show toast with message:', message);
        const toastTemplate = document.getElementById('toast-template');
        if (!toastTemplate) {
            console.error('Toast template not found!');
            return;
        }

        let toast = toastTemplate.querySelector('#toast');
        if (!toast) {
            console.error('Toast element not found inside template!');
            return;
        }

        toast = toast.cloneNode(true);
        document.body.appendChild(toast);
        toast.id = 'dynamic-toast';
        const toastMessage = toast.querySelector('.toast-message');
        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
        toast.classList.add(success ? 'bg-green-500' : 'bg-red-500');
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
        toast.style.zIndex = '9999';
        toast.classList.add('animate-fade-in-up');

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(1rem)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function closeToast(element) {
        const toast = element.closest('#toast');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(1rem)';
            setTimeout(() => toast.classList.add('hidden'), 300);
        }
    }

    function updateTotals(cartData) {
        document.getElementById('total-sem-iva').textContent = '€' + cartData.totalSemIva.toFixed(2).replace('.', ',');
        document.getElementById('total-iva').textContent = '€' + cartData.totalIva.toFixed(2).replace('.', ',');
        document.getElementById('total-com-iva').textContent = '€' + cartData.totalComIva.toFixed(2).replace('.', ',');
    }

    function checkEmptyCart() {
        console.log('Checking if cart is empty');
        const cartItems = document.querySelectorAll('.cart-item');
        const cartEmpty = document.querySelector('.cart-empty');
        const cartContent = document.querySelector('.cart-content');

        if (cartItems.length === 0) {
            console.log('Cart is empty, showing empty message');
            if (cartEmpty) cartEmpty.style.display = 'block';
            if (cartContent) {
                cartContent.style.display = 'none';
                setTimeout(() => {
                    cartContent.offsetHeight;  // Trigger reflow
                }, 100);
            }
        } else {
            console.log('Cart is not empty');
            if (cartEmpty) cartEmpty.style.display = 'none';
            if (cartContent) cartContent.style.display = 'block';
        }
    }

    async function removeFromCart(productId) {
        console.log('Removing item with ID:', productId);
        try {
            const response = await fetch(`/cart/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (!response.ok) throw new Error('Erro na requisição');

            const data = await response.json();

            if (data.success) {
                if (data.cartItems && data.cartItems[productId]) {
                    const quantityElement = document.querySelector(`#cart-item-${productId} .item-quantity`);
                    if (quantityElement) quantityElement.textContent = data.cartItems[productId].quantity;

                    const summaryItem = document.querySelector(`.cart-summary-item[data-id="${productId}"]`);
                    if (summaryItem) {
                        summaryItem.querySelector('.subtotal-com-iva').textContent = '€' + data.cartItems[productId].subtotalComIva.toFixed(2).replace('.', ',');
                        summaryItem.querySelector('.subtotal-sem-iva').textContent = '€' + data.cartItems[productId].subtotalSemIva.toFixed(2).replace('.', ',');
                        summaryItem.querySelector('.subtotal-iva').textContent = '€' + data.cartItems[productId].subtotalIva.toFixed(2).replace('.', ',');
                    }
                } else {
                    const itemEl = document.getElementById(`cart-item-${productId}`);
                    if (itemEl) itemEl.remove();

                    const summaryItem = document.querySelector(`.cart-summary-item[data-id="${productId}"]`);
                    if (summaryItem) summaryItem.remove();
                }

                if (data.total) updateTotals(data.total);
                showToast('Item removido com sucesso!', true);
                checkEmptyCart();
            } else {
                showToast(data.message || 'Erro ao remover o item', false);
            }
        } catch (error) {
            console.error('Erro AJAX:', error);
            showToast('Ocorreu um erro ao remover o item', false);
        }
    }

    document.querySelectorAll('.remove-from-cart').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.id;
            removeFromCart(productId);
        });
    });

    checkEmptyCart();  // Initial check
});

