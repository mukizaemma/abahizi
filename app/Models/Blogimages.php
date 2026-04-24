<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogimages extends Model
{
    use HasFactory;
    protected $table = 'blogimages';
    protected $fillable = ['gallery', 'caption', 'news_id'];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
