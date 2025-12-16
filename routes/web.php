<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

Route::get('/', fn () => redirect()->route('catalog.index'));

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [CatalogController::class, 'show'])->name('catalog.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', fn () => redirect()->route('products.index'))->name('admin.home');
    Route::resource('products', AdminProductController::class);
});

require __DIR__.'/auth.php';
