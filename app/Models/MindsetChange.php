<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MindsetChange extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'education',
        'employment',
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
