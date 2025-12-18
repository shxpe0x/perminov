<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductRatingCache
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected ProductService $productService
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReviewCreated $event): void
    {
        // Очистить кеш рейтинга товара
        $this->productService->updateProductRating($event->review->product);
    }
}
