<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualReportImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'annual_report_id',
        'image',
        'caption',
        'sort_order',
    ];

    public function report()
    {
        return $this->belongsTo(AnnualReport::class, 'annual_report_id');
    }

    public function imageUrl(): string
    {
        $path = $this->image ?? '';

        if ($path !== '' && str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
