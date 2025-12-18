<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = Auth::user()->cart()->with(['items.product'])->first();
        
        return view('cart.index', compact('cart'));
    }

    /**
     * Add product to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Check stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        // Get or create cart
        $cart = Auth::user()->cart;
        if (!$cart) {
            $cart = Auth::user()->cart()->create();
        }

        // Check if product already in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Check if we can add more
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Недостаточно товара на складе. В корзине уже ' . $cartItem->quantity . ' шт.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Add new item with price
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Товар добавлен в корзину!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if item belongs to user's cart
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        // Check stock
        if ($request->quantity > $item->product->stock) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Количество обновлено');
    }

    /**
     * Remove item from cart.
     */
    public function remove(CartItem $item)
    {
        // Check if item belongs to user's cart
        if ($item->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Товар удалён из корзины');
    }

    /**
     * Get cart items count (AJAX).
     */
    public function count()
    {
        $cart = Auth::user()->cart;
        $count = $cart ? $cart->items->sum('quantity') : 0;

        return response()->json(['count' => $count]);
    }

    /**
     * Clear entire cart.
     */
    public function clear()
    {
        $cart = Auth::user()->cart;
        
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('success', 'Корзина очищена');
    }
}