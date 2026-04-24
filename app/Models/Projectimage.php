<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projectimage extends Model
{
    use HasFactory;
    protected $table= "projectimages";
    protected $fillable = [
        'added_by',
        'activity_id',
        'caption',
        'image'
    ];

    public function project(){
        return $this->belongsTo(Activity::class);
    }
}
