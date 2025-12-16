<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Просмотр корзины
     */
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }

    /**
     * Добавить товар в корзину
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        try {
            $cart = $this->getOrCreateCart();
            $quantity = (int) $request->input('quantity', 1);

            // Проверяем наличие
            if ($product->stock < $quantity) {
                return back()->with('error', 'Недостаточно товара на складе.');
            }

            // Если товар уже есть в корзине - увеличиваем количество
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $quantity;
                if ($product->stock < $newQuantity) {
                    return back()->with('error', 'Недостаточно товара на складе.');
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }

            return back()->with('success', 'Товар добавлен в корзину.');
        } catch (\Exception $e) {
            Log::error('Ошибка добавления в корзину', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ошибка при добавлении товара.');
        }
    }

    /**
     * Обновить количество товара
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        try {
            $cart = $this->getCart();
            if (!$cart) {
                return back()->with('error', 'Корзина пуста.');
            }

            $cartItem = $cart->items()->findOrFail($itemId);
            $quantity = (int) $request->input('quantity');

            if ($cartItem->product->stock < $quantity) {
                return back()->with('error', 'Недостаточно товара на складе.');
            }

            $cartItem->update(['quantity' => $quantity]);

            return back()->with('success', 'Количество обновлено.');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления корзины', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ошибка при обновлении.');
        }
    }

    /**
     * Удалить товар из корзины
     */
    public function remove($itemId)
    {
        try {
            $cart = $this->getCart();
            if (!$cart) {
                return back()->with('error', 'Корзина пуста.');
            }

            $cart->items()->findOrFail($itemId)->delete();

            return back()->with('success', 'Товар удалён из корзины.');
        } catch (\Exception $e) {
            Log::error('Ошибка удаления из корзины', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ошибка при удалении.');
        }
    }

    /**
     * Очистить корзину
     */
    public function clear()
    {
        try {
            $cart = $this->getCart();
            if ($cart) {
                $cart->items()->delete();
            }

            return back()->with('success', 'Корзина очищена.');
        } catch (\Exception $e) {
            Log::error('Ошибка очистки корзины', ['error' => $e->getMessage()]);
            return back()->with('error', 'Ошибка при очистке.');
        }
    }

    /**
     * Получить текущую корзину
     */
    private function getCart(): ?Cart
    {
        if (Auth::check()) {
            return Auth::user()->cart;
        }

        return Cart::where('session_id', session()->getId())->first();
    }

    /**
     * Получить или создать корзину
     */
    private function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            return Auth::user()->getOrCreateCart();
        }

        return Cart::firstOrCreate(
            ['session_id' => session()->getId()],
            ['session_id' => session()->getId()]
        );
    }
}
