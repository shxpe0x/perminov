<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    // Список отзывов на товар
    public function index(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->recent()
            ->paginate(10);

        return view('reviews.index', compact('product', 'reviews'));
    }

    // Добавить отзыв
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            Review::query()->updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                ],
                [
                    'rating' => $request->input('rating'),
                    'comment' => $request->input('comment'),
                ]
            );

            Log::info('Отзыв добавлен', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'rating' => $request->input('rating'),
            ]);

            return back()->with('success', 'Отзыв добавлен');
        } catch (\Exception $e) {
            Log::error('Ошибка добавления отзыва', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при добавлении отзыва');
        }
    }

    // Удалить отзыв
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        try {
            $review->delete();

            return back()->with('success', 'Отзыв удалён');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка удаления отзыва');
        }
    }
}
