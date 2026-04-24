<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.product-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = Str::slug($request->input('name'));
        $base = $slug;
        $i = 1;
        while (ProductCategory::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        ProductCategory::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'sort_order' => (int) ($request->input('sort_order', 0)),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('productCategories.index')->with('success', 'Category added.');
    }

    public function update(Request $request, $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $category->name = $request->input('name');
        $category->sort_order = (int) ($request->input('sort_order', 0));
        $category->is_active = $request->has('is_active');

        if ($category->isDirty('name')) {
            $slug = Str::slug($request->input('name'));
            $base = $slug;
            $j = 1;
            while (
                ProductCategory::query()
                    ->where('slug', $slug)
                    ->where('id', '!=', $category->id)
                    ->exists()
            ) {
                $slug = $base . '-' . $j;
                $j++;
            }
            $category->slug = $slug;
        }

        $category->save();

        return redirect()->route('productCategories.index')->with('success', 'Category updated.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        if ($category->products()->exists()) {
            return redirect()->route('productCategories.index')->with('error', 'Cannot delete: products still use this category.');
        }
        $category->delete();

        return redirect()->route('productCategories.index')->with('success', 'Category deleted.');
    }
}
