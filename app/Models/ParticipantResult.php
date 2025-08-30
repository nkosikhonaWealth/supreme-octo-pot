<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipantResult extends Model
{
    use SoftDeletes;

    protected $fillable = [
        't_v_e_t_id',
        't_o_t_application_id',
        'average_score',
        'status',
        'status_comment',
    ];

    public function TVET()
    {
        return $this->belongsTo(TVET::class);
    }

    public function TOTApplication()
    {
        return $this->belongsTo(TOTApplication::class);
    }

}
