<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderCancelledNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order;

        // TODO: Реализовать отправку email
        // Mail::to($order->user->email)->send(new OrderCancelledMail($order));

        Log::info('Отправлено уведомление об отмене заказа', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);
    }
}
