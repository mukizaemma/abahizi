<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnershipInquiry extends Model
{
    protected $fillable = [
        'organization',
        'full_name',
        'phone',
        'email',
        'interests',
        'message',
    ];
}
