<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:categories,slug'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $category = Category::query()->create($request->only(['name', 'slug', 'description']));

            Log::info('Категория создана', [
                'admin_id' => auth()->id(),
                'category_id' => $category->id,
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Категория создана');
        } catch (\Exception $e) {
            Log::error('Ошибка создания категории', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Ошибка создания категории');
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'slug' => ['nullable', 'string', 'max:100', 'unique:categories,slug,' . $category->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $category->update($request->only(['name', 'slug', 'description']));

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Категория обновлена');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ошибка обновления');
        }
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Нельзя удалить категорию с товарами');
        }

        try {
            $category->delete();

            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Категория удалена');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка удаления');
        }
    }
}
