<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Просмотр корзины
    public function index()
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get();

        $total = $cartItems->sum(fn($item) => $item->subtotal);

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Добавить в корзину
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        try {
            $quantity = (int) $request->input('quantity', 1);

            $cartItem = CartItem::query()->updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => DB::raw("quantity + {$quantity}"),
                ]
            );

            Log::info('Добавлено в корзину', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);

            return back()->with('success', 'Товар добавлен в корзину');
        } catch (\Exception $e) {
            Log::error('Ошибка добавления в корзину', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при добавлении товара');
        }
    }

    // Обновить количество
    public function update(Request $request, CartItem $cartItem)
    {
        $this->authorize('update', $cartItem);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        try {
            $cartItem->update([
                'quantity' => $request->input('quantity'),
            ]);

            return back()->with('success', 'Количество обновлено');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка обновления');
        }
    }

    // Удалить из корзины
    public function remove(CartItem $cartItem)
    {
        $this->authorize('delete', $cartItem);

        try {
            $cartItem->delete();

            return back()->with('success', 'Товар удалён из корзины');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка удаления');
        }
    }

    // Очистить корзину
    public function clear()
    {
        try {
            auth()->user()->cartItems()->delete();

            return back()->with('success', 'Корзина очищена');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка очистки корзины');
        }
    }
}
