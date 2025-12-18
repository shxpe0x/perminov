<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // TODO: Реализовать отправку email
        // Mail::to($order->user->email)->send(new OrderCreatedMail($order));

        Log::info('Отправлено уведомление о создании заказа', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
        ]);
    }
}
