<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order->load(['items.product', 'user']);

        Log::info('Отправка уведомления о создании заказа', [
            'order_id' => $order->id,
            'user_email' => $order->user->email,
        ]);

        // TODO: Реализовать отправку email через Mail::to($order->user)->send(new OrderCreatedMail($order));
        // Пока только логируем
    }
}
