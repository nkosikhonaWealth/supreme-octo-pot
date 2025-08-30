<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'designation',
        'occupation',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
