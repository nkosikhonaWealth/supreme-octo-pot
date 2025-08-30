<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

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
                    DatePicker::make('payment_date')
                        ->required(),
                Select::make('payment_name')
                    ->options(['Full Payment' => 'Full Payment','Deposit' => 'Deposit',
                    'Part Payment' => 'Part Payment'])
                    ->required(),
                Select::make('payment_method')
                    ->options(['EFT' => 'EFT','Card' => 'Card','Cash' => 'Cash',
                    'Mobile Money' => 'Mobile Money','E-Mali' => 'E-Mali','E-Wallet' => 'E-Wallet',
                    'Unayo' => 'Unayo','SBS ePocket' => 'SBS ePocket','Nedbank Wallet' => 'Nedbank Wallet',
                    'Eswatini Bank Wallet' => 'Eswatini Bank Wallet','InstaCash' => 'InstaCash'])
                    ->required(),
                TextInput::make('payment_amount')
                    ->numeric()
                    ->required(),
                FileUpload::make('payment_upload'),
                Select::make('payment_verification')
                ->options(['Verified' => 'Verified','Not Verified' => 'Not Verified']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('payment_amount')
            ->columns([
                TextColumn::make('payment_date')->sortable()->searchable(),
                TextColumn::make('payment_name')->sortable()->searchable(),
                TextColumn::make('payment_method')->sortable()->searchable(),
                TextColumn::make('payment_amount')->sortable()->searchable(),
                ImageColumn::make('payment_upload')->sortable()->searchable(),
                TextColumn::make('payment_verification')->sortable()->searchable(),
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
