<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrepreneurship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'education',
        'business_stage',
        'business_details',
        'business_offering',
        'business_revenue',
        'business_costs',
        'business_assistance',
        'assistance_cost',
        'assistance_beneficiaries',
        'business_upload',
        'finance_upload',
        'account',
        'account_number',
        'motivation',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }
}
