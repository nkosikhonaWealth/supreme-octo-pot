<?php

namespace App\Filament\Admin\Pages;

use App\Models\MonthlyReport;
use App\Models\Participant;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Carbon;
use App\Filament\Admin\Widgets\MonthlyReportsStats;
use App\Filament\Admin\Widgets\ReportSubmissionBarChart;
use Livewire\WithPagination;
use Livewire\Component;

class AdminMonthlyReportsDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $title = 'Monthly Reports Dashboard';
    protected static ?string $navigationLabel = 'Monthly Reports Dashboard';
    protected static ?string $navigationGroup = 'Beneficiary Management';
    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.admin.pages.admin-monthly-reports-dashboard';

    public ?string $selectedMonth;

    public function mount(): void
    {
        $this->selectedMonth = now()->format('Y-m'); // Default to current month
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(MonthlyReport::query()->with('participant.user'))
            ->columns([
                TextColumn::make('participant.user.name')->label('Participant'),
                TextColumn::make('report_month')->date('M Y')->label('Month'),
                BadgeColumn::make('admin_verified')
                    ->label('Verification Status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            true => 'Verified',
                            false => 'Not Verified',
                            default => 'Pending',
                        };
                    })
                    ->colors([
                        'secondary' => fn ($state): bool => $state === null,
                        'success' => fn ($state): bool => $state === true,
                        'danger' => fn ($state): bool => $state === false,
                    ]),
            ]);
    }

    public function updatedSelectedMonth()
    {
        $this->dispatch('updateMonthlyReportStats', filterMonth: $this->selectedMonth);
    }

    protected function getViewData(): array
    {
        return [
            'selectedMonth' => $this->selectedMonth,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            MonthlyReportsStats::class,
            ReportSubmissionBarChart::class,
        ];
    }
}
