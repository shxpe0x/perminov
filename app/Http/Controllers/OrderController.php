<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Список заказов пользователя
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Просмотр заказа
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    // Форма оформления заказа
    public function create()
    {
        $cartItems = auth()->user()
            ->cartItems()
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Корзина пуста');
        }

        $total = $cartItems->sum(fn($item) => $item->subtotal);

        return view('orders.create', compact('cartItems', 'total'));
    }

    // Создание заказа
    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => ['required', 'string', 'max:500'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Корзина пуста');
        }

        try {
            DB::beginTransaction();

            // Создаём заказ
            $total = $cartItems->sum(fn($item) => $item->subtotal);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'status' => Order::STATUS_NEW,
                'total_price' => $total,
                'delivery_address' => $request->input('delivery_address'),
                'comment' => $request->input('comment'),
            ]);

            // Добавляем товары в заказ
            foreach ($cartItems as $cartItem) {
                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
            }

            // Очищаем корзину
            $user->cartItems()->delete();

            DB::commit();

            Log::info('Заказ создан', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'total' => $total,
            ]);

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка создания заказа', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Ошибка при создании заказа. Попробуйте снова.');
        }
    }

    // Отмена заказа
    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if ($order->status !== Order::STATUS_NEW) {
            return back()->with('error', 'Можно отменить только новый заказ');
        }

        try {
            $order->update(['status' => Order::STATUS_CANCELLED]);

            return back()->with('success', 'Заказ отменён');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка отмены заказа');
        }
    }
}
