<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Log;

class SendOrderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        // Логирование создания заказа
        Log::info('Заказ создан - уведомление', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'user_name' => $event->order->user->name,
            'total_price' => $event->order->total_price,
            'items_count' => $event->order->items->count(),
        ]);

        // Для учебного проекта достаточно логирования
        // В продакшене здесь была бы отправка email:
        // Mail::to($event->order->user->email)->send(new OrderCreatedMail($event->order));
    }
}
