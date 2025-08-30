<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'participant_id',
        'report_month',
        'employment_status',
        'toolkit_usage_status',
        'proof_of_work',
        'income_improvement_status',
        'income_generated',
        'estimated_expenses',
        'amount_saved',
        'self_reliance_confidence',
        'additional_support_needs',
        'admin_verified',
        'people_hired_seasonal',
        'people_hired_temporal',
        'people_hired_full_time',
        'received_financial_assistance',
        'assistance_type',

    ];

    protected $casts = [
        'people_hired_seasonal' => 'integer',
        'people_hired_temporal' => 'integer',
        'people_hired_full_time' => 'integer',
        'received_financial_assistance' => 'string',
        'assistance_type' => 'string',
        'report_month' => 'date',
        'proof_of_work' => 'array'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function getProofOfWorkUrlsAttribute(): array
    {
        return collect($this->proof_of_work ?? [])
            ->map(function ($filename) {
                return route('proof.work', ['filename' => basename($filename)]);
            })->toArray();
    }
    /**
     * Get the total number of people hired across all employment types
     */
    public function getTotalPeopleHiredAttribute(): int
    {
        return $this->people_hired_seasonal + 
               $this->people_hired_temporal + 
               $this->people_hired_full_time;
    }

    /**
     * Check if financial assistance was received
     */
    public function hasReceivedFinancialAssistance(): bool
    {
        return $this->received_financial_assistance === 'yes';
    }

    /**
     * Get employment statistics
     */
    public function getEmploymentStats(): array
    {
        return [
            'seasonal' => $this->people_hired_seasonal,
            'temporal' => $this->people_hired_temporal,
            'full_time' => $this->people_hired_full_time,
            'total' => $this->total_people_hired,
        ];
    }

    /**
     * Scope for reports that received financial assistance
     */
    public function scopeWithFinancialAssistance($query)
    {
        return $query->where('received_financial_assistance', 'yes');
    }

    /**
     * Scope for reports by assistance type
     */
    public function scopeByAssistanceType($query, $type)
    {
        return $query->where('assistance_type', $type);
    }
}
