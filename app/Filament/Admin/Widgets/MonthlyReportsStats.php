<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\MonthlyReport;
use App\Models\Participant;
use Livewire\WithPagination;
use Livewire\Component;
use Livewire\Attributes\On; 

class MonthlyReportsStats extends BaseWidget
{
    public ?string $selectedMonth;

    #[On('updateMonthlyReportStats')] 
    public function statTest($filterMonth)
    {
        $selectedMonth = $filterMonth;
        $refresh;
    }

    protected function getStats(): array
    {
        $month = $this->selectedMonth ?? now()->format('Y-m');

        $totalParticipants = Participant::query()->whereHas('TVET.participant_result', function ($query) {
            $query->where('status', 'Awarded');
        })->count();
        $reportsSubmitted = MonthlyReport::where('report_month', 'like', "$month%")
            ->distinct('participant_id')->count('participant_id');
        $reportsPending = $totalParticipants - $reportsSubmitted;
        $submittedpercentage = number_format(($reportsSubmitted/$totalParticipants)*100,0);
        $pendingpercentage = number_format(($reportsPending/$totalParticipants)*100,0);

        return [
            Stat::make('Total Participants', $totalParticipants.'-'.$month),
            Stat::make('Reports Submitted', $reportsSubmitted.' ('.$submittedpercentage.' %)'),
            Stat::make('Reports Submitted', $reportsPending.' ('.$pendingpercentage.' %)'),
        ];
    }
}
