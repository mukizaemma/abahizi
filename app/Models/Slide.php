<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    /**
     * Public URL for a slide image path as stored (full path e.g. images/slides/x.jpg, or legacy filename only).
     */
    public static function publicImageUrl(?string $image): string
    {
        if (empty($image)) {
            return '';
        }
        if (str_contains($image, '/')) {
            return asset('storage/' . $image);
        }

        return asset('storage/images/slides/' . $image);
    }
}
