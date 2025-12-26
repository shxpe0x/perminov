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
            'type' => ['nullable', Rule::in(['computer', 'keyboard', 'mouse', 'headphones', 'monitor', 'webcam', 'speaker'])],
            'q' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', Rule::in(['default', 'price_asc', 'price_desc', 'name_asc', 'name_desc', 'newest'])],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'in_stock' => ['nullable', 'boolean'],
            'brands' => ['nullable', 'array'],
            'brands.*' => ['string'],
        ]);

        // Start query
        $query = Product::query()->with('category');

        // Type filter
        $type = $request->query('type');
        if ($type) {
            $query->where('type', $type);
        }

        // Search filter with proper escaping
        $q = trim((string) $request->query('q', ''));
        if ($q) {
            // Escape special LIKE characters
            $escapedQ = str_replace(['%', '_'], ['\\%', '\\_'], $q);
            
            $query->where(function ($query) use ($escapedQ) {
                $query->where('brand', 'LIKE', "%{$escapedQ}%")
                      ->orWhere('model', 'LIKE', "%{$escapedQ}%")
                      ->orWhere('description', 'LIKE', "%{$escapedQ}%");
            });
        }

        // Price range filter
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Stock filter
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Brand filter
        if ($request->filled('brands')) {
            $query->whereIn('brand', $request->brands);
        }

        // Sorting
        $sort = $request->query('sort', 'default');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('brand', 'asc')->orderBy('model', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('brand', 'desc')->orderBy('model', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Get products with pagination
        $products = $query->paginate(12)->withQueryString();

        // Get unique brands for filter (only from non-deleted products)
        $brands = Product::query()
            ->select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('catalog.index', compact('products', 'type', 'q', 'sort', 'brands'));
    }

    public function show(Product $product)
    {
        // Eager loading связей и исключение soft deleted
        $product->loadMissing(['category', 'reviews.user']);

        return view('catalog.show', compact('product'));
    }
}
