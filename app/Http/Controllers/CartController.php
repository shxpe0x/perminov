<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать корзину
     */
    public function index()
    {
        $cart = auth()->user()->cart()->with('items.product')->first();

        return view('cart.index', compact('cart'));
    }

    /**
     * Добавить товар в корзину
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        try {
            if (!$product->isInStock()) {
                return back()->with('error', 'Товара нет в наличии.');
            }

            $quantity = (int) $request->input('quantity', 1);

            if ($quantity > $product->stock) {
                return back()->with('error', "Недостаточно товара на складе. Доступно: {$product->stock}");
            }

            $cart = auth()->user()->getOrCreateCart();

            // Проверяем, есть ли уже такой товар в корзине
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                // Увеличиваем количество
                $newQuantity = $cartItem->quantity + $quantity;

                if ($newQuantity > $product->stock) {
                    return back()->with('error', "Невозможно добавить. Доступно: {$product->stock}");
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Добавляем новый элемент
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            Log::info('Товар добавлен в корзину', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);

            return back()->with('success', 'Товар добавлен в корзину!');
        } catch (\Exception $e) {
            Log::error('Ошибка добавления в корзину', [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при добавлении товара.');
        }
    }

    /**
     * Обновить количество товара
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        try {
            $cart = auth()->user()->cart;
            $cartItem = $cart->items()->findOrFail($itemId);
            $quantity = (int) $request->input('quantity');

            if ($quantity > $cartItem->product->stock) {
                return back()->with('error', "Недостаточно товара. Доступно: {$cartItem->product->stock}");
            }

            $cartItem->update(['quantity' => $quantity]);

            return back()->with('success', 'Количество обновлено.');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления корзины', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при обновлении.');
        }
    }

    /**
     * Удалить товар из корзины
     */
    public function remove($itemId)
    {
        try {
            $cart = auth()->user()->cart;
            $cartItem = $cart->items()->findOrFail($itemId);
            $cartItem->delete();

            return back()->with('success', 'Товар удалён из корзины.');
        } catch (\Exception $e) {
            Log::error('Ошибка удаления из корзины', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при удалении.');
        }
    }

    /**
     * Очистить корзину
     */
    public function clear()
    {
        try {
            $cart = auth()->user()->cart;
            $cart->items()->delete();

            return back()->with('success', 'Корзина очищена.');
        } catch (\Exception $e) {
            Log::error('Ошибка очистки корзины', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка при очистке корзины.');
        }
    }
}
