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
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Page implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.admin.pages.dashboard';

    public function getHeading(): string
    {
        return 'ENYC TVET Support Programme - Admin Dashboard ';
    }

    protected function getViewData(): array
    {
        return [
            'four_regions' => $this->getFourRegions(),
            'hhohho_males' => $this->getMalesHhohho(),
            'hhohho_females' => $this->getFemalesHhohho(),
            'lubombo_males' => $this->getMalesLubombo(),
            'lubombo_females' => $this->getFemalesLubombo(),
            'manzini_males' => $this->getMalesManzini(),
            'manzini_females' => $this->getFemalesManzini(),
            'shiselweni_males' => $this->getMalesShiselweni(),
            'shiselweni_females' => $this->getFemalesShiselweni(),
            'carpentry_males' => $this->getCarpentryMales(),
            'carpentry_females' => $this->getCarpentryFemales(),
            'electrician_males' => $this->getElectricianMales(),
            'electrician_females' => $this->getElectricianFemales(),
            'sewing_males' => $this->getSewingMales(),
            'sewing_females' => $this->getSewingFemales(),
            'plumbing_males' => $this->getPlumbingMales(),
            'plumbing_females' => $this->getPlumbingFemales(),
            'motor_mechanic_males' => $this->getMotorMechanicMales(),
            'motor_mechanic_females' => $this->getMotorMechanicFemales(),
            'welding_males' => $this->getWeldingMales(),
            'welding_females' => $this->getWeldingFemales(),
            'all_males' => $this->getAllMales(),
            'all_females' => $this->getAllFemales(),
        ];
    }

    public function getFourRegions()
    {
        return Participant::has('TVET')->count();
    }
    public function getAllFemales()
    {
        return Participant::where('gender','Female')->has('TVET')->count();
    }
    public function getAllMales()
    {
        return Participant::where('gender','Male')->has('TVET')->count();
    }
    public function getMalesLubombo()
    {
        return Participant::where('gender','Male')->where('region','Lubombo')->has('TVET')->count();
    }

    public function getFemalesLubombo()
    {
        return Participant::where('gender','Female')->where('region','Lubombo')->has('TVET')->count();
    }

    public function getMalesHhohho()
    {
        return Participant::where('gender','Male')->where('region','Hhohho')->has('TVET')->count();
    }

    public function getFemalesHhohho()
    {
        return Participant::where('gender','Female')->where('region','Hhohho')->has('TVET')->count();
    }

    public function getMalesManzini()
    {
        return Participant::where('gender','Male')->where('region','Manzini')->has('TVET')->count();
    }

    public function getFemalesManzini()
    {
        return Participant::where('gender','Female')->where('region','Manzini')->has('TVET')->count();
    }

    public function getMalesShiselweni()
    {
        return Participant::where('gender','Male')->where('region','Shiselweni')->has('TVET')->count();
    }

    public function getFemalesShiselweni()
    {
        return Participant::where('gender','Female')->where('region','Shiselweni')->has('TVET')->count();
    }

    public function getCarpentryMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Carpentry'); 
                })
                ->count();
    }

    public function getCarpentryFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Carpentry'); 
                })
                ->count();
    }

    public function getElectricianMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Electrician'); 
                })
                ->count();
    }

    public function getElectricianFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Electrician'); 
                })
                ->count();
    }

    public function getSewingMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Sewing'); 
                })
                ->count();
    }

    public function getSewingFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Sewing'); 
                })
                ->count();
    }

    public function getPlumbingMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Plumbing'); 
                })
                ->count();
    }

    public function getPlumbingFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Plumbing'); 
                })
                ->count();
    }

    public function getMotorMechanicMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Motor Mechanic'); 
                })
                ->count();
    }

    public function getMotorMechanicFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Motor Mechanic'); 
                })
                ->count();
    }

    public function getWeldingMales()
    {
        return Participant::query()
                ->where('gender', 'Male') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Welding'); 
                })
                ->count();
    }

    public function getWeldingFemales()
    {
        return Participant::query()
                ->where('gender', 'Female') 
                ->whereHas('tvet', function ($query) { 
                    $query->where('vocational_skill', 'Welding'); 
                })
                ->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Participant::query()->has('TVET')->orderByDesc('created_at'))
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
                TextColumn::make('TVET.vocational_skill')
                ->label('Vocational Skill')
                ->searchable(),
                TextColumn::make('TVET.vocational_skill_obtained')
                ->label('Skill Obtained Through')
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
                SelectFilter::make('vocational_skill')
                ->options(function () {
                    return \App\Models\TVET::distinct('vocational_skill')
                        ->pluck('vocational_skill', 'vocational_skill')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // Join the TVET table and filter based on vocational_skill
                        $query->whereHas('TVET', function (Builder $query) use ($data) {
                            $query->where('vocational_skill', $data['value']);
                        });
                    }
                }),
                SelectFilter::make('vocational_skill_obtained')
                ->options(function () {
                    return \App\Models\TVET::distinct('vocational_skill_obtained')
                        ->pluck('vocational_skill_obtained', 'vocational_skill_obtained')
                        ->toArray();
                })
                ->searchable()
                ->preload()
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // Join the TVET table and filter based on vocational_skill
                        $query->whereHas('TVET', function (Builder $query) use ($data) {
                            $query->where('vocational_skill_obtained', $data['value']);
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

                    return redirect()->route('filament.admin.pages.participant-view', [
                        'participantIds' => implode(',', $participantIds)
                    ]);
                }),
                BulkAction::make('viewResults')
                ->label('View Results')
                ->requiresConfirmation()
                ->action(function ($records) {
                    
                    $participantIds = $records->pluck('id')->toArray();

                    return redirect()->route('filament.admin.pages.results-view', [
                        'participantIds' => implode(',', $participantIds)
                    ]);
                }),
                BulkAction::make('exportToCSV')
                ->label('Export Selected Records to CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->action(function ($records) {
                    $filename = 'participants-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
                    $headers = [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => "attachment; filename=\"$filename\"",
                    ];
                    
                    $callback = function() use ($records) {
                        $file = fopen('php://output', 'w');
                        
                        // Write CSV headers
                        fputcsv($file, [
                            'Name', 'ID #', 'Phone', 'Gender', 'Region', 'Inkhundla', 'Residential Address', 'Disability Name', 'Vocational Skill', 'Skill Obtained Through'
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
                                $record->tvet->vocational_skill ?? '',       
                                $record->tvet->vocational_skill_obtained ?? '',
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
