<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table= "programs";
    protected $fillable = [
        'title',
        'description',
        'slug',
        'image',
        'added_by',
    ];

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    public function images(){
        return $this->hasMany(Programimage::class);
    }
}
