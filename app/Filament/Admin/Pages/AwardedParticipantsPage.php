<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\Participant;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextInputColumn\TextInputColumnState;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;

class AwardedParticipantsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-trophy'; 
    protected static string $view = 'filament.admin.pages.awarded-participants-page'; 
    protected static ?string $navigationGroup = 'Results';

    protected static ?int $navigationSort = 2;

    public array $participantIds = [];
    public array $participants_array = [];
    public $participant;
    public $participants;

    public function getHeading(): string
    {
        return 'ENYC TVET Support Programme - Awarded Participants';
    }

    public function mount()
    {
        $participantIdsString = request()->query('participantIds');

        // Base query to only include participants with an 'Awarded' status
        $baseQuery = Participant::query()->whereHas('TVET.participant_result', function ($query) {
            $query->where('status', 'Awarded');
        });

        if ($participantIdsString) {
            $this->participantIds = explode(',', $participantIdsString);
            // Filter further by specific participant IDs if provided
            $this->participants = $baseQuery->whereIn('id', $this->participantIds)->get();
        } else {
            // Otherwise, get all awarded participants
            $this->participants = $baseQuery->get();
            // Update participants_array to reflect only awarded participants
            $this->participants_array = $this->participants->pluck('id')->toArray();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () use ($table) {
                $query = Participant::query()
                    // Ensure only awarded participants are queried for the table
                    ->whereHas('TVET.participant_result', function (Builder $query) {
                        $query->where('status', 'Awarded');
                    })
                    ->when($this->participantIds, function ($query) {
                        $query->whereIn('id', $this->participantIds);
                    });

                // Get the current sort column and direction from the table instance
                $sortColumn = $table->getLivewire()->getTableSortColumn();
                $sortDirection = $table->getLivewire()->getTableSortDirection();

                // Custom sorting for 'TVET.participant_result.average_score'
                if ($sortColumn === 'TVET.participant_result.average_score') {
                    $query->leftJoin('tvet_participants', 'participants.id', '=', 'tvet_participants.participant_id')
                          ->leftJoin('participant_results', 'tvet_participants.id', '=', 'participant_results.tvet_participant_id')
                          ->orderBy('participant_results.average_score', $sortDirection)
                          ->select('participants.*'); // Select original columns to avoid conflicts
                } else {
                    // Default sorting if not sorting by average_score
                    $query->orderBy('created_at', 'desc');
                }

                return $query;
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->wrap()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('TVET.vocational_skill')
                    ->wrap()
                    ->label('Vocational Skill'),
                TextColumn::make('gender')
                    ->label('Gender'),
                TextColumn::make('phone')
                    ->label('Phone'),
                TextColumn::make('region')
                    ->label('Region'),
                TextColumn::make('inkhundla')
                    ->label('inkhundla'),
                TextColumn::make('residential_address')
                    ->label('Residential Address')
                    ->wrap(),
                TextColumn::make('TVET.participant_result.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Awarded' => 'success',
                        default => 'gray', 
                    }),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->options(
                        Participant::has('TVET.participant_result')
                            ->whereHas('TVET.participant_result', fn ($q) => $q->where('status', 'Awarded'))
                            ->distinct('region')
                            ->pluck('region')
                            ->mapWithKeys(fn ($value) => [$value => $value])
                            ->toArray()
                    )
                    ->placeholder('All Regions')
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('region', $data['value']);
                        }
                    }),
                SelectFilter::make('vocational_skill')
                    ->options(
                        Participant::with('TVET')
                            ->has('TVET.participant_result')
                            ->whereHas('TVET.participant_result', fn ($q) => $q->where('status', 'Awarded'))
                            ->get()
                            ->pluck('TVET.vocational_skill')
                            ->unique()
                            ->filter()
                            ->mapWithKeys(fn ($value) => [$value => $value])
                            ->toArray()
                    )
                    ->placeholder('All Skills')
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('TVET', function ($q) use ($data) {
                                $q->where('vocational_skill', $data['value']);
                            });
                        }
                    }),
                SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ])
                    ->placeholder('All Genders'),
            ])->deferFilters()
            ->bulkActions([
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25);
    }

    protected function getViewData(): array
    {
        // Load only awarded participants for the overview data as well
        $participants = $this->participants->load(['TVET.participant_result']);

        $allRegions = ['Hhohho', 'Lubombo', 'Manzini', 'Shiselweni'];
        
        $regions = collect($allRegions)
            ->mapWithKeys(fn ($region) => [
                $region => $this->getRegionData($participants, $region)
            ])
            ->filter(fn ($data) => $data['total'] > 0)
            ->toArray();

        return [
            'regions' => $regions,
            'skills' => $this->getSkillData($participants)
        ];
    }

    private function getRegionData($participants, $region)
    {
        $filtered = $participants->filter(function ($participant) use ($region) {
            return $participant->region === $region && 
                   optional($participant->TVET)->participant_result !== null &&
                   optional($participant->TVET->participant_result)->status === 'Awarded'; // Ensure awarded status
        });

        return [
            'total' => $filtered->count(),
            'male' => $filtered->where('gender', 'Male')->count(),
            'female' => $filtered->where('gender', 'Female')->count()
        ];
    }

    private function getSkillData($participants)
    {
        return $participants->filter(fn($p) => optional($p->TVET)->participant_result !== null &&
                                                optional($p->TVET->participant_result)->status === 'Awarded') // Ensure awarded status
            ->groupBy(fn($p) => optional($p->TVET)->vocational_skill ?? 'Unknown')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'male' => $group->where('gender', 'Male')->count(),
                    'female' => $group->where('gender', 'Female')->count()
                ];
            });
    }
}
