# Frontend Setup Guide - SSR Stack

## Установка зависимостей

### 1. Backend зависимости

```bash
composer require livewire/livewire
```

### 2. Frontend зависимости

```bash
npm install flowbite notyf swiper @heroicons/vue
```

## Конфигурация

### 1. Обновить package.json

Добавить в devDependencies:
```json
"flowbite": "^2.5.0",
"notyf": "^3.10.0",
"swiper": "^11.1.0",
"@heroicons/vue": "^2.1.0"
```

### 2. Обновить tailwind.config.js

Добавить в content:
```js
"./node_modules/flowbite/**/*.js",
"./vendor/livewire/**/*.blade.php"
```

Добавить в plugins:
```js
require('flowbite/plugin')
```

### 3. Обновить resources/css/app.css

```css
@import 'notyf/notyf.min.css';
@import 'swiper/css';
@import 'swiper/css/navigation';
@import 'swiper/css/pagination';

@tailwind base;
@tailwind components;
@tailwind utilities;
```

### 4. Обновить resources/js/app.js

```js
import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import anchor from '@alpinejs/anchor';
import { Notyf } from 'notyf';
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

// Alpine.js setup
Alpine.plugin(collapse);
Alpine.plugin(anchor);
window.Alpine = Alpine;
Alpine.start();

// Notyf setup
window.notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' },
    ripple: true,
});

// Swiper setup
window.Swiper = Swiper;
window.SwiperModules = { Navigation, Pagination };

// Livewire events
if (typeof Livewire !== 'undefined') {
    document.addEventListener('livewire:init', () => {
        Livewire.on('notify', (event) => {
            if (event.type === 'success') {
                window.notyf.success(event.message);
            } else if (event.type === 'error') {
                window.notyf.error(event.message);
            } else {
                window.notyf.success(event.message);
            }
        });
    });
}
```

## Livewire установка

### 1. Опубликовать конфиг

```bash
php artisan livewire:publish --config
```

### 2. Добавить Livewire директивы в layout

В `resources/views/layouts/app.blade.php` перед `</head>`:
```blade
@livewireStyles
```

Перед `</body>`:
```blade
@livewireScripts
```

## Создание компонентов

### 1. Livewire компоненты

```bash
php artisan make:livewire ProductFilters
php artisan make:livewire CartIcon
php artisan make:livewire ReviewForm
php artisan make:livewire Admin/ProductTable
```

### 2. Blade компоненты

```bash
php artisan make:component ProductCard
php artisan make:component Navbar
php artisan make:component Footer
php artisan make:component Modal
```

## Сборка

### Development
```bash
npm run dev
```

### Production
```bash
npm run build
```

## Использование компонентов

### Flowbite модальное окно

```blade
<button data-modal-target="product-modal" data-modal-toggle="product-modal" 
        class="bg-blue-500 text-white px-4 py-2 rounded">
    Открыть товар
</button>

<div id="product-modal" tabindex="-1" class="hidden ...">
    {{-- Содержимое модалки --}}
</div>
```

### Notyf уведомления

```js
window.notyf.success('Товар добавлен в корзину!');
window.notyf.error('Недостаточно товара на складе');
```

### Swiper галерея

```blade
<div class="swiper" id="product-gallery">
    <div class="swiper-wrapper">
        @foreach($product->images as $image)
            <div class="swiper-slide">
                <img src="{{ $image->url }}" alt="{{ $product->name }}">
            </div>
        @endforeach
    </div>
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<script>
    new Swiper('#product-gallery', {
        modules: [window.SwiperModules.Navigation, window.SwiperModules.Pagination],
        pagination: { el: '.swiper-pagination' },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>
```

### Heroicons

```blade
<svg class="w-6 h-6 text-gray-500">
    <use href="#heroicon-shopping-cart"/>
</svg>
```

## Структура проекта

```
resources/
├── css/
│   └── app.css
├── js/
│   └── app.js
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── admin.blade.php
│   ├── components/
│   │   ├── product-card.blade.php
│   │   ├── navbar.blade.php
│   │   └── footer.blade.php
│   ├── livewire/
│   │   ├── product-filters.blade.php
│   │   ├── cart-icon.blade.php
│   │   └── review-form.blade.php
│   ├── catalog/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── admin/
│       └── products/
│           ├── index.blade.php
│           └── edit.blade.php
```

## Полезные команды

```bash
# Очистить кеш
php artisan optimize:clear

# Собрать для production
npm run build && php artisan optimize

# Создать Livewire компонент
php artisan make:livewire ComponentName

# Создать Blade компонент
php artisan make:component ComponentName
```

## Troubleshooting

### Livewire не работает
1. Проверь наличие `@livewireStyles` и `@livewireScripts` в layout
2. Очисти кеш: `php artisan view:clear`
3. Пересобери фронтенд: `npm run build`

### Flowbite компоненты не работают
1. Убедись, что `import 'flowbite'` есть в app.js
2. Проверь, что путь к flowbite есть в tailwind.config.js content
3. Пересобери: `npm run dev`

### Стили не применяются
1. Проверь, что Vite запущен: `npm run dev`
2. Проверь директивы `@vite` в layout
3. Очисти кеш браузера
