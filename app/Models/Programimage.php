<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programimage extends Model
{
    use HasFactory;

    protected $table = 'programimages';

    protected $fillable = [
        'program_id',
        'added_by',
        'caption',
        'image',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}

