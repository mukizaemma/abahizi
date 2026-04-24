<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'title',
        'slug',
        'description',
        'image',
        'price',
        'compare_at_price',
        'stock_quantity',
        'is_new',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_new' => 'boolean',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug) && ! empty($product->title)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Cover image URL segment for storage disk public (e.g. images/products/..).
     */
    public function coverStoragePath(): ?string
    {
        return $this->image ?: null;
    }

    public function discountPercent(): ?int
    {
        $compare = $this->compare_at_price;
        $price = $this->price;
        if ($compare === null || (float) $compare <= 0 || (float) $price >= (float) $compare) {
            return null;
        }

        return (int) round((1 - (float) $price / (float) $compare) * 100);
    }

    public function inStock(): bool
    {
        return (int) $this->stock_quantity > 0;
    }
}
