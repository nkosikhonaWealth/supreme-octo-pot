<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'call_notes',
        'call_outcome',
        'next_call_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
