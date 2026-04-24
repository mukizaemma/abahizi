<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStorySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
    ];

    public static function firstOrSingleton(): self
    {
        $row = static::query()->first();
        if ($row) {
            return $row;
        }

        return static::query()->create([
            'heading' => 'What goes into our handbags',
        ]);
    }
}
