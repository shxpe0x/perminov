<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductRatingCache implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ReviewCreated $event): void
    {
        $review = $event->review;
        $productService = app(ProductService::class);

        // Обновляем кеш рейтинга
        $productService->updateProductRating($review->product_id);
    }
}
