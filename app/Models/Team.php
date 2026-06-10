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
