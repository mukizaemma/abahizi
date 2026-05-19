<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
        'pdf',
        'slug',
        'sort_order',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }

    public function pdfUrl(): ?string
    {
        if (empty($this->pdf)) {
            return null;
        }

        return asset('storage/documents/impact-reports/' . $this->pdf);
    }
}
