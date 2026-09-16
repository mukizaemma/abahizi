<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'image',
        'sort_order',
    ];

    public function url(): string
    {
        $path = ltrim((string) $this->image, '/');
        if ($path === '') {
            return '';
        }

        return asset('storage/' . $path);
    }
}
