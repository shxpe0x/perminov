import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import anchor from '@alpinejs/anchor';
import { Notyf } from 'notyf';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

// ==========================================
// Alpine.js Setup
// ==========================================
Alpine.plugin(collapse);
Alpine.plugin(anchor);

// Cart Alpine component
Alpine.data('cart', () => ({
    count: 0,
    loading: false,

    init() {
        this.updateCount();
    },

    async addToCart(productId, quantity = 1) {
        if (this.loading) return;
        
        this.loading = true;
        try {
            const response = await axios.post('/cart/add', {
                product_id: productId,
                quantity: quantity
            });
            
            this.count = response.data.count;
            window.notyf.success('Товар добавлен в корзину!');
            this.$dispatch('cart-updated', { count: this.count });
        } catch (error) {
            const message = error.response?.data?.message || 'Ошибка добавления в корзину';
            window.notyf.error(message);
        } finally {
            this.loading = false;
        }
    },

    async updateCount() {
        try {
            const response = await axios.get('/cart/count');
            this.count = response.data.count;
        } catch (error) {
            console.error('Failed to fetch cart count:', error);
        }
    }
}));

// Product image gallery
Alpine.data('productGallery', (images) => ({
    images: images || [],
    currentIndex: 0,
    
    get currentImage() {
        return this.images[this.currentIndex] || null;
    },
    
    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    
    prev() {
        this.currentIndex = this.currentIndex === 0 
            ? this.images.length - 1 
            : this.currentIndex - 1;
    },
    
    selectImage(index) {
        this.currentIndex = index;
    }
}));

// Favorites
Alpine.data('favorite', () => ({
    loading: false,

    async toggle(productId) {
        if (this.loading) return;
        
        this.loading = true;
        try {
            const response = await axios.post('/favorites/toggle', {
                product_id: productId
            });
            
            if (response.data.added) {
                window.notyf.success('Добавлено в избранное!');
            } else {
                window.notyf.success('Удалено из избранного');
            }
            
            this.$dispatch('favorites-updated');
        } catch (error) {
            const message = error.response?.data?.message || 'Ошибка';
            window.notyf.error(message);
        } finally {
            this.loading = false;
        }
    }
}));

window.Alpine = Alpine;
Alpine.start();

// ==========================================
// Notyf Notifications Setup
// ==========================================
window.notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' },
    ripple: true,
    dismissible: true,
    types: [
        {
            type: 'success',
            background: '#10b981',
            icon: {
                className: 'notyf__icon--success',
                tagName: 'i'
            }
        },
        {
            type: 'error',
            background: '#ef4444',
            icon: {
                className: 'notyf__icon--error',
                tagName: 'i'
            }
        },
        {
            type: 'warning',
            background: '#f59e0b',
            icon: false
        }
    ]
});

// ==========================================
// Swiper Setup
// ==========================================
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination, Autoplay };

// Initialize product gallery swiper on product pages
document.addEventListener('DOMContentLoaded', () => {
    const productGallery = document.querySelector('#product-gallery');
    if (productGallery) {
        new Swiper('#product-gallery', {
            modules: [Navigation, Pagination, Autoplay],
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }

    // Initialize featured products carousel
    const featuredCarousel = document.querySelector('#featured-carousel');
    if (featuredCarousel) {
        new Swiper('#featured-carousel', {
            modules: [Navigation, Pagination, Autoplay],
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 4000,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
});

// ==========================================
// Livewire Integration
// ==========================================
if (typeof Livewire !== 'undefined') {
    document.addEventListener('livewire:init', () => {
        // Listen for notify events from Livewire
        Livewire.on('notify', (event) => {
            const type = event.type || 'success';
            const message = event.message || 'Действие выполнено';
            
            if (type === 'success') {
                window.notyf.success(message);
            } else if (type === 'error') {
                window.notyf.error(message);
            } else if (type === 'warning') {
                window.notyf.open({ type: 'warning', message });
            } else {
                window.notyf.success(message);
            }
        });

        // Scroll to top after pagination
        Livewire.hook('morph.updated', ({ el, component }) => {
            if (el.hasAttribute('data-scroll-top')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
}

// ==========================================
// Global Utilities
// ==========================================

// Format price
window.formatPrice = (price) => {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(price);
};

// Debounce function
window.debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Confirm action
window.confirmAction = (message = 'Вы уверены?') => {
    return confirm(message);
};

// Copy to clipboard
window.copyToClipboard = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
        window.notyf.success('Скопировано в буфер обмена');
    } catch (err) {
        window.notyf.error('Ошибка копирования');
    }
};

// ==========================================
// Image lazy loading
// ==========================================
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img.lazy').forEach(img => {
        imageObserver.observe(img);
    });
}
