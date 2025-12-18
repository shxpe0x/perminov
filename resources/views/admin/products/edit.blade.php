@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
    <div class="max-w-3xl">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Категория *</label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">Выберите категорию</option>
                        <option value="computer" {{ old('type', $product->type) === 'computer' ? 'selected' : '' }}>💻 Компьютеры</option>
                        <option value="keyboard" {{ old('type', $product->type) === 'keyboard' ? 'selected' : '' }}>⌨️ Клавиатуры</option>
                        <option value="mouse" {{ old('type', $product->type) === 'mouse' ? 'selected' : '' }}>🖱️ Мыши</option>
                        <option value="headphones" {{ old('type', $product->type) === 'headphones' ? 'selected' : '' }}>🎧 Наушники</option>
                        <option value="monitor" {{ old('type', $product->type) === 'monitor' ? 'selected' : '' }}>🖥️ Мониторы</option>
                        <option value="webcam" {{ old('type', $product->type) === 'webcam' ? 'selected' : '' }}>📷 Веб-камеры</option>
                        <option value="speaker" {{ old('type', $product->type) === 'speaker' ? 'selected' : '' }}>🔊 Колонки</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Бренд *</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Модель *</label>
                    <input type="text" name="model" value="{{ old('model', $product->model) }}" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Цена, ₽ *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="0.01" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Количество на складе *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Описание</label>
                    <textarea name="description" rows="4" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image -->
                @if($product->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущее изображение</label>
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->brand }} {{ $product->model }}" class="w-32 h-32 rounded object-cover">
                    </div>
                @endif

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Новое изображение (оставьте пустым, чтобы не менять)</label>
                    <input type="file" name="image" accept="image/*" 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Featured -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} 
                               class="rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">⭐ Рекомендуемый товар</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center space-x-4 pt-4">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition">
                        Отмена
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection>