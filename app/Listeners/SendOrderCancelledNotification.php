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
        // Заглушка для отправки email
        // В будущем можно интегрировать Mail::to($event->order->user->email)
        
        Log::info('Отправлено уведомление об отмене заказа', [
            'order_id' => $event->order->id,
            'user_id' => $event->order->user_id,
            'user_email' => $event->order->user->email,
            'status' => $event->order->status,
        ]);

        // TODO: Реализовать отправку email
        // Mail::to($event->order->user->email)
        //     ->send(new OrderCancelledMail($event->order));
    }
}
