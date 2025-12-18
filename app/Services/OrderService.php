<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Events\OrderCreated;
use App\Events\OrderCancelled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Создать заказ из корзины
     */
    public function createOrderFromCart(Cart $cart, array $data): Order
    {
        if ($cart->items->isEmpty()) {
            throw new \Exception('Корзина пуста');
        }

        // Проверяем наличие товаров на складе
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                throw new \Exception("Товар {$item->product->brand} {$item->product->model} закончился на складе");
            }
        }

        return DB::transaction(function () use ($cart, $data) {
            // Создаём заказ
            $order = Order::create([
                'user_id' => $cart->user_id,
                'status' => Order::STATUS_NEW,
                'total_price' => $cart->total,
                'delivery_address' => $data['delivery_address'],
                'phone' => $data['phone'],
                'comment' => $data['comment'] ?? null,
            ]);

            // Копируем товары из корзины в заказ
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

            // Логируем создание заказа
            Log::info('Заказ создан', [
                'order_id' => $order->id,
                'user_id' => $cart->user_id,
                'total_price' => $order->total_price,
            ]);

            // Генерируем событие
            event(new OrderCreated($order));

            return $order;
        });
    }

    /**
     * Обновить статус заказа
     */
    public function updateOrderStatus(Order $order, string $newStatus): Order
    {
        $oldStatus = $order->status;

        // Валидация статуса
        $validStatuses = [
            Order::STATUS_NEW,
            Order::STATUS_PAID,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ];

        if (!in_array($newStatus, $validStatuses)) {
            throw new \Exception('Недопустимый статус заказа');
        }

        // Проверка возможности смены статуса
        if ($order->status === Order::STATUS_CANCELLED) {
            throw new \Exception('Невозможно изменить статус отменённого заказа');
        }

        if ($order->status === Order::STATUS_DELIVERED && $newStatus !== Order::STATUS_CANCELLED) {
            throw new \Exception('Невозможно изменить статус доставленного заказа');
        }

        DB::transaction(function () use ($order, $newStatus, $oldStatus) {
            $order->update(['status' => $newStatus]);

            // Если заказ отменяется, возвращаем товары на склад
            if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                // Генерируем событие отмены
                event(new OrderCancelled($order));
            }

            Log::info('Статус заказа изменён', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
        });

        return $order->fresh();
    }

    /**
     * Получить заказ с подробностями (eager loading)
     */
    public function getOrderWithDetails(int $orderId): Order
    {
        return Order::with(['items.product', 'user'])
            ->findOrFail($orderId);
    }

    /**
     * Получить заказы пользователя с пагинацией
     */
    public function getUserOrders(int $userId, int $perPage = 10)
    {
        return Order::with(['items.product'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
