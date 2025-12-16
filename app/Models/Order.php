<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'delivery_address',
        'comment',
    ];

    protected $casts = [
        'total_price' => 'integer',
        'status' => 'string',
    ];

    // Статусы заказа
    public const STATUS_NEW = 'new';
    public const STATUS_PAID = 'paid';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_PAID => 'Оплачен',
            self::STATUS_SHIPPED => 'Отправлен',
            self::STATUS_DELIVERED => 'Доставлен',
            self::STATUS_CANCELLED => 'Отменён',
        ];
    }

    // Отношения
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Аксессоры
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_price, 0, ',', ' ') . ' ₽';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'blue',
            self::STATUS_PAID => 'yellow',
            self::STATUS_SHIPPED => 'purple',
            self::STATUS_DELIVERED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray',
        };
    }
}
