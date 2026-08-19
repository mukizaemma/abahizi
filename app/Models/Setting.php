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
        'heading_font' => 'string',
        'page_header_image' => 'string',
        'page_header_caption' => 'string',
        'page_headers' => 'array',
        'hero_video_url' => 'string',
        'hero_poster' => 'string',
        'hero_headline' => 'string',
        'hero_subheadline' => 'string',
        'hero_media_type' => 'string',
        'google_map_embed_code' => 'string',
        'show_products_publicly' => 'boolean',
        'show_products_page' => 'boolean',
        'accept_order_requests' => 'boolean',
    ];

    public static function firstOrEmpty(): self
    {
        return static::first() ?? new static();
    }

    public function resolvedHeroMediaType(): string
    {
        $type = strtolower(trim((string) ($this->hero_media_type ?? '')));
        if (in_array($type, ['slideshow', 'image', 'video'], true)) {
            return $type;
        }

        if (trim((string) ($this->hero_video_url ?? '')) !== '') {
            return 'video';
        }

        return 'slideshow';
    }

    public function heroPosterPublicUrl(): ?string
    {
        $path = trim((string) ($this->hero_poster ?? ''));
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'images/')) {
            return asset('storage/' . $path);
        }

        return asset('storage/images/' . $path);
    }

    public function heroVideoPublicUrl(): ?string
    {
        $path = trim((string) ($this->hero_video_url ?? ''));
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (str_contains($path, '/')) {
            return asset('storage/' . $path);
        }

        return asset('storage/videos/' . $path);
    }
}
