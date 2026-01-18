<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'price' => ['required', 'regex:/^\d+([.,]\d{1,2})?$/'],

            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $priceCents = $this->toCents($data['price']);
        $slug = $this->uniqueSlug($data['name']);

        Product::create([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_cents' => $priceCents,
            'stock' => (int) $data['stock'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produkt dodany.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'regex:/^\d+([.,]\d{1,2})?$/'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        $priceCents = $this->toCents($data['price']);

        $slug = $product->name === $data['name']
            ? $product->slug
            : $this->uniqueSlug($data['name'], $product->id);

        $product->update([
            'category_id' => (int) $data['category_id'],
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'price_cents' => $priceCents,
            'stock' => (int) $data['stock'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produkt zaktualizowany.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('seller.products.index')->with('success', 'Produkt usunięty.');
    }

    private function uniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Product::where('slug', $slug)
                ->when($ignoreProductId, fn ($q) => $q->where('id', '!=', $ignoreProductId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    private function toCents(string $price): int
    {
        $normalized = str_replace(',', '.', trim($price));

        if (!str_contains($normalized, '.')) {
            $normalized .= '.00';
        }

        [$zl, $gr] = array_pad(explode('.', $normalized, 2), 2, '0');
        $gr = substr(str_pad($gr, 2, '0'), 0, 2);

        return ((int) $zl) * 100 + (int) $gr;
    }
}

