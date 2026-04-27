<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'primary_color' => 'string',
        'secondary_color' => 'string',
        'neutral_color' => 'string',
        'font_family' => 'string',
        'page_header_image' => 'string',
        'page_header_caption' => 'string',
        'google_map_embed_code' => 'string',
        'show_products_publicly' => 'boolean',
    ];

    public static function firstOrEmpty(): self
    {
        return static::first() ?? new static();
    }
}
