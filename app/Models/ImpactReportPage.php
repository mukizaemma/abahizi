<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactReportPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public static function firstOrSingleton(): self
    {
        $row = static::query()->first();
        if ($row) {
            return $row;
        }

        return static::query()->create([
            'title' => 'Impact Reports',
            'description' => '',
        ]);
    }
}
