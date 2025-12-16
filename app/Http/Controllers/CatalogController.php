<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'type' => ['nullable', Rule::in(['computer', 'peripheral'])],
            'q' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['id', 'price'])],
            'dir' => ['nullable', Rule::in(['asc', 'desc'])],
            'perPage' => ['nullable', 'integer', 'min:5', 'max:50'],
        ]);

        $type = $request->query('type');
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'id');
        $dir = $request->query('dir', 'desc');
        $perPage = (int) $request->query('perPage', 10);

        $query = Product::query();

        if ($type) {
            $query->where('type', $type);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('brand', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%");
            });
        }

        // Важно: сортировку берем только из whitelist (sort/dir мы уже провалидировали).
        $query->orderBy($sort, $dir);

        $products = $query->paginate($perPage)->withQueryString();

        return view('catalog.index', compact('products', 'type', 'q', 'sort', 'dir', 'perPage'));
    }

    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}
