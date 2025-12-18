<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Корзина') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cart && $cart->items->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                    Товары в корзине ({{ $cart->items->count() }})
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($cart->items as $item)
                                        <div class="flex gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                            <!-- Product Image -->
                                            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                                @if($item->product->image)
                                                    <img src="{{ $item->product->image_url }}" 
                                                         alt="{{ $item->product->brand }} {{ $item->product->model }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('catalog.show', $item->product) }}" 
                                                   class="text-lg font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $item->product->brand }} {{ $item->product->model }}
                                                </a>
                                                
                                                @if($item->product->category)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ $item->product->category->name }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Price -->
                                                <div class="mt-2">
                                                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($item->price, 0, ',', ' ') }} ₽
                                                    </span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400"> × {{ $item->quantity }}</span>
                                                </div>
                                                
                                                <!-- Stock Warning -->
                                                @if($item->product->stock < $item->quantity)
                                                    <div class="mt-2 flex items-center gap-1 text-sm text-red-600 dark:text-red-400">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Осталось только {{ $item->product->stock }} шт.
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Quantity Controls -->
                                            <div class="flex flex-col items-end gap-2">
                                                <div class="flex items-center gap-2">
                                                    <form method="POST" action="{{ route('cart.update', $item) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                                        <button type="submit" 
                                                                class="p-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded transition"
                                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    
                                                    <span class="w-12 text-center font-semibold text-gray-900 dark:text-white">
                                                        {{ $item->quantity }}
                                                    </span>
                                                    
                                                    <form method="POST" action="{{ route('cart.update', $item) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                        <button type="submit" 
                                                                class="p-1 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded transition"
                                                                {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Удалить
                                                    </button>
                                                </form>
                                                
                                                <!-- Item Total -->
                                                <div class="mt-2 text-right">
                                                    <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                        {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Итого
                            </h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Товары ({{ $cart->items->sum('quantity') }} шт.)</span>
                                    <span>{{ number_format($cart->total, 0, ',', ' ') }} ₽</span>
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Доставка</span>
                                    <span class="text-green-600 dark:text-green-400">Бесплатно</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between items-center">
                                    <span class="text-xl font-semibold text-gray-900 dark:text-white">Всего:</span>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($cart->total, 0, ',', ' ') }} ₽
                                    </span>
                                </div>
                            </div>
                            
                            <a href="{{ route('orders.create') }}" 
                               class="block w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-center">
                                Оформить заказ
                            </a>
                            
                            <a href="{{ route('catalog.index') }}" 
                               class="block w-full mt-3 px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition text-center">
                                Продолжить покупки
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-6 text-2xl font-semibold text-gray-900 dark:text-white">
                        Корзина пуста
                    </h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Добавьте товары из каталога, чтобы начать покупки
                    </p>
                    <a href="{{ route('catalog.index') }}" 
                       class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Перейти в каталог
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>