<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Requests\CreateOrderRequest;
use App\Events\OrderCreated;
use App\Events\OrderCancelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Check stock and soft deletes for all items
        foreach ($cart->items as $item) {
            // Check if product is soft deleted
            if ($item->product->trashed()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Товар "' . $item->product->brand . ' ' . $item->product->model . '" больше не доступен');
            }
            
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
    public function store(CreateOrderRequest $request)
    {
        $validated = $request->validated();

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
                // Check if product is soft deleted
                if ($item->product->trashed()) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Товар "' . $item->product->brand . ' ' . $item->product->model . '" больше не доступен');
                }
                
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
                'status' => Order::STATUS_NEW,
                'total_price' => $total,
                'delivery_address' => $deliveryAddress,
                'phone' => $validated['phone'],
                'comment' => $validated['comment'] ?? null,
            ]);

            // Create order items and decrease stock atomically
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Atomic stock decrement with race condition protection
                $affected = Product::where('id', $cartItem->product_id)
                    ->where('stock', '>=', $cartItem->quantity)
                    ->decrement('stock', $cartItem->quantity);

                if (!$affected) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Товар "' . $cartItem->product->brand . ' ' . $cartItem->product->model . '" закончился на складе');
                }
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Dispatch event after successful commit
            event(new OrderCreated($order));

            // Log order creation
            Log::info('Order created', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'total' => $total
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Произошла ошибка при оформлении заказа');
        }
    }

    /**
     * Display order details.
     */
    public function show(Order $order)
    {
        // Use relationship to prevent information leakage
        $order = Auth::user()->orders()->with(['items.product'])->findOrFail($order->id);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order.
     */
    public function cancel(Order $order)
    {
        // Use relationship to prevent information leakage
        $order = Auth::user()->orders()->with(['items.product'])->findOrFail($order->id);

        // Check if order can be cancelled using model method
        if (!$order->isCancellable()) {
            return back()->with('error', 'Этот заказ нельзя отменить');
        }

        DB::beginTransaction();

        try {
            // Return stock atomically
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }

            // Update order status using constant
            $order->update(['status' => Order::STATUS_CANCELLED]);

            DB::commit();

            // Dispatch event after successful commit
            event(new OrderCancelled($order));

            // Log order cancellation
            Log::info('Order cancelled', [
                'order_id' => $order->id,
                'user_id' => Auth::id()
            ]);

            return back()->with('success', 'Заказ отменён');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order cancellation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Произошла ошибка при отмене заказа');
        }
    }
}
