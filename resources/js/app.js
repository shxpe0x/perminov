import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs';
import anchor from '@alpinejs/anchor';
import collapse from '@alpinejs/collapse';
import IMask from 'imask';

// Flowbite for UI components
import 'flowbite';

// Notyf for notifications
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

window.Alpine = Alpine;

Alpine.plugin(anchor);
Alpine.plugin(collapse);

Alpine.start();

// Initialize Notyf
window.notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'success',
            background: '#10b981',
            icon: {
                className: 'notyf__icon--success',
                tagName: 'i',
            },
        },
        {
            type: 'error',
            background: '#ef4444',
            icon: {
                className: 'notyf__icon--error',
                tagName: 'i',
            },
        },
    ],
});

// Phone mask function
function attachPhoneMask(input) {
    const mask = IMask(input, { mask: '+{7} (000) 000 00 00', lazy: false });

    if (input.value) mask.value = input.value;

    input.form?.addEventListener('submit', () => {
        let raw = mask.unmaskedValue;
        if (raw.length === 10) raw = '7' + raw;
        input.value = raw;
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[data-phone-mask]').forEach(attachPhoneMask);
});

// Alpine.js cart component
Alpine.data('cart', () => ({
    count: 0,

    init() {
        // Load cart count from server or localStorage
        this.updateCount();
    },

    async addToCart(productId) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await response.json();

            if (response.ok) {
                this.count = data.count;
                window.notyf.success('Товар добавлен в корзину!');
            } else {
                window.notyf.error(data.message || 'Ошибка добавления в корзину');
            }
        } catch (error) {
            console.error('Cart error:', error);
            window.notyf.error('Произошла ошибка');
        }
    },

    async updateCount() {
        try {
            const response = await fetch('/cart/count');
            const data = await response.json();
            this.count = data.count || 0;
        } catch (error) {
            console.error('Failed to fetch cart count:', error);
        }
    },
}));
