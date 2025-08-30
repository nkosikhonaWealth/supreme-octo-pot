<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsultationRelationManager extends RelationManager
{
    protected static string $relationship = 'consultation';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->options(function (RelationManager $livewire): array {
                        return $livewire->getOwnerRecord()->user()
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->required(),
                Select::make('consultation_slot_id')
                ->options(function (RelationManager $livewire): array {
                    return $livewire->getOwnerRecord()->consultation_slot()
                        ->pluck('date', 'id')
                        ->toArray();
                })
                ->required(),
                Textarea::make('consultation_notes')
                    ->rows(5)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('consultation_notes')
            ->columns([
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('booking.consultation_slot.date')->label('Booking Date')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('consultation_notes')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
