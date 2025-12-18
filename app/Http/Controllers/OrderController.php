<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {
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

        // Используем OrderService для eager loading
        $order = $this->orderService->getOrderWithDetails($order);

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
            $data = $request->validated();
            
            // Используем OrderService
            $order = $this->orderService->createOrderFromCart(auth()->user(), $data);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Заказ №' . $order->id . ' успешно оформлен!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }
}
