<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Список избранного
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    // Добавить в избранное
    public function toggle(Product $product)
    {
        $userId = auth()->id();

        $favorite = Favorite::query()
            ->where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'Удалено из избранного');
        }

        Favorite::query()->create([
            'user_id' => $userId,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Добавлено в избранное');
    }
}
