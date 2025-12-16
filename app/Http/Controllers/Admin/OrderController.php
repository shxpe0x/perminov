<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
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
        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    // Обновить статус
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::statuses()))],
        ]);

        try {
            $oldStatus = $order->status;
            $order->update([
                'status' => $request->input('status'),
            ]);

            Log::info('Статус заказа обновлён', [
                'admin_id' => auth()->id(),
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status,
            ]);

            return back()->with('success', 'Статус заказа обновлён');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления статуса', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Ошибка обновления статуса');
        }
    }
}
