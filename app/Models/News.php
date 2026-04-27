<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table= "news";
    protected $fillable = [
        'title',
        'author',
        'body',
        'slug',
        'image',
        'added_by',
    ];

    public function blogimages()
    {
        return $this->hasMany(Blogimages::class);
    }

    public function isPublished(): bool
    {
        return !is_null($this->published_at);
    }
}
