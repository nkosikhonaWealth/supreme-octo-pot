<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YouthAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'region_id',
        'venue',
        'activity_type',
        'topics_covered',
        'activity_date',
        'start_time',
        'finish_time',
        'data_collector',
        'collection_date',
        'verified_by',
        'verification_date',
        'user_id',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'collection_date' => 'date',
        'verification_date' => 'date',
        'topics_covered' => 'array',
    ];
    
    public function youth_attendance_entries()
    {
        return $this->hasMany(YouthAttendanceEntry::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
