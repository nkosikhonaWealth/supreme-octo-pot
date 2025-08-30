<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class YouthMessageLog extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'youth_message_id',
        'email',
        'status',   
        'error_message', 
    ];

    public function youth_message()
    {
        return $this->belongsTo(YouthMessage::class);
    }
}
