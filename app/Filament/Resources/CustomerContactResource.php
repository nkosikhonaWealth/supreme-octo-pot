<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerContactResource\Pages;
use App\Filament\Resources\CustomerContactResource\RelationManagers;
use App\Models\CustomerContact;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerContactResource extends Resource
{
    protected static ?string $model = CustomerContact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Name')
                    ->relationship('user','name')
                    ->required(),
                DateTimePicker::make('next_call_date'),
                Textarea::make('call_notes')
                    ->rows(3)
                    ->required(),
                Textarea::make('call_outcome')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('next_call_date')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('call_notes')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                TextColumn::make('call_outcome')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerContacts::route('/'),
            'create' => Pages\CreateCustomerContact::route('/create'),
            'edit' => Pages\EditCustomerContact::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
