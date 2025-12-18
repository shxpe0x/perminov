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
        $cart = Auth::user()->cart()->with(['items.product.category'])->first();
        
        return view('cart.index', compact('cart'));
    }

    /**
     * Add product to cart (AJAX).
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
            return response()->json([
                'message' => 'Недостаточно товара на складе',
            ], 400);
        }

        // Get or create cart
        $cart = Auth::user()->cart()->firstOrCreate([]);

        // Check if product already in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Check if we can add more
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return response()->json([
                    'message' => 'Недостаточно товара на складе. В корзине уже ' . $cartItem->quantity . ' шт.',
                ], 400);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Add new item
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        // Update cart total
        $cart->updateTotal();

        return response()->json([
            'message' => 'Товар добавлен в корзину',
            'count' => $cart->items()->sum('quantity'),
        ]);
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
        $item->cart->updateTotal();

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

        $cart = $item->cart;
        $item->delete();
        $cart->updateTotal();

        return back()->with('success', 'Товар удалён из корзины');
    }

    /**
     * Get cart items count (AJAX).
     */
    public function count()
    {
        $cart = Auth::user()->cart;
        $count = $cart ? $cart->items()->sum('quantity') : 0;

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
            $cart->updateTotal();
        }

        return back()->with('success', 'Корзина очищена');
    }
}