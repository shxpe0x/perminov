<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'customer_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        'notes',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_amount' => 'integer',
    ];

    // Статусы заказа
    const STATUS_NEW = 'new';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PAID = 'paid';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Пользователь заказа
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Товары в заказе
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Генерация уникального номера заказа
     */
    public static function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    /**
     * Форматированная сумма заказа
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', ' ') . ' ₽';
    }

    /**
     * Название статуса на русском
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'Новый',
            self::STATUS_CONFIRMED => 'Подтверждён',
            self::STATUS_PAID => 'Оплачен',
            self::STATUS_SHIPPED => 'Отправлен',
            self::STATUS_DELIVERED => 'Доставлен',
            self::STATUS_CANCELLED => 'Отменён',
            default => 'Неизвестно',
        };
    }

    /**
     * Scopes для фильтрации по статусу
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }
}
