<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex gap-6">
                <!-- Filters Sidebar (Left) -->
                <aside class="w-72 flex-shrink-0">
                    <div class="sticky top-6 space-y-4">
                        <form method="GET" action="{{ route('catalog.index') }}" id="filtersForm">
                            <!-- Hidden sort field -->
                            <input type="hidden" name="sort" value="{{ request('sort', 'default') }}">
                            
                            <!-- Search -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                <div class="relative">
                                    <input type="text" 
                                           name="q" 
                                           value="{{ request('q', '') }}" 
                                           placeholder="Поиск товаров..."
                                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">
                                    Категории
                                </h3>
                                
                                <div class="space-y-2">
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="" 
                                               @checked(!request('type'))
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">Все товары</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="computer" 
                                               @checked(request('type') === 'computer')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">💻 Компьютеры</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="keyboard" 
                                               @checked(request('type') === 'keyboard')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">⌨️ Клавиатуры</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="mouse" 
                                               @checked(request('type') === 'mouse')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">🖱️ Мыши</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="headphones" 
                                               @checked(request('type') === 'headphones')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">🎧 Наушники</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="monitor" 
                                               @checked(request('type') === 'monitor')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">🖥️ Мониторы</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="webcam" 
                                               @checked(request('type') === 'webcam')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">📷 Веб-камеры</span>
                                    </label>
                                    
                                    <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                        <input type="radio" 
                                               name="type" 
                                               value="speaker" 
                                               @checked(request('type') === 'speaker')
                                               onchange="this.form.submit()"
                                               class="w-4 h-4 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300 font-medium">🔊 Колонки</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Dynamic Filters by Category -->
                            @php
                                $currentType = request('type');
                                $categoryBrands = [
                                    'keyboard' => ['Logitech', 'Corsair', 'Razer', 'HyperX'],
                                    'mouse' => ['Logitech', 'Razer', 'SteelSeries', 'HyperX'],
                                    'headphones' => ['Sony', 'JBL', 'HyperX', 'Razer'],
                                    'monitor' => ['Samsung', 'LG', 'ASUS', 'Dell'],
                                    'webcam' => ['Logitech', 'Microsoft', 'Razer', 'A4Tech'],
                                    'speaker' => ['JBL', 'Logitech', 'Creative', 'Edifier'],
                                    'computer' => ['ASUS', 'MSI', 'HP', 'Dell'],
                                ];
                                $showBrands = $currentType && isset($categoryBrands[$currentType]) ? $categoryBrands[$currentType] : [];
                            @endphp
                            
                            @if($showBrands)
                            <!-- Brand Filter -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">
                                    Бренд
                                </h3>
                                <div class="space-y-2">
                                    @foreach($showBrands as $brand)
                                        <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                            <input type="checkbox" 
                                                   name="brands[]" 
                                                   value="{{ $brand }}" 
                                                   @checked(in_array($brand, request('brands', [])))
                                                   class="w-4 h-4 rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Price Range -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">
                                    Цена, ₽
                                </h3>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="price_min" 
                                           value="{{ request('price_min') }}" 
                                           placeholder="От"
                                           min="0"
                                           class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <span class="text-gray-400">—</span>
                                    <input type="number" 
                                           name="price_max" 
                                           value="{{ request('price_max') }}" 
                                           placeholder="До"
                                           min="0"
                                           class="w-full px-3 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                </div>
                            </div>
                            
                            <!-- Stock Filter -->
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                                    <input type="checkbox" 
                                           name="in_stock" 
                                           value="1" 
                                           @checked(request('in_stock'))
                                           class="w-4 h-4 rounded text-green-600 focus:ring-2 focus:ring-green-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">✅ Только в наличии</span>
                                </label>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <button type="submit" 
                                        class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:scale-[1.02]">
                                    Применить
                                </button>
                                <a href="{{ route('catalog.index') }}" 
                                   class="px-4 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>
                
                <!-- Products Section (Right) -->
                <main class="flex-1 min-w-0">
                    <!-- Header with Sort -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    @if(request('type'))
                                        {{ match(request('type')) {
                                            'computer' => 'Компьютеры',
                                            'keyboard' => 'Клавиатуры',
                                            'mouse' => 'Мыши',
                                            'headphones' => 'Наушники',
                                            'monitor' => 'Мониторы',
                                            'webcam' => 'Веб-камеры',
                                            'speaker' => 'Колонки',
                                            default => 'Каталог товаров'
                                        } }}
                                    @else
                                        Каталог товаров
                                    @endif
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Найдено: <span class="font-semibold text-gray-900 dark:text-white">{{ $products->total() }}</span> {{ trans_choice('товар|товара|товаров', $products->total()) }}
                                </p>
                            </div>
                            
                            <form method="GET" action="{{ route('catalog.index') }}" class="flex items-center gap-3">
                                @foreach(request()->except('sort') as $key => $value)
                                    @if(is_array($value))
                                        @foreach($value as $v)
                                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endif
                                @endforeach
                                
                                <select name="sort" 
                                        onchange="this.form.submit()"
                                        class="px-4 py-2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option value="default" @selected(request('sort', 'default') === 'default')>По популярности</option>
                                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Сначала дешевле</option>
                                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Сначала дороже</option>
                                    <option value="name_asc" @selected(request('sort') === 'name_asc')>По названию А-Я</option>
                                    <option value="newest" @selected(request('sort') === 'newest')>Сначала новые</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Active Filters Tags -->
                    @if(request()->hasAny(['type', 'q', 'price_min', 'price_max', 'in_stock', 'brands']))
                    <div class="mb-6 flex flex-wrap gap-2">
                        @if(request('type'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ match(request('type')) {
                                    'computer' => 'Компьютеры',
                                    'keyboard' => 'Клавиатуры',
                                    'mouse' => 'Мыши',
                                    'headphones' => 'Наушники',
                                    'monitor' => 'Мониторы',
                                    'webcam' => 'Веб-камеры',
                                    'speaker' => 'Колонки',
                                } }}
                            </span>
                        @endif
                        @if(request('q'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                Поиск: {{ request('q') }}
                            </span>
                        @endif
                        @if(request('price_min') || request('price_max'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ request('price_min', 0) }} - {{ request('price_max', '∞') }} ₽
                            </span>
                        @endif
                        @if(request('in_stock'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                В наличии
                            </span>
                        @endif
                        @if(request('brands'))
                            @foreach(request('brands') as $brand)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                    {{ $brand }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                    @endif

                    <!-- Products Grid -->
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-8">
                            @foreach ($products as $product)
                                <x-product-card :product="$product" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-16 text-center">
                            <svg class="mx-auto h-20 w-20 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-6 text-xl font-bold text-gray-900 dark:text-white">
                                Товары не найдены
                            </h3>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">
                                Попробуйте изменить параметры фильтров или поиска
                            </p>
                            <a href="{{ route('catalog.index') }}" 
                               class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition shadow-lg shadow-blue-500/30">
                                Сбросить все фильтры
                            </a>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </div>
</x-app-layout>