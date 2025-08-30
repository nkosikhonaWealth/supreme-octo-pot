<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Filament\Resources\BookingResource\RelationManagers\ConsultationRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\PaymentRelationManager;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Name')
                    ->relationship('user','name')
                    ->searchable()
                    ->required(),
                Select::make('consultation_slot_id')
                    ->label('Date')
                    ->relationship('consultation_slot','date')
                    ->searchable()
                    ->required(),
                Select::make('booking_status')
                    ->label('Booking Status')
                    ->options(['Confirmed' => 'Confirmed','Not Confirmed' => 'Not Confirmed'])
                    ->searchable()
                    ->required(),
                Select::make('payment_status')
                    ->label('Payment Status')
                    ->options(['Paid' => 'Paid','Not Paid' => 'Not Paid'])
                    ->searchable()
                    ->required(),
                TextInput::make('booking_fee')
                ->numeric()
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->sortable()->searchable(),
                TextColumn::make('consultation_slot.date')->label('Booking Date'),
                TextColumn::make('booking_status')->sortable()->searchable(),
                TextColumn::make('payment_status')->sortable()->searchable(),
                TextColumn::make('booking_fee')->sortable()->searchable(),
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
            PaymentRelationManager::class,
            ConsultationRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
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
