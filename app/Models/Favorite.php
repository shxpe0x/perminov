<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    protected $casts = [
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

    // Scope для пользователя
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
