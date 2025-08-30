<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'gender',
        'd_o_b',
        'phone',
        'marital_status',
        'identity_number',
        'id_upload',
        'residential_address',
        'living_situation',
        'inkhundla',
        'pathway',
        'region',
        'disability',
        'disability_name',
        'family_situation',
        'family_role',
        'beneficiaries',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entrepreneurship()
    {
        return $this->hasOne(Entrepreneurship::class);
    }

    public function TVET()
    {
        return $this->hasOne(TVET::class);
    }
    
    public function mindset()
    {
        return $this->hasOne(MindsetChange::class);
    }

    public function monthly_report()
    {
        return $this->hasMany(MonthlyReport::class);
    }

    public function toolkit_verification()
    {
        return $this->hasMany(ToolkitVerification::class);
    }
    
    public function TOT()
    {
        return $this->hasOne(TOTApplication::class);
    }

    public function CYC()
    {
        return $this->hasOne(CYCApplication::class);
    }

    public function messageLog()
    {
        return $this->hasMany(ParticipantMessageLog::class);
    }
}