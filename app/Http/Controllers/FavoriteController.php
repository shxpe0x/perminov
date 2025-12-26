<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Список избранных товаров
     */
    public function index()
    {
        // Только активные (не удаленные) товары
        $favorites = auth()->user()
            ->favorites()
            ->whereNull('products.deleted_at')
            ->with('category')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Добавить в избранное
     */
    public function toggle(Product $product)
    {
        try {
            // Проверка на soft deleted
            if ($product->trashed()) {
                return back()->with('error', 'Товар больше не доступен.');
            }
            
            $user = auth()->user();

            if ($user->hasFavorite($product->id)) {
                // Удаляем из избранного
                $user->favorites()->detach($product->id);
                $message = 'Товар удалён из избранного.';
            } else {
                // Добавляем в избранное
                $user->favorites()->attach($product->id);
                $message = 'Товар добавлен в избранное!';
            }

            Log::info('Изменение избранного', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            return back()->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Ошибка изменения избранного', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при изменении избранного.');
        }
    }
}
