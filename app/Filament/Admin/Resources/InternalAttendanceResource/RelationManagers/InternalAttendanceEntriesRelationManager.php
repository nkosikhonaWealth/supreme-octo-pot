<?php

namespace App\Filament\Admin\Resources\InternalAttendanceResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class InternalAttendanceEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'internal_attendance_entries';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('institution')->required(),
                Forms\Components\TextInput::make('designation')->required(),
                Forms\Components\TextInput::make('contact')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('institution')->sortable(),
                Tables\Columns\TextColumn::make('designation')->sortable(),
                Tables\Columns\TextColumn::make('contact'),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
