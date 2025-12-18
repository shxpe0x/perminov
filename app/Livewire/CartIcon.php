<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CartIcon extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount()
    {
        if (auth()->check()) {
            $this->count = auth()->user()->cart?->items()->sum('quantity') ?? 0;
        } else {
            $this->count = session()->get('cart_count', 0);
        }
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
