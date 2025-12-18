<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductService
{
    /**
     * Получить товары с кешированием
     */
    public function getProductsCached(array $filters, int $perPage = 12)
    {
        $page = request()->get('page', 1);
        $cacheKey = 'products:filters:' . md5(json_encode($filters)) . ':page:' . $page;

        return Cache::tags(['products'])->remember($cacheKey, 3600, function () use ($filters, $perPage) {
            $query = Product::query()->with(['category', 'reviews']);

            // Применение фильтров
            if (!empty($filters['type'])) {
                $query->where('type', $filters['type']);
            }

            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            if (!empty($filters['search'])) {
                $query->search($filters['search']);
            }

            if (!empty($filters['in_stock'])) {
                $query->inStock();
            }

            if (!empty($filters['featured'])) {
                $query->featured();
            }

            // Сортировка
            $sortBy = $filters['sort_by'] ?? 'created_at';
            $sortOrder = $filters['sort_order'] ?? 'desc';
            $query->orderBy($sortBy, $sortOrder);

            return $query->paginate($perPage);
        });
    }

    /**
     * Получить рейтинг товара с кешированием
     */
    public function getProductRating(Product $product): array
    {
        $cacheKey = "product:rating:{$product->id}";

        return Cache::tags(['products'])->remember($cacheKey, 3600, function () use ($product) {
            return [
                'average' => round($product->reviews()->avg('rating') ?? 0, 1),
                'count' => $product->reviews()->count(),
            ];
        });
    }

    /**
     * Создать товар
     */
    public function createProduct(array $data): Product
    {
        // Загрузка изображения
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->validateImage($data['image']);
            $data['image'] = $data['image']->store('products', 'public');
        }

        // Создание записи
        $product = Product::create($data);

        // Очистка кеша
        $this->clearProductsCache();

        // Логирование
        Log::info('Товар создан', [
            'product_id' => $product->id,
            'brand' => $product->brand,
            'model' => $product->model,
            'admin_id' => auth()->id(),
        ]);

        return $product;
    }

    /**
     * Обновить товар
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // Обновление изображения
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->validateImage($data['image']);

            // Удаление старого изображения
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
        }

        // Обновление записи
        $product->update($data);

        // Очистка кеша
        $this->clearProductsCache();
        $this->updateProductRating($product);

        // Логирование
        Log::info('Товар обновлён', [
            'product_id' => $product->id,
            'brand' => $product->brand,
            'model' => $product->model,
            'admin_id' => auth()->id(),
        ]);

        return $product->fresh();
    }

    /**
     * Удалить товар
     */
    public function deleteProduct(Product $product): bool
    {
        // Удаление файла (при мягком удалении файл оставляем)
        // if ($product->image && Storage::disk('public')->exists($product->image)) {
        //     Storage::disk('public')->delete($product->image);
        // }

        // Soft Delete
        $result = $product->delete();

        // Очистка кеша
        $this->clearProductsCache();

        // Логирование
        Log::info('Товар удалён', [
            'product_id' => $product->id,
            'brand' => $product->brand,
            'model' => $product->model,
            'admin_id' => auth()->id(),
        ]);

        return $result;
    }

    /**
     * Валидация загружаемого изображения
     */
    public function validateImage(UploadedFile $file): void
    {
        // Размер: макс 2MB
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw ValidationException::withMessages([
                'image' => ['Размер изображения не должен превышать 2MB']
            ]);
        }

        // MIME-тип
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw ValidationException::withMessages([
                'image' => ['Допустимые форматы: JPEG, PNG, WebP, GIF']
            ]);
        }

        // Расширение
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw ValidationException::withMessages([
                'image' => ['Допустимые расширения: jpg, jpeg, png, webp, gif']
            ]);
        }
    }

    /**
     * Очистить весь кеш товаров
     */
    public function clearProductsCache(): void
    {
        Cache::tags(['products'])->flush();
    }

    /**
     * Пересчитать рейтинг товара (очистить кеш)
     */
    public function updateProductRating(Product $product): void
    {
        Cache::forget("product:rating:{$product->id}");
    }
}
