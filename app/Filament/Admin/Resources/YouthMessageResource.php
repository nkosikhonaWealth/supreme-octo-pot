<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\YouthMessageResource\Pages;
use App\Filament\Admin\Resources\YouthMessageResource\RelationManagers;
use App\Models\YouthMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class YouthMessageResource extends Resource
{
    protected static ?string $model = YouthMessage::class;

    protected static ?string $navigationGroup = 'Youth Messaging';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis'; // optional
    protected static ?string $navigationLabel = 'Youth Messages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('message')
                    ->label('Message')
                    ->required(),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Scheduled At'),
                // Add other fields as needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('message')->limit(50),
                Tables\Columns\TextColumn::make('scheduled_at')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                // Optional: Add filters
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListYouthMessages::route('/'),
            'create' => Pages\CreateYouthMessage::route('/create'),
            'edit' => Pages\EditYouthMessage::route('/{record}/edit'),
        ];
    }
}
