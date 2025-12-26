<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Support\Facades\Log;

class SendOrderCancellationNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderCancelled $event): void
    {
        // TODO: Реализовать отправку email/SMS уведомления
        // Пример:
        // Mail::to($event->order->user->email)->send(new OrderCancelledMail($event->order));
        
        Log::info('Order cancellation notification sent', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
        ]);
    }
}
