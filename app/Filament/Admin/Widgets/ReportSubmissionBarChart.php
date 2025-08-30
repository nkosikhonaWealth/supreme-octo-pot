<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Participant;
use App\Models\MonthlyReport;
use Filament\Widgets\BarChartWidget;
use Livewire\WithPagination;
use Livewire\Component;

class ReportSubmissionBarChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Report Submissions';

    protected int|string|array $columnSpan = 12;

    public ?string $selectedMonth;

    protected function getData(): array
    {
        $month = $this->selectedMonth ?? now()->format('Y-m');

        $total = Participant::query()->whereHas('TVET.participant_result', function ($query) {
            $query->where('status', 'Awarded');
        })->count();
        $submitted = MonthlyReport::where('report_month', 'like', "$month%")
            ->distinct('participant_id')->count('participant_id');
        $notSubmitted = $total - $submitted;

        return [
            'datasets' => [
                [
                    'label' => 'Participants',
                    'data' => [$total, $submitted, $notSubmitted],
                    'backgroundColor' => ['#0000ff', '#00ff00', '#ff0000'],
                ],
            ],
            'labels' => ['Total Beneficiaries', 'Submitted Reports', 'Unsubmitted Reports'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
