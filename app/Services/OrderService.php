<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /**
     * Создать заказ из корзины
     */
    public function createOrderFromCart(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = $user->cart;

            // Проверка пустой корзины
            if (!$cart || $cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Корзина пуста']
                ]);
            }

            // Проверка наличия товаров
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => ["Товар '{$item->product->brand} {$item->product->model}' недоступен в нужном количестве"]
                    ]);
                }
            }

            // Создание заказа
            $order = Order::create([
                'user_id' => $user->id,
                'status' => Order::STATUS_NEW,
                'total_price' => $cart->total_price,
                'delivery_address' => $data['delivery_address'],
                'phone' => $data['phone'] ?? $user->phone,
                'comment' => $data['comment'] ?? null,
            ]);

            // Копирование товаров из корзины в заказ
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Уменьшение остатка товара
                $item->product->decrement('stock', $item->quantity);
            }

            // Очистка корзины
            $cart->items()->delete();

            // Логирование
            Log::info('Заказ создан', [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'total_price' => $order->total_price,
                'items_count' => $order->items->count(),
            ]);

            return $order;
        });
    }

    /**
     * Обновить статус заказа
     */
    public function updateOrderStatus(Order $order, string $newStatus): Order
    {
        $oldStatus = $order->status;

        // Проверка валидности статуса
        if (!array_key_exists($newStatus, Order::statuses())) {
            throw ValidationException::withMessages([
                'status' => ['Неверный статус заказа']
            ]);
        }

        // Обновление
        $order->update(['status' => $newStatus]);

        // Логирование
        Log::info('Статус заказа изменён', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        return $order->fresh();
    }

    /**
     * Получить заказ с деталями (eager loading)
     */
    public function getOrderWithDetails(Order $order): Order
    {
        return $order->load(['items.product', 'user']);
    }

    /**
     * Отменить заказ
     */
    public function cancelOrder(Order $order): Order
    {
        // Проверка возможности отмены
        if (!$order->isCancellable()) {
            throw ValidationException::withMessages([
                'order' => ['Заказ нельзя отменить в текущем статусе']
            ]);
        }

        return DB::transaction(function () use ($order) {
            // Возврат товаров на склад
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Смена статуса
            $order->update(['status' => Order::STATUS_CANCELLED]);

            // Логирование
            Log::info('Заказ отменён', [
                'order_id' => $order->id,
                'items_returned' => $order->items->count(),
            ]);

            return $order->fresh();
        });
    }
}
