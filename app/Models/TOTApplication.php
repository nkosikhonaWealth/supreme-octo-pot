<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TOTApplication extends Model
{
    protected $fillable = [
        'participant_id',
        'certificate_upload',
        'cv_upload',
        'current_activity',
        'youth_organization_response',
        'youth_organization_name',
        'youth_organization_duties',
        'current_residence',
        'motivation',
    ];
    
    protected $casts = [
        'certificate_upload' => 'array',
        'cv_upload' => 'array',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function participant_result()
    {
        return $this->hasOne(ParticipantResult::class, 't_o_t_application_id');
    }
}
