<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'names',
        'position',
        'slug',
        'bio',
        'image',
        'category',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'phone',
        'email',
        'display',
        'status',
        'sort_order',
    ];

    public function isVisibleOnSite(): bool
    {
        return strcasecmp(trim((string) $this->display), 'Yes') === 0;
    }

    public function scopeVisibleOnSite($query)
    {
        return $query->whereRaw('LOWER(TRIM(display)) = ?', ['yes']);
    }

    public function scopeOrderedForDisplay($query)
    {
        return $query
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function imageUrl(): string
    {
        if (empty($this->image)) {
            return '';
        }

        return asset('storage/images/staff/' . ltrim($this->image, '/'));
    }
}
