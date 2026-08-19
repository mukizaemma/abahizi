<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'author',
        'body',
        'slug',
        'image',
        'added_by',
        'published_at',
        'published_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function blogimages()
    {
        return $this->hasMany(Blogimages::class);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    public function scopeLatestPublished($query)
    {
        return $query->published()->latest('published_at');
    }

    public function isPublished(): bool
    {
        return ! is_null($this->published_at);
    }

    public function displayDate()
    {
        return $this->published_at ?? $this->created_at;
    }

    public function plainBody(): string
    {
        $text = html_entity_decode(strip_tags((string) $this->body), ENT_QUOTES, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    }

    public function previewText(int $limit = 140): string
    {
        return Str::limit($this->plainBody(), $limit);
    }

    public function needsReadMore(int $limit = 140): bool
    {
        return Str::length($this->plainBody()) > $limit;
    }

    public function excerptText(int $limit = 180): string
    {
        return $this->previewText($limit);
    }

    public function coverUrl(): ?string
    {
        return static::publicImageUrl($this->image);
    }

    public static function publicImageUrl(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'storage/')) {
            return asset($normalized);
        }

        if (str_starts_with($normalized, 'images/')) {
            return asset('storage/' . $normalized);
        }

        return asset('storage/images/news/' . $normalized);
    }
}
