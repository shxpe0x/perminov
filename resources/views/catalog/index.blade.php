<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Каталог товаров') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('catalog.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Type Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Тип товара
                        </label>
                        <select name="type" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="" @selected(!$type)>Все</option>
                            <option value="computer" @selected($type === 'computer')>Компьютеры</option>
                            <option value="peripheral" @selected($type === 'peripheral')>Периферия</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Поиск
                        </label>
                        <input type="text" 
                               name="q" 
                               value="{{ $q ?? '' }}" 
                               placeholder="Бренд или модель..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Сортировка
                        </label>
                        <select name="sort" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="id" @selected(($sort ?? 'id') === 'id')>По умолчанию</option>
                            <option value="price" @selected(($sort ?? 'id') === 'price')>По цене</option>
                            <option value="name" @selected(($sort ?? 'id') === 'name')>По названию</option>
                        </select>
                    </div>

                    <!-- Sort Direction -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Направление
                        </label>
                        <select name="dir" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="desc" @selected(($dir ?? 'desc') === 'desc')>По убыванию</option>
                            <option value="asc" @selected(($dir ?? 'desc') === 'asc')>По возрастанию</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 lg:col-span-4 flex gap-3">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Применить фильтры
                        </button>
                        <a href="{{ route('catalog.index') }}" 
                           class="px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition-colors">
                            Сбросить
                        </a>
                    </div>
                </form>
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

            <!-- Results Info -->
            <div class="mb-6 text-gray-600 dark:text-gray-400">
                Найдено товаров: <span class="font-semibold">{{ $products->total() }}</span>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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
                       class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Сбросить фильтры
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>