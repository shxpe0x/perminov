<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
    ];

    // Автоматически генерируем slug при создании
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Отношение: категория имеет много товаров
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Scope для получения категории по slug
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    // Количество товаров в категории
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }
}
