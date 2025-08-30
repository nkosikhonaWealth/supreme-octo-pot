<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CYCApplication extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'participant_id',
        'sdg_response',
        'challenge_response',
        'representation_experience',
        'representation_details',
        'leadership_experience',
        'motivation',
        'cv_upload',
        'supporting_documents',
    ];

    protected $casts = [
        'cv_upload' => 'array',
        'supporting_documents' => 'array',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
