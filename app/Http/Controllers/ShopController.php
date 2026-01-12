<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('shop.index', compact('categories', 'products'));
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
