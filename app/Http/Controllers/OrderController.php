<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display user's orders.
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show checkout form.
     */
    public function create()
    {
        $cart = Auth::user()->cart()->with(['items.product'])->first();

        // Redirect if cart is empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Ваша корзина пуста');
        }

        // Check stock for all items
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', 'Товар "' . $item->product->brand . ' ' . $item->product->model . '" закончился на складе');
            }
        }

        return view('orders.create', compact('cart'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house' => 'required|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:card,cash',
            'comment' => 'nullable|string|max:1000',
        ]);

        $cart = Auth::user()->cart()->with(['items.product'])->first();

        // Validate cart exists and not empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Ваша корзина пуста');
        }

        DB::beginTransaction();

        try {
            // Check stock and calculate total
            $total = 0;
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Товар "' . $item->product->brand . ' ' . $item->product->model . '" закончился на складе');
                }
                $total += $item->price * $item->quantity;
            }

            // Build delivery address
            $deliveryAddress = sprintf(
                '%s, %s, д. %s%s%s',
                $validated['city'],
                $validated['street'],
                $validated['house'],
                $validated['apartment'] ? ', кв. ' . $validated['apartment'] : '',
                $validated['postal_code'] ? ', ' . $validated['postal_code'] : ''
            );

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total' => $total,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'delivery_address' => $deliveryAddress,
                'payment_method' => $validated['payment_method'],
                'comment' => $validated['comment'] ?? null,
            ]);

            // Create order items and decrease stock
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Decrease stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Произошла ошибка при оформлении заказа');
        }
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order.
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Этот заказ нельзя отменить');
        }

        DB::beginTransaction();

        try {
            // Return stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            DB::commit();

            return back()->with('success', 'Заказ отменён');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Произошла ошибка при отмене заказа');
        }
    }
}