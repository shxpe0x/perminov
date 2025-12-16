<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Отношения
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Методы
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function getFormattedPhoneAttribute(): string
    {
        if (empty($this->phone)) {
            return '';
        }

        if (preg_match('/^(\+?7)(\d{3})(\d{3})(\d{2})(\d{2})$/', $this->phone, $matches)) {
            return "{$matches[1]} ({$matches[2]}) {$matches[3]}-{$matches[4]}-{$matches[5]}";
        }

        return $this->phone;
    }

    // Общая стоимость корзины
    public function getCartTotalAttribute(): int
    {
        return $this->cartItems()->with('product')->get()->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    // Количество товаров в корзине
    public function getCartCountAttribute(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    // Количество заказов
    public function getOrdersCountAttribute(): int
    {
        return $this->orders()->count();
    }
}
