<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderCancelledNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCancelled $event): void
    {
        $order = $event->order->load(['user']);

        Log::info('Отправка уведомления об отмене заказа', [
            'order_id' => $order->id,
            'user_email' => $order->user->email,
        ]);

        // TODO: Реализовать отправку email
    }
}
