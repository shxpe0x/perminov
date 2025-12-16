<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['category_id', 'type', 'brand', 'model', 'price', 'stock', 'description', 'image'];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'type' => 'string',
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
     * Элементы корзины с этим товаром
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Элементы заказов с этим товаром
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
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
              ->orWhere('model', 'like', "%{$search}%");
        });
    }

    /**
     * Scope для фильтрации по категории
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
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
     * Получить URL изображения
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        // Placeholder если нет изображения
        return asset('images/no-image.png');
    }

    /**
     * Есть ли товар в наличии
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Получить средний рейтинг
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Получить количество отзывов
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
