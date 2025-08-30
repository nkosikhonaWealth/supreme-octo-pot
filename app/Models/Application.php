<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'mindset_change_id',
        'entrepreneurship_id',
        't_v_e_t_id',
        'status',
        'recommendation',
        'organisation',
        'shortlist',
        'comment',
    ];

    public function mindset()
    {
        return $this->belongsTo(MindsetChange::class);
    }

    public function entrepreneurship()
    {
        return $this->belongsTo(Entrepreneurship::class);
    }

    public function tvet()
    {
        return $this->belongsTo(TVET::class);
    }

}
