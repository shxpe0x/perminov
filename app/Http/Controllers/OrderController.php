<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Список заказов пользователя
     */
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('items.product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Показать заказ
     */
    public function show(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Форма оформления заказа
     */
    public function create()
    {
        $cart = auth()->user()->cart()->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Корзина пуста.');
        }

        return view('orders.create', compact('cart'));
    }

    /**
     * Новый Заказ
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $cart = auth()->user()->cart()->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return back()->with('error', 'Корзина пуста.');
            }

            // Проверяем наличие товаров
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    return back()->with('error', "Товар {$item->product->brand} {$item->product->model} закончился на складе.");
                }
            }

            DB::beginTransaction();

            // Создаэм заказ
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => Order::STATUS_NEW,
                'total_price' => $cart->total,
                'delivery_address' => $request->delivery_address,
                'phone' => $request->phone,
                'comment' => $request->comment,
            ]);

            // Копируем элементы из корзины
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                // Уменьшаем остаток на складе
                $item->product->decrement('stock', $item->quantity);
            }

            // Очищаем корзину
            $cart->items()->delete();

            DB::commit();

            Log::info('Заказ создан', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'total' => $order->total_price,
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Ошибка создания заказа', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Ошибка при оформлении заказа. Попробуйте снова.');
        }
    }
}
