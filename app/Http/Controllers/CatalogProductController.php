<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class CatalogProductController extends Controller
{
    public function index()
    {
        $products = Product::query()->with(['category', 'images'])->latest()->paginate(25);

        return view('admin.catalog-products.index', compact('products'));
    }

    public function saveHomepageCards(Request $request)
    {
        $rules = [];
        foreach ([1, 2, 3] as $slot) {
            $rules[\App\Support\HomeProductShowcase::titleField($slot)] = ['nullable', 'string', 'max:80'];
            $rules[\App\Support\HomeProductShowcase::imageField($slot)] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'];
        }

        $request->validate($rules);

        $about = \App\Models\Background::first();
        if ($about === null) {
            $about = new \App\Models\Background();
            $about->description = 'Our Background';
            $about->save();
        }

        \App\Support\HomeProductShowcase::save($request, $about);

        return redirect()
            ->route('catalogProducts.homepage')
            ->with('success', 'Homepage product cards saved. Refresh the public homepage to see them.');
    }

    public function create()
    {
        $categories = ProductCategory::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.catalog-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'is_new' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $slug = $this->uniqueSlug($request->input('title'));

        $product = new Product();
        $product->title = $request->input('title');
        $product->slug = $slug;
        $product->product_category_id = (int) $request->input('product_category_id');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->compare_at_price = $request->input('compare_at_price');
        $product->stock_quantity = (int) ($request->input('stock_quantity', 0));
        $product->color = $request->input('color');
        $product->sort_order = (int) ($request->input('sort_order', 0));
        $product->is_new = $request->has('is_new');
        $product->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('images/products', 'public');
        }

        $product->save();

        $this->storeGalleryImages($product, $request);

        return redirect()->route('catalogProducts.edit', $product->id)->with('success', 'Product created.');
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = ProductCategory::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.catalog-products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            'is_new' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        $newTitle = $request->input('title');
        if ($product->title !== $newTitle) {
            $product->slug = $this->uniqueSlug($newTitle, $product->id);
        }
        $product->title = $newTitle;
        $product->product_category_id = (int) $request->input('product_category_id');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->compare_at_price = $request->input('compare_at_price');
        $product->stock_quantity = (int) ($request->input('stock_quantity', 0));
        $product->color = $request->input('color');
        $product->sort_order = (int) ($request->input('sort_order', 0));
        $product->is_new = $request->has('is_new');
        $product->is_active = $request->has('is_active');

        if ($request->hasFile('image')) {
            if (! empty($product->image) && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('images/products', 'public');
        }

        $product->save();

        $this->storeGalleryImages($product, $request);

        return redirect()->route('catalogProducts.edit', $product->id)->with('success', 'Product updated.');
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        if (! empty($product->image) && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($product->images as $img) {
            if (! empty($img->image) && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
        }

        $product->delete();

        return redirect()->route('catalogProducts.index')->with('success', 'Product deleted.');
    }

    public function deleteImage($id)
    {
        $img = ProductImage::findOrFail($id);
        $productId = $img->product_id;

        if (! empty($img->image) && Storage::disk('public')->exists($img->image)) {
            Storage::disk('public')->delete($img->image);
        }
        $img->delete();

        return redirect()->route('catalogProducts.edit', $productId)->with('success', 'Image removed.');
    }

    public function homepage()
    {
        $homepageSlots = \App\Support\HomeProductShowcase::adminSlots();

        return view('admin.catalog-products.homepage', compact('homepageSlots'));
    }

    public function pageGallery()
    {
        $images = \App\Models\ProductGalleryImage::query()->orderBy('sort_order')->orderBy('id')->get();
        $about = \App\Models\Background::firstOrEmpty();

        return view('admin.catalog-products.page-gallery', compact('images', 'about'));
    }

    public function storePageGallery(Request $request)
    {
        $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
        ]);

        $maxSort = (int) \App\Models\ProductGalleryImage::query()->max('sort_order');
        \App\Models\ProductGalleryImage::create([
            'caption' => trim((string) $request->input('caption')) ?: null,
            'image' => $request->file('image')->store('images/products/page-gallery', 'public'),
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()->route('catalogProducts.pageGallery')->with('success', 'Photo added to the products page gallery.');
    }

    public function destroyPageGallery($id)
    {
        $image = \App\Models\ProductGalleryImage::findOrFail($id);
        if (! empty($image->image) && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        $image->delete();

        return redirect()->route('catalogProducts.pageGallery')->with('success', 'Photo removed from the products page gallery.');
    }

    public function savePageIntro(Request $request)
    {
        $request->validate([
            'products_intro' => ['nullable', 'string'],
        ]);
        $about = \App\Models\Background::first();
        if ($about === null) {
            $about = new \App\Models\Background();
            $about->description = 'Our Background';
        }
        if (Schema::hasColumn('backgrounds', 'products_intro')) {
            $about->products_intro = $request->input('products_intro');
            $about->save();
        }

        return redirect()->route('catalogProducts.pageGallery')->with('success', 'Products page intro saved.');
    }

    private function storeGalleryImages(Product $product, Request $request): void
    {
        $files = $request->file('gallery_images', []);
        if (empty($files)) {
            return;
        }

        $maxSort = (int) $product->images()->max('sort_order');
        foreach ($files as $file) {
            if (! $file) {
                continue;
            }
            $path = $file->store('images/products/gallery', 'public');
            $maxSort++;
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'sort_order' => $maxSort,
            ]);
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $i = 1;

        while (
            Product::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
