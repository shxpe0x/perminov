<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->orderByDesc('id')->paginate(10);

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

            // Загрузка изображения, если есть
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path; // сохраняем относительный путь в БД
            }

            $product = Product::query()->create($data);

            Log::info('Товар создан', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар добавлен.');
        } catch (\Exception $e) {
            Log::error('Ошибка создания товара', [
                'admin_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Ошибка при создании товара. Попробуйте снова.');
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

            // Если загружено новое изображение
            if ($request->hasFile('image')) {
                // Удаляем старый файл, если был
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }

                $path = $request->file('image')->store('products', 'public');
                $data['image'] = $path;
            }

            $product->update($data);

            Log::info('Товар обновлён', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар обновлён.');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления товара', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Ошибка при обновлении товара. Попробуйте снова.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            $productId = $product->id;

            // Удаляем файл изображения, если есть
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            Log::info('Товар удалён', [
                'admin_id' => auth()->id(),
                'product_id' => $productId,
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Товар удалён.');
        } catch (\Exception $e) {
            Log::error('Ошибка удаления товара', [
                'admin_id' => auth()->id(),
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Ошибка при удалении товара. Попробуйте снова.');
        }
    }
}
