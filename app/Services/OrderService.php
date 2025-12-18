<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Events\OrderCreated;
use App\Events\OrderCancelled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderService
{
    /**
     * Создать заказ из корзины пользователя
     */
    public function createOrderFromCart(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. Проверить что корзина не пуста
            $cart = $user->cart;
            
            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Корзина пуста');
            }

            // 2. Рассчитать общую стоимость
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                if (!$item->product->isInStock() || $item->product->stock < $item->quantity) {
                    throw new Exception("Товар {$item->product->brand} {$item->product->model} недоступен в нужном количестве");
                }
                $totalAmount += $item->product->price * $item->quantity;
            }

            // 3. Создать заказ
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'delivery_address' => $data['delivery_address'] ?? null,
                'delivery_method' => $data['delivery_method'] ?? 'courier',
                'payment_method' => $data['payment_method'] ?? 'card',
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Добавить товары в order_items и обновить stock
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Уменьшаем количество товара на складе
                $item->product->decrement('stock', $item->quantity);
            }

            // 5. Очистить корзину
            $cart->items()->delete();

            // 6. Логировать создание заказа
            Log::info('Заказ создан', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
            ]);

            // 7. Отправить событие
            event(new OrderCreated($order));

            return $order;
        });
    }

    /**
     * Обновить статус заказа
     */
    public function updateOrderStatus(Order $order, string $newStatus): Order
    {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            throw new Exception('Недопустимый статус заказа');
        }

        // Проверка возможности отмены
        if ($newStatus === 'cancelled' && !$order->isCancellable()) {
            throw new Exception('Заказ нельзя отменить на текущем этапе');
        }

        $oldStatus = $order->status;
        $order->update(['status' => $newStatus]);

        Log::info('Статус заказа изменён', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        // Отправить событие об отмене
        if ($newStatus === 'cancelled') {
            // Вернуть товары на склад
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            
            event(new OrderCancelled($order));
        }

        return $order->fresh();
    }

    /**
     * Получить заказ со всеми связями (для избежания N+1)
     */
    public function getOrderWithDetails(int $orderId): ?Order
    {
        return Order::with(['items.product.category', 'user'])
            ->find($orderId);
    }

    /**
     * Получить заказы пользователя
     */
    public function getUserOrders(User $user, array $filters = [])
    {
        $query = Order::with(['items.product'])
            ->where('user_id', $user->id);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }
}
