<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаём админа
        User::factory()->create([
            'name' => 'Admin',
            'phone' => '+79999999999',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Создаём обычного юзера
        User::factory()->create([
            'name' => 'Test User',
            'phone' => '+79876543210',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Создаём категории
        $categories = [
            Category::create(['name' => 'Процессоры']),
            Category::create(['name' => 'Видеокарты']),
            Category::create(['name' => 'Материнские платы']),
            Category::create(['name' => 'Оперативная память']),
            Category::create(['name' => 'Жёсткие диски']),
            Category::create(['name' => 'SSD']),
            Category::create(['name' => 'Мониторы']),
            Category::create(['name' => 'Клавиатуры']),
            Category::create(['name' => 'Мышки']),
            Category::create(['name' => 'Наушники']),
        ];

        // 20 тестовых товаров
        $products = [
            // Процессоры (3)
            ['type' => 'computer', 'brand' => 'Intel', 'model' => 'Core i9-13900K', 'price' => 89999, 'category_id' => 1, 'stock' => 15, 'image' => 'cpu-intel-i9.jpg', 'description' => 'Мощный процессор для игр и работы'],
            ['type' => 'computer', 'brand' => 'AMD', 'model' => 'Ryzen 9 7950X', 'price' => 79999, 'category_id' => 1, 'stock' => 12, 'image' => 'cpu-amd-ryzen.jpg', 'description' => 'Отличное решение для многопоточных задач'],
            ['type' => 'computer', 'brand' => 'Intel', 'model' => 'Core i7-13700K', 'price' => 59999, 'category_id' => 1, 'stock' => 20, 'image' => 'cpu-intel-i7.jpg', 'description' => 'Универсальный процессор среднего класса'],

            // Видеокарты (3)
            ['type' => 'computer', 'brand' => 'NVIDIA', 'model' => 'RTX 4090', 'price' => 249999, 'category_id' => 2, 'stock' => 5, 'image' => 'gpu-nvidia-4090.jpg', 'description' => 'Флагман для игр в 4K'],
            ['type' => 'computer', 'brand' => 'NVIDIA', 'model' => 'RTX 4080', 'price' => 189999, 'category_id' => 2, 'stock' => 8, 'image' => 'gpu-nvidia-4080.jpg', 'description' => 'Отличная видеокарта для 1440p'],
            ['type' => 'computer', 'brand' => 'AMD', 'model' => 'RX 7900 XTX', 'price' => 179999, 'category_id' => 2, 'stock' => 6, 'image' => 'gpu-amd-7900.jpg', 'description' => 'Конкурент RTX 4080'],

            // Материнские платы (2)
            ['type' => 'computer', 'brand' => 'ASUS', 'model' => 'ROG MAXIMUS Z790', 'price' => 49999, 'category_id' => 3, 'stock' => 10, 'image' => 'mobo-asus-z790.jpg', 'description' => 'Премиум материнская плата для Intel'],
            ['type' => 'computer', 'brand' => 'MSI', 'model' => 'MEG X870-E', 'price' => 44999, 'category_id' => 3, 'stock' => 9, 'image' => 'mobo-msi-x870.jpg', 'description' => 'Флагман для AMD Ryzen'],

            // Память (2)
            ['type' => 'computer', 'brand' => 'Kingston', 'model' => 'Fury Beast 32GB DDR5', 'price' => 12999, 'category_id' => 4, 'stock' => 50, 'image' => 'ram-kingston-32gb.jpg', 'description' => 'Быстрая оперативная память'],
            ['type' => 'computer', 'brand' => 'Corsair', 'model' => 'Vengeance RGB 64GB DDR5', 'price' => 24999, 'category_id' => 4, 'stock' => 30, 'image' => 'ram-corsair-64gb.jpg', 'description' => 'Два комплекта по 32GB с RGB'],

            // SSD (2)
            ['type' => 'computer', 'brand' => 'Samsung', 'model' => '990 Pro 4TB', 'price' => 49999, 'category_id' => 6, 'stock' => 12, 'image' => 'ssd-samsung-4tb.jpg', 'description' => 'Быстрый NVMe SSD'],
            ['type' => 'computer', 'brand' => 'WD', 'model' => 'Black SN850X 2TB', 'price' => 24999, 'category_id' => 6, 'stock' => 18, 'image' => 'ssd-wd-2tb.jpg', 'description' => 'Отличный SSD по цене'],

            // Мониторы (2)
            ['type' => 'peripheral', 'brand' => 'ASUS', 'model' => 'ROG Swift 1440p 240Hz', 'price' => 39999, 'category_id' => 7, 'stock' => 8, 'image' => 'monitor-asus-240hz.jpg', 'description' => 'Монитор для киберспорта'],
            ['type' => 'peripheral', 'brand' => 'LG', 'model' => 'UltraWide 3440x1440', 'price' => 59999, 'category_id' => 7, 'stock' => 6, 'image' => 'monitor-lg-ultrawide.jpg', 'description' => 'Суперширокий монитор'],

            // Клавиатуры (2)
            ['type' => 'peripheral', 'brand' => 'Corsair', 'model' => 'K95 Platinum XT', 'price' => 19999, 'category_id' => 8, 'stock' => 15, 'image' => 'keyboard-corsair.jpg', 'description' => 'Премиум механическая клавиатура'],
            ['type' => 'peripheral', 'brand' => 'Logitech', 'model' => 'G915 Pro', 'price' => 14999, 'category_id' => 8, 'stock' => 20, 'image' => 'keyboard-logitech.jpg', 'description' => 'Беспроводная механическая клавиатура'],

            // Мышки (2)
            ['type' => 'peripheral', 'brand' => 'Logitech', 'model' => 'G502 Hero', 'price' => 4999, 'category_id' => 9, 'stock' => 40, 'image' => 'mouse-logitech-502.jpg', 'description' => 'Легендарная гейминг мышка'],
            ['type' => 'peripheral', 'brand' => 'Razer', 'model' => 'DeathAdder V3', 'price' => 6999, 'category_id' => 9, 'stock' => 35, 'image' => 'mouse-razer-deathadder.jpg', 'description' => 'Элегантная мышь для киберспорта'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
