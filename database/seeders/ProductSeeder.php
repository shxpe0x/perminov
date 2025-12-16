<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'type' => 'computer',
                'brand' => 'ASUS',
                'model' => 'ROG Strix G16',
                'price' => 149990,
                'description' => 'Игровой ноутбук / условный товар для демо.',
            ],
            [
                'type' => 'computer',
                'brand' => 'Lenovo',
                'model' => 'Legion 5',
                'price' => 129990,
                'description' => 'Игровой ноутбук / условный товар для демо.',
            ],
            [
                'type' => 'peripheral',
                'brand' => 'Logitech',
                'model' => 'G Pro X Superlight',
                'price' => 10990,
                'description' => 'Мышь / условный товар для демо.',
            ],
            [
                'type' => 'peripheral',
                'brand' => 'Keychron',
                'model' => 'K2',
                'price' => 7990,
                'description' => 'Клавиатура / условный товар для демо.',
            ],
        ];

        foreach ($items as $item) {
            Product::query()->firstOrCreate(
                ['type' => $item['type'], 'brand' => $item['brand'], 'model' => $item['model']],
                ['price' => $item['price'], 'description' => $item['description']]
            );
        }
    }
}
