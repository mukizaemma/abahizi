<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnualReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'heading',
        'description',
        'highlight_title',
        'highlight_message',
        'pdf_button_label',
        'pdf',
        'slug',
        'sort_order',
        'status',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(AnnualReportImage::class)->orderBy('sort_order')->orderBy('id');
    }

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

    public function pdfButtonLabel(): string
    {
        if (! empty($this->pdf_button_label)) {
            return $this->pdf_button_label;
        }

        return 'Read the ' . $this->heading;
    }
}
