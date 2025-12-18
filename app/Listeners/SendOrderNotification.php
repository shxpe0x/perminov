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
        // Заглушка для отправки email
        // В будущем можно интегрировать Mail::to($event->order->user->email)
        
        Log::info('Отправлено уведомление о создании заказа', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'user_email' => $event->order->user->email,
            'total_price' => $event->order->total_price,
        ]);

        // TODO: Реализовать отправку email
        // Mail::to($event->order->user->email)
        //     ->send(new OrderCreatedMail($event->order));
    }
}
