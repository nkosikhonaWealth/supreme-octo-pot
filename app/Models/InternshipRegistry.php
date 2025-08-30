<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipRegistry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'full_name',
        'gender',
        'age',
        'id_number',
        'field_of_study',
        'certificate',
        'post_internship_employment_status',
        'learning_outcomes_achieved',
        'exit_interview_notes',
    ];

    protected $casts = [
        'age' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope for filtering by region
    public function scopeForRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    // Scope for employment status statistics
    public function scopeEmployed($query)
    {
        return $query->where('post_internship_employment_status', 'Employed');
    }

    public function scopeJobSeeking($query)
    {
        return $query->where('post_internship_employment_status', 'Job Seeking');
    }

    public function scopeFurtherStudying($query)
    {
        return $query->where('post_internship_employment_status', 'Further Studying');
    }
}
