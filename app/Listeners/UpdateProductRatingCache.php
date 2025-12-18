<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductRatingCache implements ShouldQueue
{
    use InteractsWithQueue;

    protected ProductService $productService;

    /**
     * Create the event listener.
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Handle the event.
     */
    public function handle(ReviewCreated $event): void
    {
        $review = $event->review;
        
        // Очистить и пересоздать кеш рейтинга
        $this->productService->updateProductRatingCache($review->product_id);
    }
}
