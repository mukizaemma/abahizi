<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitiativeInvolvement extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'names',
        'email',
        'phone',
        'address',
        'involvement_slug',
        'involvement_label',
        'involvement_kind',
        'note',
        'donation_amount',
        'donation_period',
        'submission_channel',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
