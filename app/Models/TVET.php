<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TVET extends Model
{
    protected $fillable = [
        'participant_id',
        'vocational_skill',
        'vocational_skill_obtained',
        'certificate_upload',
        'finance_upload',
        'current_activity',
        'duration',
        'toolkit_use',
        'youth_organization_response',
        'youth_organization_name',
        'recent_assistance',
        'motivation',
        'account',
        'account_number',
    ];

    protected $casts = [
        'certificate_upload' => 'array',
        'finance_upload' => 'array',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function application()
    {
        return $this->hasOne(Application::class);
    }

    public function participant_result()
    {
        return $this->hasOne(ParticipantResult::class);
    }
}
