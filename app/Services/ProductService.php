<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class ProductService
{
    private const CACHE_TTL = 3600; // 1 час
    private const MAX_IMAGE_SIZE = 2048; // 2MB в KB
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    /**
     * Получить товары с кешированием
     */
    public function getProductsCached(array $filters = [])
    {
        $cacheKey = $this->getCacheKey('products', $filters);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($filters) {
            $query = Product::with(['category', 'reviews']);

            // Применяем фильтры
            if (isset($filters['type'])) {
                if ($filters['type'] === 'computer') {
                    $query->computers();
                } elseif ($filters['type'] === 'peripheral') {
                    $query->peripherals();
                }
            }

            if (isset($filters['category_id'])) {
                $query->byCategory($filters['category_id']);
            }

            if (isset($filters['search'])) {
                $query->search($filters['search']);
            }

            if (isset($filters['in_stock']) && $filters['in_stock']) {
                $query->inStock();
            }

            return $query->paginate($filters['per_page'] ?? 12);
        });
    }

    /**
     * Получить рейтинг товара с кешированием
     */
    public function getProductRating(int $productId): array
    {
        $cacheKey = "product_rating_{$productId}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($productId) {
            $product = Product::with('reviews')->find($productId);
            
            if (!$product) {
                return ['average' => 0, 'count' => 0];
            }

            return [
                'average' => round($product->reviews->avg('rating') ?? 0, 1),
                'count' => $product->reviews->count(),
            ];
        });
    }

    /**
     * Создать товар
     */
    public function createProduct(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $this->validateImage($image);
            $data['image'] = $this->uploadImage($image);
        }

        $product = Product::create($data);
        
        $this->clearProductsCache();
        
        return $product;
    }

    /**
     * Обновить товар
     */
    public function updateProduct(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $this->validateImage($image);
            
            // Удалить старое изображение
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $data['image'] = $this->uploadImage($image);
        }

        $product->update($data);
        
        $this->clearProductsCache();
        $this->clearProductRatingCache($product->id);
        
        return $product->fresh();
    }

    /**
     * Удалить товар (мягкое удаление)
     */
    public function deleteProduct(Product $product): bool
    {
        $result = $product->delete();
        
        if ($result) {
            $this->clearProductsCache();
        }
        
        return $result;
    }

    /**
     * Валидация изображения
     */
    public function validateImage(UploadedFile $file): void
    {
        // Проверка размера
        if ($file->getSize() > self::MAX_IMAGE_SIZE * 1024) {
            throw new Exception('Размер файла не должен превышать 2MB');
        }

        // Проверка MIME-типа
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new Exception('Допустимы только изображения форматов: JPEG, PNG, WebP, GIF');
        }

        // Проверка расширения
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new Exception('Недопустимое расширение файла');
        }
    }

    /**
     * Загрузить изображение
     */
    private function uploadImage(UploadedFile $file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('products', $filename, 'public');
        
        return $path;
    }

    /**
     * Очистить кеш всех товаров
     */
    public function clearProductsCache(): void
    {
        // Удаляем все кеши связанные с товарами
        Cache::flush(); // Или можно использовать tags если драйвер поддерживает
    }

    /**
     * Очистить кеш рейтинга товара
     */
    public function clearProductRatingCache(int $productId): void
    {
        Cache::forget("product_rating_{$productId}");
    }

    /**
     * Обновить кеш рейтинга товара
     */
    public function updateProductRatingCache(int $productId): void
    {
        $this->clearProductRatingCache($productId);
        $this->getProductRating($productId); // Пересоздать кеш
    }

    /**
     * Генерировать ключ кеша
     */
    private function getCacheKey(string $prefix, array $params): string
    {
        ksort($params);
        return $prefix . '_' . md5(json_encode($params));
    }
}
