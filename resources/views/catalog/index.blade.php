<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Каталог товаров') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex gap-6">
                <!-- Products Grid (Left) -->
                <div class="flex-1">
                    <!-- Results Info & Sort -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="text-gray-600 dark:text-gray-400">
                                Найдено товаров: <span class="font-semibold text-gray-900 dark:text-white">{{ $products->total() }}</span>
                            </div>
                            
                            <form method="GET" action="{{ route('catalog.index') }}" class="flex items-center gap-3">
                                <!-- Preserve filters -->
                                @if(request('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                @if(request('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                @if(request('price_min'))
                                    <input type="hidden" name="price_min" value="{{ request('price_min') }}">
                                @endif
                                @if(request('price_max'))
                                    <input type="hidden" name="price_max" value="{{ request('price_max') }}">
                                @endif
                                @if(request('in_stock'))
                                    <input type="hidden" name="in_stock" value="{{ request('in_stock') }}">
                                @endif
                                
                                <label class="text-sm text-gray-600 dark:text-gray-400">Сортировка:</label>
                                <select name="sort" 
                                        onchange="this.form.submit()"
                                        class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    <option value="default" @selected(($sort ?? 'default') === 'default')>По умолчанию</option>
                                    <option value="price_asc" @selected(($sort ?? 'default') === 'price_asc')>Цена: по возрастанию</option>
                                    <option value="price_desc" @selected(($sort ?? 'default') === 'price_desc')>Цена: по убыванию</option>
                                    <option value="name_asc" @selected(($sort ?? 'default') === 'name_asc')>Название: А-Я</option>
                                    <option value="name_desc" @selected(($sort ?? 'default') === 'name_desc')>Название: Я-А</option>
                                    <option value="newest" @selected(($sort ?? 'default') === 'newest')>Сначала новые</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                            <div class="font-semibold mb-2">Ошибки в параметрах:</div>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                                Товары не найдены
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">
                                Попробуйте изменить параметры поиска или фильтры
                            </p>
                            <a href="{{ route('catalog.index') }}" 
                               class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                Сбросить фильтры
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Filters Sidebar (Right) -->
                <div class="w-80 flex-shrink-0">
                    <div class="sticky top-6">
                        <form method="GET" action="{{ route('catalog.index') }}" class="space-y-6">
                            <!-- Search -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    🔍 Поиск
                                </label>
                                <input type="text" 
                                       name="q" 
                                       value="{{ $q ?? '' }}" 
                                       placeholder="Бренд или модель..."
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    📦 Категория
                                </label>
                                
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="type" 
                                               value="" 
                                               @checked(!request('type'))
                                               class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Все товары</span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="radio" 
                                               name="type" 
                                               value="computer" 
                                               @checked(request('type') === 'computer')
                                               class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">💻 Компьютеры</span>
                                    </label>
                                </div>
                                
                                <!-- Peripherals subcategories -->
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-2">Периферия</div>
                                    <div class="space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="keyboard" 
                                                   @checked(request('type') === 'keyboard')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">⌨️ Клавиатуры</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="mouse" 
                                                   @checked(request('type') === 'mouse')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">🖱️ Мыши</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="headphones" 
                                                   @checked(request('type') === 'headphones')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">🎧 Наушники</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="monitor" 
                                                   @checked(request('type') === 'monitor')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">🖥️ Мониторы</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="webcam" 
                                                   @checked(request('type') === 'webcam')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">📷 Веб-камеры</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                   name="type" 
                                                   value="speaker" 
                                                   @checked(request('type') === 'speaker')
                                                   class="text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">🔊 Колонки</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price Range -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    💰 Цена, ₽
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="price_min" 
                                           value="{{ request('price_min') }}" 
                                           placeholder="От"
                                           min="0"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                    <span class="text-gray-500 dark:text-gray-400">—</span>
                                    <input type="number" 
                                           name="price_max" 
                                           value="{{ request('price_max') }}" 
                                           placeholder="До"
                                           min="0"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <!-- Stock Filter -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="in_stock" 
                                           value="1" 
                                           @checked(request('in_stock'))
                                           class="rounded text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">✅ Только в наличии</span>
                                </label>
                            </div>
                            
                            <!-- Brand Filter (if you have brands) -->
                            @if(isset($brands) && $brands->count() > 0)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                    🏷️ Бренд
                                </label>
                                <div class="space-y-2 max-h-48 overflow-y-auto">
                                    @foreach($brands as $brand)
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="brands[]" 
                                                   value="{{ $brand }}" 
                                                   @checked(in_array($brand, request('brands', [])))
                                                   class="rounded text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Actions -->
                            <div class="flex gap-3">
                                <button type="submit" 
                                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                    Применить
                                </button>
                                <a href="{{ route('catalog.index') }}" 
                                   class="px-4 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition text-center">
                                    ✕
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>