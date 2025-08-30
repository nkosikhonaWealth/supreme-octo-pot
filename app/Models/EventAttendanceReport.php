<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendanceReport extends Model
{
    protected $fillable = [
        'user_id',
        'stakeholder_level',
        'region',
        'location',
        'event_type',
        'engagement_date',
        'report_date',
        'programme_area',
        'purpose',
        'summary',
        'key_themes',
        'key_stakeholders',
        'opportunities',
        'action_items',
        'lessons',
        'supporting_materials',
    ];

    protected $casts = [
        'action_items' => 'array',
        'supporting_materials' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
