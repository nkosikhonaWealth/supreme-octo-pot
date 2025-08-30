<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestEmailLog extends Model
{
    protected $fillable = 
    ['email', 
    'name',
    'status',
    'error'
    ];
}
