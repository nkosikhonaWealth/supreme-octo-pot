<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YouthAttendanceEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'youth_attendance_id',
        'name',
        'age',
        'gender',
        'youth_region',
        'education_level',
        'institution',
        'is_employed',
        'employment_type',
        'contact',
        'email',
    ];

    protected $casts = [
        'age' => 'integer',
        'is_employed' => 'boolean', // This will handle the tinyint(1) conversion
    ];

    // Helper methods
    public function isEmployed(): bool
    {
        return (bool) $this->is_employed;
    }

    public function getEmploymentStatusTextAttribute(): string
    {
        return $this->is_employed ? 'Yes' : 'No';
    }

    public function getFullEmploymentStatusAttribute(): string
    {
        if (!$this->is_employed) {
            return 'Unemployed';
        }
        
        return $this->employment_type ? "Employed ({$this->employment_type})" : 'Employed';
    }

    public function attendance()
    {
        return $this->belongsTo(YouthAttendance::class, 'youth_attendance_id');
    }
    
}
