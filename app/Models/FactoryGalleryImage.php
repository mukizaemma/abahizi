<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryGalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'image',
        'sort_order',
    ];

    public static function publicUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        return asset('storage/' . $normalized);
    }

    public function url(): string
    {
        return self::publicUrl($this->image);
    }
}
