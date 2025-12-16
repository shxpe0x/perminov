<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['type', 'category_id', 'brand', 'model', 'price', 'description', 'image'];

    protected $casts = [
        'price' => 'integer',
        'type' => 'string',
        'category_id' => 'integer',
    ];

    // Отношения
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    // Scopes
    public function scopeComputers($query)
    {
        return $query->where('type', 'computer');
    }

    public function scopePeripherals($query)
    {
        return $query->where('type', 'peripheral');
    }

    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, string $search)
    {
        if (empty($search)) {
            return $query;
        }

        $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);

        return $query->where(function ($q) use ($search) {
            $q->where('brand', 'like', "%{$search}%")
              ->orWhere('model', 'like', "%{$search}%");
        });
    }

    // Аксессоры
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }

        return Storage::url($this->image);
    }

    // Средний рейтинг
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Количество отзывов
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    // Проверка, в избранном ли у пользователя
    public function isFavoritedBy(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->favorites()->where('user_id', $userId)->exists();
    }

    // Проверка, в корзине ли у пользователя
    public function isInCartOf(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->cartItems()->where('user_id', $userId)->exists();
    }
}
