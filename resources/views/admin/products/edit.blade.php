@extends('admin.layout')

@section('title', 'Редактировать товар')

@section('content')
<div class="max-w-3xl">
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-6">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Назад к списку
    </a>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Категория *</label>
                <select name="type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <option value="computer" @selected($product->type === 'computer')>💻 Компьютер</option>
                    <option value="keyboard" @selected($product->type === 'keyboard')>⌨️ Клавиатура</option>
                    <option value="mouse" @selected($product->type === 'mouse')>🖱️ Мышь</option>
                    <option value="headphones" @selected($product->type === 'headphones')>🎧 Наушники</option>
                    <option value="monitor" @selected($product->type === 'monitor')>🖥️ Монитор</option>
                    <option value="webcam" @selected($product->type === 'webcam')>📷 Веб-камера</option>
                    <option value="speaker" @selected($product->type === 'speaker')>🔊 Колонки</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Бренд *</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Модель *</label>
                    <input type="text" name="model" value="{{ old('model', $product->model) }}" required 
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Цена (₽) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Остаток (шт.) *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Описание</label>
                <textarea name="description" rows="4" 
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Изображение</label>
                @if($product->image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->brand }} {{ $product->model }}" class="w-32 h-32 object-cover rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Текущее изображение</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Максимальный размер: 2MB. Оставьте пустым, чтобы сохранить текущее.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-2 focus:ring-blue-200">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">⭐ Избранный товар</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="flex-1 px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-lg shadow-blue-500/30 transition">
                    Сохранить изменения
                </button>
                <a href="{{ route('admin.products.index') }}" 
                   class="px-5 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition text-center">
                    Отмена
                </a>
            </div>
        </form>
    </div>
</div>
@endsection