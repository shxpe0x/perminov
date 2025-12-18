<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;

class ReviewForm extends Component
{
    public Product $product;
    public $rating = 5;
    public $comment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10|max:1000',
    ];

    protected $messages = [
        'rating.required' => 'Пожалуйста, выберите оценку',
        'rating.min' => 'Минимальная оценка - 1',
        'rating.max' => 'Максимальная оценка - 5',
        'comment.required' => 'Напишите отзыв',
        'comment.min' => 'Отзыв должен содержать минимум 10 символов',
        'comment.max' => 'Отзыв не может быть длиннее 1000 символов',
    ];

    public function submit()
    {
        if (!auth()->check()) {
            return $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Войдите, чтобы оставить отзыв'
            ]);
        }

        // Check if user has purchased this product
        $hasPurchased = auth()->user()->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $this->product->id))
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            return $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Вы можете оставить отзыв только на купленный товар'
            ]);
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($existingReview) {
            return $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Вы уже оставили отзыв на этот товар'
            ]);
        }

        $this->validate();

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->reset(['rating', 'comment']);
        $this->rating = 5;

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Отзыв успешно добавлен!'
        ]);

        $this->dispatch('review-added');
    }

    public function render()
    {
        return view('livewire.review-form');
    }
}
