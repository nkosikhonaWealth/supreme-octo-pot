<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YouthMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'gender',
        'phone',
        'residential_address',
        'inkhundla',
        'vocational_skill',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function youth_message_log()
    {
        return $this->hasMany(YouthMessageLog::class);
    }
}
