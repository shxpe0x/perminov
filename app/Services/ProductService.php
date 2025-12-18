<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Получить каталог товаров с кешированием
     */
    public function getProductsCached(array $filters = [], int $perPage = 10)
    {
        $cacheKey = 'products_' . md5(json_encode($filters) . '_' . $perPage);

        return Cache::remember($cacheKey, 3600, function () use ($filters, $perPage) {
            $query = Product::with(['category', 'reviews']);

            // Фильтрация по типу
            if (isset($filters['type'])) {
                if ($filters['type'] === 'computer') {
                    $query->computers();
                } elseif ($filters['type'] === 'peripheral') {
                    $query->peripherals();
                }
            }

            // Поиск
            if (!empty($filters['q'])) {
                $query->search($filters['q']);
            }

            // Фильтр по категории
            if (!empty($filters['category_id'])) {
                $query->byCategory($filters['category_id']);
            }

            // Только в наличии
            if (isset($filters['in_stock']) && $filters['in_stock']) {
                $query->inStock();
            }

            // Сортировка
            $sort = $filters['sort'] ?? 'id';
            $dir = $filters['dir'] ?? 'desc';
            $query->orderBy($sort, $dir);

            return $query->paginate($perPage);
        });
    }

    /**
     * Получить рейтинг товара с кешированием
     */
    public function getProductRating(int $productId): array
    {
        $cacheKey = "product_rating_{$productId}";

        return Cache::remember($cacheKey, 3600, function () use ($productId) {
            $product = Product::with('reviews')->findOrFail($productId);

            return [
                'average' => round($product->reviews->avg('rating') ?? 0, 1),
                'count' => $product->reviews->count(),
            ];
        });
    }

    /**
     * Создать товар
     */
    public function createProduct(array $data): Product
    {
        // Обработка изображения
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->validateImage($data['image']);
            $data['image'] = $this->uploadImage($data['image']);
        }

        $product = Product::create($data);

        // Очищаем кеш каталога
        $this->clearProductsCache();

        Log::info('Товар создан', [
            'product_id' => $product->id,
            'brand' => $product->brand,
            'model' => $product->model,
        ]);

        return $product;
    }

    /**
     * Обновить товар
     */
    public function updateProduct(Product $product, array $data): Product
    {
        // Обработка нового изображения
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->validateImage($data['image']);

            // Удаляем старое изображение
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $this->uploadImage($data['image']);
        }

        $product->update($data);

        // Очищаем кеш
        $this->clearProductsCache();
        $this->clearProductRatingCache($product->id);

        Log::info('Товар обновлён', [
            'product_id' => $product->id,
            'brand' => $product->brand,
            'model' => $product->model,
        ]);

        return $product->fresh();
    }

    /**
     * Валидация изображения
     */
    public function validateImage(UploadedFile $file): void
    {
        // Проверка размера (макс 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \Exception('Размер изображения не должен превышать 2 MB');
        }

        // Проверка MIME-типа
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Допустимые форматы: JPEG, PNG, WebP, GIF');
        }

        // Проверка расширения
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new \Exception('Допустимые расширения: jpg, jpeg, png, webp, gif');
        }
    }

    /**
     * Загрузить изображение
     */
    private function uploadImage(UploadedFile $file): string
    {
        return $file->store('products', 'public');
    }

    /**
     * Очистить кеш каталога
     */
    public function clearProductsCache(): void
    {
        Cache::flush(); // Или используйте теги для точечной очистки
    }

    /**
     * Очистить кеш рейтинга товара
     */
    public function clearProductRatingCache(int $productId): void
    {
        Cache::forget("product_rating_{$productId}");
    }

    /**
     * Обновить кеш рейтинга после добавления отзыва
     */
    public function updateProductRating(int $productId): void
    {
        $this->clearProductRatingCache($productId);
        // Предварительно прогреваем кеш
        $this->getProductRating($productId);
    }
}
