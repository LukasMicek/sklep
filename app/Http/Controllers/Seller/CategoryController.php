<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->paginate(10);
        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
        ]);

        $slug = $this->uniqueSlug($data['name']);

        Category::create([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategoria dodana.');
    }

    public function edit(Category $category)
    {
        return view('seller.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
        ]);

        $slug = $category->name === $data['name']
            ? $category->slug
            : $this->uniqueSlug($data['name'], $category->id);

        $category->update([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategoria zaktualizowana.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('seller.categories.index')->with('success', 'Kategoria usunięta.');
    }

    private function uniqueSlug(string $name, ?int $ignoreCategoryId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreCategoryId, fn($q) => $q->where('id', '!=', $ignoreCategoryId))
                ->exists()
        ) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }
}

