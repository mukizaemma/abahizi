<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $table= "activities";
    protected $fillable = [
        'title',
        'description',
        'slug',
        'image',
        'program_id',
        'created_at'
    ];

    public function program(){
        return $this->BelongsTo(Program::class);
    }

    public function images(){
        return $this->hasMany(Projectimage::class);
    }
}
