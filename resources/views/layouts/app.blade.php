<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/luvi-ui.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-900 text-gray-100">
        <div class="min-h-screen bg-gray-900">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
        window.addToCart = async function(productId, button) {
            const original = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
                    body: JSON.stringify({product_id: productId, quantity: 1})
                });
                const data = await res.json();
                showToast(data.message, data.success ? 'success' : 'error');
                if (data.success && data.count) updateCartBadge(data.count);
            } catch(e) {
                showToast('Ошибка при добавлении', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = original;
            }
        };

        window.updateCartItem = async function(itemId, quantity, el) {
            try {
                const res = await fetch(`/cart/${itemId}`, {
                    method: 'PATCH',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'},
                    body: JSON.stringify({quantity})
                });
                const data = await res.json();
                if (data.success) {
                    el.closest('[data-item-id]').querySelector('[data-quantity]').textContent = quantity;
                    el.closest('[data-item-id]').querySelector('[data-item-total]').textContent = new Intl.NumberFormat('ru-RU').format(data.item_total) + ' ₽';
                    document.querySelector('[data-cart-total]').textContent = new Intl.NumberFormat('ru-RU').format(data.total) + ' ₽';
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) {
                showToast('Ошибка обновления', 'error');
            }
        };

        window.removeCartItem = async function(itemId, el) {
            if (!confirm('Удалить товар?')) return;
            try {
                const res = await fetch(`/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json'}
                });
                const data = await res.json();
                if (data.success) {
                    el.closest('[data-item-id]').remove();
                    document.querySelector('[data-cart-total]').textContent = new Intl.NumberFormat('ru-RU').format(data.total) + ' ₽';
                    if (data.count === 0) location.reload();
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) {
                showToast('Ошибка удаления', 'error');
            }
        };

        function showToast(msg, type) {
            const t = document.createElement('div');
            t.className = `fixed top-20 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white font-semibold transition-transform duration-300`;
            t.textContent = msg;
            t.style.transform = 'translateX(400px)';
            document.body.appendChild(t);
            setTimeout(() => t.style.transform = 'translateX(0)', 10);
            setTimeout(() => { t.style.transform = 'translateX(400px)'; setTimeout(() => t.remove(), 300); }, 3000);
        }

        function updateCartBadge(count) {
            const b = document.querySelector('[data-cart-count]');
            if (b) { b.textContent = count; b.classList.toggle('hidden', count === 0); }
        }

        @auth
        fetch('/cart/count').then(r => r.json()).then(d => updateCartBadge(d.count)).catch(e => console.error(e));
        @endauth
        </script>
    </body>
</html>