<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalAttendanceEntry extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'internal_attendance_id',
        'name',
        'institution',
        'designation',
        'contact',
        'email',
    ];

    public function internal_attendance()
    {
        return $this->belongsTo(InternalAttendance::class);
    }
}
