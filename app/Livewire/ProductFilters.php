<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilters extends Component
{
    use WithPagination;

    public $category = '';
    public $search = '';
    public $priceMin = 0;
    public $priceMax = 500000;
    public $inStock = false;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'category' => ['except' => ''],
        'search' => ['except' => ''],
        'priceMin' => ['except' => 0],
        'priceMax' => ['except' => 500000],
        'inStock' => ['except' => false],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['category', 'search', 'priceMin', 'priceMax', 'inStock']);
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::all();
        
        $products = Product::query()
            ->when($this->category, fn($q) => $q->where('category_id', $this->category))
            ->when($this->search, fn($q) => $q->search($this->search))
            ->whereBetween('price', [$this->priceMin, $this->priceMax])
            ->when($this->inStock, fn($q) => $q->inStock())
            ->orderBy($this->sortBy, $this->sortDirection)
            ->with('category')
            ->paginate(12);

        return view('livewire.product-filters', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
