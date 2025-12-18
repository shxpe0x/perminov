<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $request->validate([
            'type' => ['nullable', Rule::in(['computer', 'peripheral'])],
            'q' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['id', 'price'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],
            'perPage' => ['nullable', 'integer', 'min:5', 'max:50'],
        ]);

        $filters = [
            'type' => $request->query('type'),
            'search' => trim((string) $request->query('q', '')),
            'sort_by' => $request->query('sort', 'id'),
            'sort_order' => $request->query('dir', 'desc'),
        ];

        $perPage = (int) $request->query('perPage', 10);

        // Используем ProductService с кешированием
        $products = $this->productService->getProductsCached($filters, $perPage);

        // Для совместимости с view
        $type = $filters['type'];
        $q = $filters['search'];
        $sort = $filters['sort_by'];
        $dir = $filters['sort_order'];

        return view('catalog.index', compact('products', 'type', 'q', 'sort', 'dir', 'perPage'));
    }

    public function show(Product $product)
    {
        // Eager loading связей
        $product->load(['category', 'reviews.user']);

        // Получаем рейтинг из кеша
        $rating = $this->productService->getProductRating($product);

        return view('catalog.show', compact('product', 'rating'));
    }
}
