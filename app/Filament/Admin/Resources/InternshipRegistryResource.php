<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InternshipRegistryResource\Pages;
use App\Filament\Admin\Resources\InternshipRegistryResource\RelationManagers;
use App\Models\InternshipRegistry;
use App\Models\FieldOfStudyOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternshipRegistryResource extends Resource
{
    protected static ?string $model = InternshipRegistry::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Internship Registry';
    protected static ?string $navigationGroup = 'Activity Register Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('full_name')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                        'Other' => 'Other',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('age')
                                    ->numeric()
                                    ->minValue(16)
                                    ->maxValue(35)
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('id_number')
                                    ->label('ID Number')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('certificate')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('field_of_study')
                                    ->label('Field of Study')
                                    ->options(fn () => FieldOfStudyOption::getOptions())
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Field of Study Name'),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        FieldOfStudyOption::addOption($data['name']);
                                        return $data['name'];
                                    })
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('post_internship_employment_status')
                                    ->label('Post-Internship Employment Status')
                                    ->options([
                                        'Employed' => 'Employed',
                                        'Job Seeking' => 'Job Seeking',
                                        'Further Studying' => 'Further Studying',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\RichEditor::make('learning_outcomes_achieved')
                            ->label('Learning Outcomes Achieved')
                            ->required()
                            ->columnSpanFull(),
                            
                        Forms\Components\RichEditor::make('exit_interview_notes')
                            ->label('Exit Interview Notes')
                            ->required()
                            ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Male' => 'blue',
                        'Female' => 'pink',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('age')
                    ->sortable(),
                Tables\Columns\TextColumn::make('field_of_study')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('post_internship_employment_status')
                    ->label('Employment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Employed' => 'success',
                        'Job Seeking' => 'warning',
                        'Further Studying' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('certificate')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id_number')
                    ->label('ID #')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
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
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ]),
                Tables\Filters\SelectFilter::make('post_internship_employment_status')
                    ->label('Employment Status')
                    ->options([
                        'Employed' => 'Employed',
                        'Job seeking' => 'Job Seeking',
                        'Further studying' => 'Further Studying',
                    ]),
                Tables\Filters\SelectFilter::make('field_of_study')
                    ->options(fn () => FieldOfStudyOption::getOptions())
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export_csv')
                    ->label('Export')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn (InternshipRegistry $record) =>
                        response()->streamDownload(
                            function () use ($record) {
                                $file = fopen('php://output', 'w');

                                // Header with metadata
                                fputcsv($file, ['Internship Registry Export']);
                                fputcsv($file, ['Generated on', now()->format('Y-m-d H:i:s')]);
                                fputcsv($file, []);

                                // Intern details
                                fputcsv($file, ['Intern Information']);
                                fputcsv($file, ['Full Name', $record->full_name]);
                                fputcsv($file, ['Gender', $record->gender]);
                                fputcsv($file, ['Age', $record->age]);
                                fputcsv($file, ['ID Number', $record->id_number]);
                                fputcsv($file, ['Field of Study', $record->field_of_study]);
                                fputcsv($file, ['Certificate', $record->certificate]);
                                fputcsv($file, ['Employment Status', $record->post_internship_employment_status]);
                                fputcsv($file, []);
                                
                                // Learning outcomes and notes
                                fputcsv($file, ['Learning Outcomes Achieved']);
                                fputcsv($file, [strip_tags($record->learning_outcomes_achieved)]);
                                fputcsv($file, []);
                                
                                fputcsv($file, ['Exit Interview Notes']);
                                fputcsv($file, [strip_tags($record->exit_interview_notes)]);
                                
                                fclose($file);
                            },
                            "{$record->region}-{$record->full_name}-internship-{$record->activity_date->format('Y-m-d')}.csv"
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternshipRegistries::route('/'),
            'create' => Pages\CreateInternshipRegistry::route('/create'),
            'edit' => Pages\EditInternshipRegistry::route('/{record}/edit'),
        ];
    }
}
