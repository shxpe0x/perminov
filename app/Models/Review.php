<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'rating', 'comment'];

    protected $casts = [
        'rating' => 'integer',
        'user_id' => 'integer',
        'product_id' => 'integer',
    ];

    // Отношения
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // Валидация рейтинга
    public static function validRatings(): array
    {
        return [1, 2, 3, 4, 5];
    }
}
