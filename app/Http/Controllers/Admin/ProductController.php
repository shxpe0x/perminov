<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index()
    {
        // Eager loading и withCount для оптимизации
        $products = Product::query()
            ->with('category')
            ->withCount('reviews')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Используем ProductService
            $product = $this->productService->createProduct($data);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар добавлен.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ошибка при создании товара: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return redirect()
            ->route('products.edit', $product);
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            
            // Используем ProductService
            $product = $this->productService->updateProduct($product, $data);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар обновлён.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ошибка при обновлении товара: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Используем ProductService
            $this->productService->deleteProduct($product);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар удалён.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Ошибка при удалении товара: ' . $e->getMessage());
        }
    }
}
