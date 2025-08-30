<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Filament\Admin\Widgets\ApplicationStatsOverview;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\Participant;
use App\Models\TOTApplication;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TOTApplicationsDashboard extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static string $view = 'filament.admin.pages.t-o-t-applications-dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $title = 'TOT Applications Dashboard';
    protected static ?string $navigationLabel = 'TOT Applications Dashboard';
    protected static ?string $navigationGroup = 'TOT Applications Management';
    protected static ?int $navigationSort = 2;
    
    protected function getViewData(): array
    {
        $participantsByRegion = $this->getParticipantsByRegion();
        $regionCounts = $this->getRegionCounts();
        
        return [
            'participantsByRegion' => $participantsByRegion,
            'regionCounts' => $regionCounts,
            'stats' => [
                'four_regions' => $this->getCountByCondition(),
                'all_males' => $this->getCountByCondition('Male'),
                'all_females' => $this->getCountByCondition('Female'),
            ]
        ];
    }

    protected function getParticipantsByRegion()
    {
        return Participant::with(['TOT', 'user'])
            ->has('TOT')
            ->orderBy('region')
            ->orderBy('inkhundla')
            ->get()
            ->groupBy(['region', 'inkhundla']);
    }

    protected function getCountByCondition(?string $gender = null, ?string $region = null): int
    {
        return Participant::query()
            ->when($gender, fn($query) => $query->where('gender', $gender))
            ->when($region, fn($query) => $query->where('region', $region))
            ->whereHas('TOT')
            ->count();
    }

    public function getRegionCounts()
    {
        return Participant::has('TOT')
            ->selectRaw('region, count(*) as count')
            ->groupBy('region')
            ->orderBy('region')
            ->get()
            ->pluck('count', 'region')
            ->toArray();
    }

    public function getInkhundlaCounts()
    {
        return Participant::has('TOT')
            ->selectRaw('region, inkhundla, count(*) as count')
            ->groupBy('region', 'inkhundla')
            ->orderBy('region')
            ->orderBy('inkhundla')
            ->get()
            ->groupBy('region')
            ->toArray();
    }

    public function getFourRegions()
    {
        return Participant::has('TOT')->count();
    }
    public function getAllFemales()
    {
        return Participant::where('gender','Female')->has('TOT')->count();
    }
    public function getAllMales()
    {
        return Participant::where('gender','Male')->has('TOT')->count();
    }
    public function getMalesLubombo()
    {
        return Participant::where('gender','Male')->where('region','Lubombo')->has('TOT')->count();
    }

    public function getFemalesLubombo()
    {
        return Participant::where('gender','Female')->where('region','Lubombo')->has('TOT')->count();
    }

    public function getMalesHhohho()
    {
        return Participant::where('gender','Male')->where('region','Hhohho')->has('TOT')->count();
    }

    public function getFemalesHhohho()
    {
        return Participant::where('gender','Female')->where('region','Hhohho')->has('TOT')->count();
    }

    public function getMalesManzini()
    {
        return Participant::where('gender','Male')->where('region','Manzini')->has('TOT')->count();
    }

    public function getFemalesManzini()
    {
        return Participant::where('gender','Female')->where('region','Manzini')->has('TOT')->count();
    }

    public function getMalesShiselweni()
    {
        return Participant::where('gender','Male')->where('region','Shiselweni')->has('TOT')->count();
    }

    public function getFemalesShiselweni()
    {
        return Participant::where('gender','Female')->where('region','Shiselweni')->has('TOT')->count();
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Participant::query()
                    ->has('TOT')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('user.name')
                ->label('Name')
                ->searchable()
                ->sortable(),
                TextColumn::make('phone')
                ->label('Phone')
                ->searchable(),
                TextColumn::make('gender')
                ->label('Gender')
                ->searchable()
                ->sortable(),
                TextColumn::make('region')
                ->label('Region')
                ->searchable()
                ->sortable(),
                TextColumn::make('inkhundla')
                ->label('Inkhundla')
                ->searchable()
                ->sortable(),
                TextColumn::make('TOT.youth_organization_response')
                ->label('Youth Organization')
                ->searchable(),
                TextColumn::make('TOT.current_activity')
                ->label('Current Activity')
                ->searchable(),
                TextColumn::make('TOT.current_residence')
                ->label('Current Residence')
                ->searchable(),
                TextColumn::make('TOT.participant_result.status')
                ->label('Status')
                ->searchable(),
            ])
            ->filters([
                SelectFilter::make('gender')
                ->options([
                    'Female' => 'Female',
                    'Male' => 'Male'
                ])
                ->searchable(),
                SelectFilter::make('disability')
                ->options([
                    'Yes' => 'Yes',
                    'No' => 'No'
                ])
                ->searchable(),
                SelectFilter::make('region')
                ->options([
                    'Hhohho' => 'Hhohho',
                    'Lubombo' => 'Lubombo',
                    'Manzini' => 'Manzini',
                    'Shiselweni' => 'Shiselweni',
                ])
                ->searchable(),
                SelectFilter::make('current_activity')
                ->options(function () {
                    return \App\Models\TOTApplication::distinct('current_activity')
                        ->pluck('current_activity', 'current_activity')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // Join the TOTApplication table and filter based on current_activity
                        $query->whereHas('TOT', function (Builder $query) use ($data) {
                            $query->where('current_activity', $data['value']);
                        });
                    }
                }),
                SelectFilter::make('current_residence')
                ->options(function () {
                    return \App\Models\TOTApplication::distinct('current_residence')
                        ->pluck('current_residence', 'current_residence')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // Join the TOTApplication table and filter based on current_residence
                        $query->whereHas('TOT', function (Builder $query) use ($data) {
                            $query->where('current_residence', $data['value']);
                        });
                    }
                }),
                SelectFilter::make('youth_organization_response')
                ->options(function () {
                    return \App\Models\TOTApplication::distinct('youth_organization_response')
                        ->pluck('youth_organization_response', 'youth_organization_response')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // Join the TOTApplication table and filter based on youth_organization_response
                        $query->whereHas('TOT', function (Builder $query) use ($data) {
                            $query->where('youth_organization_response', $data['value']);
                        });
                    }
                }),
            ])
            ->deferFilters()
            ->actions([
            ])
            ->bulkActions([
                BulkAction::make('viewParticipants')
                ->label('View Participants')
                ->requiresConfirmation()
                ->action(function ($records) {
                    
                    $participantIds = $records->pluck('id')->toArray();

                    return redirect()->route('filament.admin.pages.t-o-t-applications', [
                        'participantIds' => implode(',', $participantIds)
                    ]);
                }),
                BulkAction::make('exportToCSV')
                ->label('Export Selected Records to CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function ($records) {
                    $filename = 'tot-applicants-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => "attachment; filename=\"$filename\"",
                    ];
                    
                    $callback = function() use ($records) {
                        $file = fopen('php://output', 'w');
                        
                        // Write CSV headers
                        fputcsv($file, [
                            'Name', 'ID #', 'Phone', 'Gender', 'Region', 'Inkhundla', 'Residential Address', 'Disability Name', "Youth Organization Experience", "Youth Organization Name"
                        ]);
                        
                        // Write data rows
                        foreach ($records as $record) {
                            fputcsv($file, [
                                $record->user->name ?? '',        
                                $record->identity_number ?? '',         
                                $record->phone ?? '',         
                                $record->gender ?? '',        
                                $record->region ?? '',        
                                $record->inkhundla ?? '',        
                                $record->residential_address ?? '',
                                $record->disability_name ?? '',
                                $record->TOT->youth_organization_response ?? '',
                                $record->TOT->youth_organization_name ?? '',
                            ]);
                        }
                        
                        fclose($file);
                    };
                    
                    return new StreamedResponse($callback, 200, $headers);
                }),
                BulkAction::make('mark_accepted')
                    ->label('Mark as Accepted')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $participantResult = $record->TOT->participant_result;
                            if (! $participantResult) {
                                $record->TOT->participant_result()->create(['status' => 'Accepted']);
                            } else {
                                $participantResult->update(['status' => 'Accepted']);
                            }
                        }
                    }),
                BulkAction::make('mark_awarded')
                    ->label('Mark as Awarded')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $participantResult = $record->TOT->participant_result;
                            if (! $participantResult) {
                                $record->TOT->participant_result()->create(['status' => 'Awarded']);
                            } else {
                                $participantResult->update(['status' => 'Awarded']);
                            }
                        }
                    })
                    ->requiresConfirmation(),
                BulkAction::make('mark_unsuccessful')
                    ->label('Mark as Unsuccessful')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $participantResult = $record->TOT->participant_result;
                            if (! $participantResult) {
                                $record->TOT->participant_result()->create(['status' => 'Unsuccessful']);
                            } else {
                                $participantResult->update(['status' => 'Unsuccessful']);
                            }
                        }
                    })
                    ->requiresConfirmation(),
        ])
        ->defaultPaginationPageOption(10);
    }
}
