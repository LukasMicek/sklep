<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
public function index(Request $request)
{
    $categories = Category::orderBy('name')->get();

    $q = trim((string) $request->query('q', ''));
    $categoryId = $request->query('category_id');
    $inStock = $request->boolean('in_stock');
    $min = $request->query('min_price');
    $max = $request->query('max_price');
    $sort = $request->query('sort', 'newest');

    $query = Product::query()
        ->where('is_active', true)
        ->with('category');

    if ($q !== '') {
        $query->where(function ($sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        });
    }

    if (!empty($categoryId)) {
        $query->where('category_id', (int)$categoryId);
    }

    if ($inStock) {
        $query->where('stock', '>', 0);
    }

    if ($min !== null && $min !== '') {
        $minCents = (int) round(((float) str_replace(',', '.', $min)) * 100);
        $query->where('price_cents', '>=', $minCents);
    }

    if ($max !== null && $max !== '') {
        $maxCents = (int) round(((float) str_replace(',', '.', $max)) * 100);
        $query->where('price_cents', '<=', $maxCents);
    }

    switch ($sort) {
        case 'price_asc':
            $query->orderBy('price_cents');
            break;
        case 'price_desc':
            $query->orderByDesc('price_cents');
            break;
        case 'name_asc':
            $query->orderBy('name');
            break;
        default:
            $query->orderByDesc('created_at');
            break;
    }

    $products = $query->paginate(12)->withQueryString();

    return view('shop.index', compact('products', 'categories', 'q', 'categoryId', 'inStock', 'min', 'max', 'sort'));
}

    public function category(Category $category)
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shop.index', compact('categories', 'products', 'category'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load('category');

        return view('shop.show', compact('product'));
    }
}
