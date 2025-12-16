<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['type', 'brand', 'model', 'price', 'description'];

    protected $casts = [
        'price' => 'integer',
        'type' => 'string',
    ];

    // Scope для фильтрации компьютеров
    public function scopeComputers($query)
    {
        return $query->where('type', 'computer');
    }

    // Scope для фильтрации периферии
    public function scopePeripherals($query)
    {
        return $query->where('type', 'peripheral');
    }

    // Scope для поиска по бренду и модели
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

    // Аксессор для форматированной цены
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ₽';
    }
}
