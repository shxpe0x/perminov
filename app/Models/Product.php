<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'type',
        'brand',
        'model',
        'price',
        'description',
        'image',
        'stock',
    ];

    protected $casts = [
        'price' => 'integer',
        'type' => 'string',
        'category_id' => 'integer',
        'stock' => 'integer',
    ];

    /**
     * Категория товара
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Отзывы на товар
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Одобренные отзывы
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->approved();
    }

    /**
     * Избранное пользователей
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // Scopes

    /**
     * Scope для фильтрации компьютеров
     */
    public function scopeComputers($query)
    {
        return $query->where('type', 'computer');
    }

    /**
     * Scope для фильтрации периферии
     */
    public function scopePeripherals($query)
    {
        return $query->where('type', 'peripheral');
    }

    /**
     * Scope для поиска по бренду и модели
     */
    public function scopeSearch($query, string $search)
    {
        if (empty($search)) {
            return $query;
        }

        // Экранируем спецсимволы LIKE
        $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);

        return $query->where(function ($q) use ($search) {
            $q->where('brand', 'like', "%{$search}%")
              ->orWhere('model', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope для товаров в наличии
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Аксессоры

    /**
     * Аксессор для форматированной цены
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }

    /**
     * Средний рейтинг товара
     */
    public function getAverageRatingAttribute(): float
    {
        return Cache::remember("product_{$this->id}_rating", 3600, function () {
            return $this->approvedReviews()->avg('rating') ?? 0;
        });
    }

    /**
     * Количество отзывов
     */
    public function getReviewsCountAttribute(): int
    {
        return Cache::remember("product_{$this->id}_reviews_count", 3600, function () {
            return $this->approvedReviews()->count();
        });
    }

    /**
     * URL изображения или заглушка
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        return asset('images/no-image.png');
    }

    /**
     * Проверка наличия товара
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Проверка, добавлен ли товар в избранное пользователем
     */
    public function isFavoritedBy($userId): bool
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
}
