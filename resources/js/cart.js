// Cart functionality with AJAX
window.CartManager = {
    // Add product to cart
    addToCart(productId, quantity = 1) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                this.showNotification(data.message, 'success');
                this.updateCartCount(data.count);
            }
            button.innerHTML = originalText;
            button.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Ошибка при добавлении в корзину', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    },

    // Update cart item quantity
    updateQuantity(itemId, quantity) {
        fetch(`/cart/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshCart();
            } else {
                this.showNotification(data.message || 'Ошибка', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Ошибка при обновлении', 'error');
        });
    },

    // Remove item from cart
    removeItem(itemId) {
        if (!confirm('Удалить товар из корзины?')) return;

        fetch(`/cart/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshCart();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showNotification('Ошибка при удалении', 'error');
        });
    },

    // Clear entire cart
    clearCart() {
        if (!confirm('Очистить всю корзину?')) return;

        fetch('/cart', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification(data.message, 'success');
                setTimeout(() => window.location.reload(), 500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    },

    // Refresh cart display
    refreshCart() {
        setTimeout(() => window.location.reload(), 500);
    },

    // Update cart count in header
    updateCartCount(count) {
        const badges = document.querySelectorAll('[data-cart-count]');
        badges.forEach(badge => {
            badge.textContent = count;
            if (count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    },

    // Show notification
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};

// Load cart count on page load
if (document.querySelector('[data-cart-count]')) {
    fetch('/cart/count', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        CartManager.updateCartCount(data.count);
    });
}