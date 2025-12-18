<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Log;

class SendOrderCancelledNotification
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
    public function handle(OrderCancelled $event): void
    {
        // Логирование отмены заказа
        Log::info('Заказ отменён - уведомление', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'user_name' => $event->order->user->name,
            'status' => $event->order->status,
            'total_price' => $event->order->total_price,
        ]);

        // Для учебного проекта достаточно логирования
        // В продакшене здесь была бы отправка email:
        // Mail::to($event->order->user->email)->send(new OrderCancelledMail($event->order));
    }
}
