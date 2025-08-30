<?php

namespace App\Filament\User\Widgets;

use App\Models\MonthlyReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class MonthlyReportStatus extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;
    protected int|string|array $columnSpan = 12;

    public function table(Table $table): Table
    {
        $participant = Auth::user()->participant;

        return $table
            ->query(
                MonthlyReport::query()
                    ->when($participant, fn ($query) => $query->where('participant_id', $participant->id))
            )
            ->columns([
                Tables\Columns\TextColumn::make('report_month')
                    ->label('Month')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('income_generated')
                    ->label('Income (SZL)')
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),

                Tables\Columns\IconColumn::make('admin_verified')
                    ->label('Verified')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                
            ]);
    }

    public static function canView(): bool
    {
        return Auth::check() && Auth::user()->participant !== null;
    }
}
