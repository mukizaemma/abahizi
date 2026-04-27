<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    use HasFactory;

    protected $fillable = [
        'names',
        'title',
        'slug',
        'age',
        'program_id',
        'testimony',
        'video_url',
        'image',
        'status',
        'added_by',
    ];
}
