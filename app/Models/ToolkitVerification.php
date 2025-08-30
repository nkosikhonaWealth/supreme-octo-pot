<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToolkitVerification extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'participant_id',
        'toolkit_received',
        'date_toolkit_received',
        'date_of_visit',
        'date_of_next_visit',
        'number_of_people_met',
        'is_toolkit_used',
        'is_toolkit_used_comment',
        'condition_of_tools',
        'condition_of_tools_comment',
        'recipient_providing_services',
        'recipient_providing_services_comment',
        'visible_income_activity',
        'visible_income_activity_comment',
        'short_interview',
        'short_interview_comment',
        'toolkit_usage_frequency',
        'making_income',
        'approximate_income_per_month',
        'summary_of_activities',
        'field_lessons',
        'prepared_by',
        'prepared_on',
        'site_representative',
        'site_signed_on',
        'received_other_support',
        'support_entity_details',
        'affiliated_with_dev_groups',
        'dev_group_details',
        'future_plans_12_months',
        'last_support_check_date',
    ];
    
    protected $casts = [
        'toolkit_received' => 'boolean',
        'is_toolkit_used' => 'boolean',
        'condition_of_tools' => 'boolean',
        'recipient_providing_services' => 'boolean',
        'visible_income_activity' => 'boolean',
        'making_income' => 'boolean',
        'date_toolkit_received' => 'date',
        'date_of_visit' => 'date',
        'date_of_next_visit' => 'date',
        'prepared_on' => 'date',
        'site_signed_on' => 'date',
        'last_support_check_date' => 'datetime',
        'received_other_support' => 'boolean',
        'affiliated_with_dev_groups' => 'boolean',
    ];
    
    public function youth()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Regional scope for access control
    public function scopeForRegion($query, $region)
    {
        return $query->whereHas('youth', function ($q) use ($region) {
            $q->where('region', $region);
        });
    }

    // Helper methods for business support tracking
    public function hasSupportDetails(): bool
    {
        return $this->received_other_support && !empty($this->support_entity_details);
    }

    public function hasDevGroupAffiliation(): bool
    {
        return $this->affiliated_with_dev_groups && !empty($this->dev_group_details);
    }

    // Common support entities for validation/suggestions
    public static function getCommonSupportEntities(): array
    {
        return [
            'YERF' => 'Youth Enterprise Revolving Fund',
            'CFI' => 'Center For Financial Inclusion',
            'ESNAU' => 'Eswatini National Agricultural Union',
            'UNDP' => 'United Nations Development Programme',
        ];
    }
}
