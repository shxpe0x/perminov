<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    /**
     * Продукты в категории
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Родительская категория
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Дочерние категории
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Scope для корневых категорий
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
