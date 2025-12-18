<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    // Список всех заказов
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::in(array_keys(Order::statuses()))],
        ]);

        $query = Order::query()
            ->with('user', 'items')
            ->orderByDesc('created_at');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);
        $statuses = Order::statuses();

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    // Просмотр заказа
    public function show(Order $order)
    {
        // Используем OrderService для eager loading
        $order = $this->orderService->getOrderWithDetails($order);

        return view('admin.orders.show', compact('order'));
    }

    // Обновить статус
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::statuses()))],
        ]);

        try {
            // Используем OrderService
            $this->orderService->updateOrderStatus($order, $request->input('status'));

            return back()->with('success', 'Статус заказа обновлён');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка обновления статуса: ' . $e->getMessage());
        }
    }
}
