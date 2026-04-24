<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Background extends Model
{
    use HasFactory;

    /**
     * Single row used for hero/background images; avoids null dereference when the table is empty.
     */
    public static function firstOrEmpty(): self
    {
        return static::first() ?? new static();
    }
}
