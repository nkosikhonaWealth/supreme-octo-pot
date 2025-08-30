<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantMessageLog extends Model
{
    protected $fillable = [
        'participant_id',
        'email',
        'result',
        'status',   
        'error_message', 
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
