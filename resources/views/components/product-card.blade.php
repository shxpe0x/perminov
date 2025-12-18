@props(['product'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div class="relative h-48 bg-gray-200 dark:bg-gray-700">
        @if($product->image)
            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->brand }} {{ $product->model }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
        @if($product->stock > 0)
            <span class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded-full">В наличии</span>
        @else
            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">Нет в наличии</span>
        @endif
        @if($product->is_featured)
            <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Хит
            </span>
        @endif
    </div>
    <div class="p-4">
        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
            {{ match($product->type) {
                'computer' => '💻 Компьютеры', 'keyboard' => '⌨️ Клавиатуры', 'mouse' => '🖱️ Мыши',
                'headphones' => '🎧 Наушники', 'monitor' => '🖥️ Мониторы', 'webcam' => '📷 Веб-камеры',
                'speaker' => '🔊 Колонки', default => $product->type } }}
        </span>
        <h3 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">{{ $product->brand }} {{ $product->model }}</h3>
        @if($product->description)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $product->description }}</p>
        @endif
        <div class="mt-4 flex items-center justify-between">
            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
            <div class="flex gap-2">
                @auth
                    @if($product->stock > 0)
                        <button onclick="window.addToCart({{ $product->id }}, this)" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </button>
                    @else
                        <button disabled class="px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed">Нет в наличии</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </a>
                @endauth
                <a href="{{ route('catalog.show', $product) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm font-medium rounded-lg transition">Подробнее</a>
            </div>
        </div>
    </div>
</div>