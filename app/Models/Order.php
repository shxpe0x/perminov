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
        'phone',
        'comment',
    ];

    protected $casts = [
        'total_price' => 'integer',
    ];

    /**
     * Статусы заказа
     */
    const STATUS_NEW = 'new';
    const STATUS_PAID = 'paid';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Пользователь, сделавший заказ
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Элементы заказа
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes

    /**
     * Scope для фильтрации по статусу
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope для получения новых заказов
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * Scope для получения последних заказов
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Scope для фильтрации по конкретному статусу
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // Статические методы

    /**
     * Получить все доступные статусы
     */
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

    // Методы проверки статуса

    /**
     * Новый заказ (ожидает оплаты)
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Заказ в обработке (оплачен)
     */
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Заказ отправлен
     */
    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    /**
     * Заказ доставлен
     */
    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Можно ли отменить заказ
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_NEW, self::STATUS_PAID]);
    }

    /**
     * Заказ отменён
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // Аксессоры

    /**
     * Получить человекочитаемый статус
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'Новый',
            self::STATUS_PAID => 'Оплачен',
            self::STATUS_SHIPPED => 'Отправлен',
            self::STATUS_DELIVERED => 'Доставлен',
            self::STATUS_CANCELLED => 'Отменён',
            default => $this->status,
        };
    }

    /**
     * Форматированная цена
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_price, 0, ',', ' ') . ' ₽';
    }
}
