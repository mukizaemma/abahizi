<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRequest extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'product_description',
        'product_id',
        'product_reference',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
