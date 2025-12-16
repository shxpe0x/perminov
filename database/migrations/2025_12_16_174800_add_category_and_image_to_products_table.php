<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Добавляем категорию вместо type
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            
            // Добавляем изображение
            $table->string('image')->nullable()->after('description');
            
            // Меняем индекс
            $table->dropIndex(['type', 'brand']);
            $table->index(['category_id', 'brand']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id', 'brand']);
            $table->dropColumn(['category_id', 'image']);
            $table->index(['type', 'brand']);
        });
    }
};
