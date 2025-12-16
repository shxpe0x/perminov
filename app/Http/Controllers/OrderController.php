<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * История заказов пользователя
     */
    public function index()
    {
        $orders = Auth::user()->orders()->orderByDesc('created_at')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Форма оформления заказа
     */
    public function create()
    {
        $cart = Auth::user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста.');
        }

        return view('orders.create', compact('cart'));
    }

    /**
     * Создание заказа
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $cart = Auth::user()->cart;

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Корзина пуста.');
            }

            // Проверка наличия
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    return back()->withInput()->with('error', "Товар {$item->product->brand} {$item->product->model} закончился на складе.");
                }
            }

            DB::beginTransaction();

            // Создаём заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'status' => Order::STATUS_NEW,
                'total_amount' => $cart->total,
                'customer_name' => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_email' => $request->input('customer_email'),
                'delivery_address' => $request->input('delivery_address'),
                'notes' => $request->input('notes'),
            ]);

            // Копируем товары из корзины в заказ
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'product_name' => $item->product->model,
                    'product_brand' => $item->product->brand,
                    'product_model' => $item->product->model,
                ]);

                // Уменьшаем остатки
                $item->product->decrement('stock', $item->quantity);
            }

            // Очищаем корзину
            $cart->items()->delete();

            DB::commit();

            Log::info('Заказ создан', [
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ успешно оформлен!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка создания заказа', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Ошибка при оформлении заказа.');
        }
    }

    /**
     * Просмотр заказа
     */
    public function show(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }
}
