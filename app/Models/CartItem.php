<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    protected $casts = [
        'quantity' => 'integer',
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

    // Общая стоимость позиции
    public function getSubtotalAttribute(): int
    {
        return $this->product->price * $this->quantity;
    }

    // Форматированная стоимость
    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' ₽';
    }
}
