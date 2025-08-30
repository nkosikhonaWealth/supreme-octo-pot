<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternalAttendanceResource\Pages;
use App\Filament\Admin\Resources\InternalAttendanceResource\RelationManagers;
use App\Models\InternalAttendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Exports\InternalAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;


class InternalAttendanceResource extends Resource
{
    protected static ?string $model = InternalAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Internal Activity Stakeholder Register';
    protected static ?string $navigationGroup = 'Activity Register Management';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query = parent::getEloquentQuery();

        if ($user->hasRole('regional_programs_support_officer')) {
            $query->where('region', $user->region->name);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\Select::make('region')
                            ->options([
                                'Hhohho' => 'Hhohho',
                                'Lubombo' => 'Lubombo',
                                'Manzini' => 'Manzini',
                                'Shiselweni' => 'Shiselweni',
                            ])                   
                            ->default(fn () => auth()->user()->region->name ?? null)
                            ->disabled(fn () => auth()->user()->hasRole('regional_programs_support_officer'))
                            ->required(),
                        Forms\Components\TextInput::make('venue')
                            ->required(),
                        Forms\Components\DatePicker::make('activity_date')
                            ->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TimePicker::make('finish_time')
                            ->seconds(false)
                            ->required(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Verification Details')
                    ->schema([
                        Forms\Components\TextInput::make('data_collector')
                            ->required(),
                        Forms\Components\DatePicker::make('collection_date')
                            ->required(),
                        Forms\Components\TextInput::make('verified_by')
                            ->default(fn () => auth()->user()->name)
                            ->disabled(fn () => auth()->user()->hasRole('regional_programs_support_officer'))
                            ->required(),
                        Forms\Components\DatePicker::make('verification_date')
                            ->required(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Attendees')
                    ->schema([
                        Forms\Components\Repeater::make('internal_attendance_entries')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('institution')
                                    ->required(),
                                Forms\Components\TextInput::make('designation')
                                    ->required(),
                                Forms\Components\TextInput::make('contact')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->collapsed()
                            ->addActionLabel('Add Attendee'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('region')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('activity_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internal_attendance_entries_count')
                    ->counts('internal_attendance_entries')
                    ->label('Attendees')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_collector')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('verified_by')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('collection_date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('verification_date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->options([
                        'Hhohho' => 'Hhohho',
                        'Lubombo' => 'Lubombo',
                        'Manzini' => 'Manzini',
                        'Shiselweni' => 'Shiselweni',
                    ]),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export_csv')
                    ->label('CSV')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn (InternalAttendance $record) =>
                        response()->streamDownload(
                            function () use ($record) {
                                $file = fopen('php://output', 'w');

                                // Metadata Section
                                fputcsv($file, ['Activity Register Export']);
                                fputcsv($file, ['Region', $record->region]);
                                fputcsv($file, ['Venue', $record->venue]);
                                fputcsv($file, ['Activity Date', $record->activity_date->format('Y-m-d')]);
                                fputcsv($file, ['Start Time', $record->start_time]);
                                fputcsv($file, ['Finish Time', $record->finish_time]);
                                fputcsv($file, ['Data Collector', $record->data_collector]);
                                fputcsv($file, ['Collection Date', optional($record->collection_date)->format('Y-m-d')]);
                                fputcsv($file, ['Verified By', $record->verified_by]);
                                fputcsv($file, ['Verification Date', optional($record->verification_date)->format('Y-m-d')]);
                                fputcsv($file, ['Recorded By (Officer)', optional($record->user)->name ?? 'N/A']);
                                fputcsv($file, []); // Blank row

                                // Attendees Section
                                fputcsv($file, ['Attendee List']);
                                fputcsv($file, ['Name', 'Institution', 'Designation', 'Contact', 'Email']);
                                foreach ($record->internal_attendance_entries as $entry) {
                                    fputcsv($file, [
                                        $entry->name,
                                        $entry->institution,
                                        $entry->designation,
                                        $entry->contact,
                                        $entry->email
                                    ]);
                                }
                                fclose($file);
                            },
                            "{$record->region}-{$record->activity_date->format('Y-m-d')}-{$record->venue}.csv"
                        )
                    ),
                    Tables\Actions\Action::make('export_xlsx')
                        ->icon('heroicon-o-document-arrow-down')
                        ->label('XLSX')
                        ->action(fn (InternalAttendance $record) =>
                            Excel::download(
                                new InternalAttendanceExport($record),
                                "{$record->region}-{$record->activity_date->format('Y-m-d')}-{$record->venue}.xlsx"
                            )
                        ),
                ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\InternalAttendanceEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternalAttendances::route('/'),
            'create' => Pages\CreateInternalAttendance::route('/create'),
            'edit' => Pages\EditInternalAttendance::route('/{record}/edit'),
        ];
    }
}


