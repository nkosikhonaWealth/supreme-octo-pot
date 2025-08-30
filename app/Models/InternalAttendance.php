<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalAttendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'region',
        'venue',
        'activity_date',
        'start_time',
        'finish_time',
        'data_collector',
        'collection_date',
        'verified_by',
        'verification_date',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'collection_date' => 'date',
        'verification_date' => 'date',
    ];

    public function internal_attendance_entries()
    {
        return $this->hasMany(InternalAttendanceEntry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
