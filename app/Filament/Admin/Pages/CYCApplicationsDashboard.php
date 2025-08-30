<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Participant;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CYCApplicationsDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.admin.pages.c-y-c-applications-dashboard';
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $title = 'CYC Applications Dashboard';
    protected static ?string $navigationLabel = 'CYC Applications Dashboard';
    protected static ?string $navigationGroup = 'CYC Applications Management';
    protected static ?int $navigationSort = 3;

    protected function getViewData(): array
    {
        $participantsByRegion = $this->getParticipantsByRegion();
        $regionCounts = $this->getRegionCounts();
        
        return [
            'ParticipantsByRegion' => $participantsByRegion,
            'regionCounts' => $regionCounts,
            'Stats' => [
                'four_regions' => $this->getCountByCondition(),
                'all_males' => $this->getCountByCondition('Male'),
                'all_females' => $this->getCountByCondition('Female'),
            ]
        ];
    }

    protected function getParticipantsByRegion()
    {
        return Participant::with(['CYC', 'user'])
            ->has('CYC')
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
            ->whereHas('CYC')
            ->count();
    }

    public function getRegionCounts()
    {
        return Participant::has('CYC')
            ->selectRaw('region, count(*) as count')
            ->groupBy('region')
            ->orderBy('region')
            ->get()
            ->pluck('count', 'region')
            ->toArray();
    }

    public function getInkhundlaCounts()
    {
        return Participant::has('CYC')
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
        return Participant::where('gender','Female')->has('CYC')->count();
    }
    public function getAllMales()
    {
        return Participant::where('gender','Male')->has('CYC')->count();
    }
    public function getMalesLubombo()
    {
        return Participant::where('gender','Male')->where('region','Lubombo')->has('CYC')->count();
    }

    public function getFemalesLubombo()
    {
        return Participant::where('gender','Female')->where('region','Lubombo')->has('CYC')->count();
    }

    public function getMalesHhohho()
    {
        return Participant::where('gender','Male')->where('region','Hhohho')->has('CYC')->count();
    }

    public function getFemalesHhohho()
    {
        return Participant::where('gender','Female')->where('region','Hhohho')->has('CYC')->count();
    }

    public function getMalesManzini()
    {
        return Participant::where('gender','Male')->where('region','Manzini')->has('CYC')->count();
    }

    public function getFemalesManzini()
    {
        return Participant::where('gender','Female')->where('region','Manzini')->has('CYC')->count();
    }

    public function getMalesShiselweni()
    {
        return Participant::where('gender','Male')->where('region','Shiselweni')->has('CYC')->count();
    }

    public function getFemalesShiselweni()
    {
        return Participant::where('gender','Female')->where('region','Shiselweni')->has('CYC')->count();
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Participant::query()
                    ->has('CYC')
                    ->with('user', 'CYC')
                    ->orderByDesc('created_at')
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('region')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('CYC.sdg_response')
                    ->label('SDG Response')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('CYC.challenge_response')
                    ->label('Challenge Response')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('CYC.leadership_experience')
                    ->label('Leadership Experience')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                BooleanColumn::make('CYC.representation_experience')
                    ->label('Has Represented Youth')
                    ->toggleable(),
                TextColumn::make('CYC.representation_details')
                    ->label('Representation Details')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
                TextColumn::make('CYC.motivation')
                    ->label('Motivation')
                    ->limit(50)
                    ->wrap()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->label('Region')
                    ->options([
                        'Hhohho' => 'Hhohho',
                        'Lubombo' => 'Lubombo',
                        'Manzini' => 'Manzini',
                        'Shiselweni' => 'Shiselweni',
                    ])
                    ->searchable(),
                SelectFilter::make('CYC.representation_experience')
                    ->label('Has Represented Youth')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ]),
            ])
            ->bulkActions([
                BulkAction::make('viewParticipants')
                ->label('View Participants')
                ->requiresConfirmation()
                ->action(function ($records) {
                    
                    $participantIds = $records->pluck('id')->toArray();

                    return redirect()->route('filament.admin.pages.c-y-c-applications', [
                        'participantIds' => implode(',', $participantIds)
                    ]);
                }),
                BulkAction::make('exportToCSV')
                    ->label('Export Selected Records to CSV')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($records) {
                        $filename = 'cyc-applicants-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => "attachment; filename=\"$filename\"",
                        ];

                        $callback = function () use ($records) {
                            $file = fopen('php://output', 'w');

                            // CSV headers
                            fputcsv($file, [
                                'Name',
                                'Gender',
                                'Phone',
                                'Region',
                                'Inkhundla'
                            ]);

                            foreach ($records as $record) {
                                fputcsv($file, [
                                    $record->user->name ?? '',
                                    $record->gender ?? '',
                                    $record->phone ?? '',
                                    $record->region ?? '',
                                    $record->inkhundla ?? '',
                                ]);
                            }

                            fclose($file);
                        };

                        return new StreamedResponse($callback, 200, $headers);
                    }),
            ])
            ->defaultPaginationPageOption(10);
    }


}
