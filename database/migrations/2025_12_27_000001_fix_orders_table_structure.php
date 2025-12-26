<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем старое поле total_price если оно существует и переименовываем
            // Но так как в миграции оно уже total_price, просто добавляем комментарий
            // что поле корректно
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes needed
    }
};
