<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Log order confirmation
        Log::info('Order confirmation notification sent', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'total_price' => $order->total_price,
        ]);

        // TODO: Send email notification
        // Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
        
        // TODO: Send SMS notification
        // SMS::send($order->phone, 'Ваш заказ #' . $order->id . ' принят!');
    }
}
