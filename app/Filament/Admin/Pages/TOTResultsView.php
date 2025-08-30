<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\Participant;
use App\Models\TOTApplication;
use App\Models\ParticipantResult;

class TotResultsView extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static string $view = 'filament.admin.pages.t-o-t-results-view';
    protected static ?string $navigationGroup = 'Results';
    protected static ?string $navigationLabel = 'TOT Results View';
    protected static ?int $navigationSort = 3;

    public function getHeading(): string
    {
        return 'ENYC TOT Programme - Results Overview';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Participant::query()->whereHas('TOT')
            )
            ->columns([
                TextColumn::make('user.name')->label('Name')->sortable()->searchable(),
                TextColumn::make('gender')->label('Gender'),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('region')->label('Region'),
                TextColumn::make('inkhundla')->label('Inkhundla'),
                TextInputColumn::make('TOT.participant_result.average_score')
                    ->label('Average Score')
                    ->rules(['numeric'])
                    ->updateStateUsing(function ($state, $record) {
                        $participantResult = $record->TOT->participant_result;

                        if (! $participantResult) {
                            $record->TOT->participant_result()->create(['average_score' => $state]);
                        } else {
                            $participantResult->update(['average_score' => $state]);
                        }
                    }),
                TextInputColumn::make('TOT.participant_result.status_comment')
                    ->label('Comment')
                    ->updateStateUsing(function ($state, $record) {
                        $participantResult = $record->TOT->participant_result;

                        if (! $participantResult) {
                            $record->TOT->participant_result()->create(['status_comment' => $state]);
                        } else {
                            $participantResult->update(['status_comment' => $state]);
                        }
                    }),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->options(Participant::distinct('region')->pluck('region', 'region')->toArray())
                    ->placeholder('All Regions'),
                SelectFilter::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->placeholder('All Genders'),
            ])
            ->bulkActions([
                BulkAction::make('mark_awarded')
                    ->label('Mark as Awarded')
                    ->action(function (Collection $records) {
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
                    ->action(function (Collection $records) {
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
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25);
    }
}
