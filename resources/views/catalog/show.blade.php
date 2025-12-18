<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $product->brand }} {{ $product->model }}
            </h2>
            <a href="{{ route('catalog.index') }}" 
               class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                ← Вернуться к каталогу
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Product Image -->
                    <div>
                        <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->brand }} {{ $product->model }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="flex flex-col">
                        <!-- Category -->
                        @if($product->category)
                            <span class="text-sm text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                {{ $product->category->name }}
                            </span>
                        @endif

                        <!-- Title -->
                        <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $product->brand }} {{ $product->model }}
                        </h1>

                        <!-- Rating -->
                        @if($product->reviews_count > 0)
                            <div class="mt-3 flex items-center gap-3">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($product->average_rating, 1) }} ({{ $product->reviews_count }} отзывов)
                                </span>
                            </div>
                        @endif

                        <!-- Price -->
                        <div class="mt-6">
                            <span class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ $product->formatted_price }}
                            </span>
                        </div>

                        <!-- Stock Status -->
                        <div class="mt-4">
                            @if($product->stock > 0)
                                <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">В наличии ({{ $product->stock }} шт.)</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Нет в наличии</span>
                                </div>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($product->description)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Описание</h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="mt-8 flex gap-4">
                            @if($product->stock > 0)
                                <button x-data="cart" 
                                        @click="addToCart({{ $product->id }})"
                                        class="flex-1 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Добавить в корзину
                                </button>
                            @else
                                <button disabled 
                                        class="flex-1 px-8 py-3 bg-gray-400 text-white font-semibold rounded-lg cursor-not-allowed">
                                    Нет в наличии
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Specifications -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Характеристики</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Бренд</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $product->brand }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Модель</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $product->model }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Тип</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $product->type === 'computer' ? 'Компьютер' : 'Периферия' }}
                            </span>
                        </div>
                        @if($product->category)
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Категория</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $product->category->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Отзывы</h3>
                    
                    @if($product->reviews_count > 0)
                        <div class="space-y-6">
                            @foreach($product->reviews as $review)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $review->user->name }}
                                                </span>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                                {{ $review->comment }}
                                            </p>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-500">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">
                            Пока нет отзывов на этот товар
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>