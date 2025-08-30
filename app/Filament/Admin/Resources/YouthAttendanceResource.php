<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\YouthAttendanceResource\Pages;
use App\Filament\Admin\Resources\YouthAttendanceResource\RelationManagers;
use App\Models\YouthAttendance;
use App\Models\YouthAttendanceEntry;
use App\Models\TrainingTopic;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Exports\YouthAttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class YouthAttendanceResource extends Resource
{
    protected static ?string $model = YouthAttendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Youth Activity Register';
    protected static ?string $navigationGroup = 'Activity Register Management';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $query = parent::getEloquentQuery();

        // Regional officers only see their own region
        if ($user->hasRole('regional_programs_support_officer')) {
            $query->where('region_id', $user->region_id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        $recentContacts = YouthAttendanceEntry::latest()
            ->take(500) // last 500 participants (adjust as needed)
            ->pluck('contact')
            ->filter()
            ->unique()
            ->toArray();

        $recentEmails = YouthAttendanceEntry::latest()
            ->take(500)
            ->pluck('email')
            ->filter()
            ->unique()
            ->toArray();

        $user = auth()->user();
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\Select::make('region_id')
                            ->label('Region')
                            ->options(Region::all()->pluck('name', 'id'))
                            ->default($user->hasRole('regional_programs_support_officer') ? $user->region_id : null)
                            ->required(),

                        Forms\Components\TextInput::make('venue')->required(),
                        Forms\Components\DatePicker::make('activity_date')->required(),
                        Forms\Components\Select::make('activity_type')
                            ->options(['Event' => 'Event', 'Training' => 'Training'])
                            ->required(),
                            
                        Forms\Components\Select::make('topics_covered')
                            ->label('Training Topics')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Topic Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('parent_id')
                                    ->label('Parent Topic (Optional)')
                                    ->options(function () {
                                        return TrainingTopic::whereNull('parent_id')->pluck('name', 'id');
                                    })
                                    ->nullable()
                                    ->searchable(),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $topic = TrainingTopic::create([
                                    'name' => $data['name'],
                                    'parent_id' => $data['parent_id'] ?? null,
                                ]);
                                return $topic->id;
                            })
                            ->options(function () {
                                $topics = TrainingTopic::with('subtopics')->whereNull('parent_id')->get();
                                $grouped = [];
                                foreach ($topics as $topic) {
                                    if ($topic->subtopics->count()) {
                                        $grouped[$topic->name] = $topic->subtopics->pluck('name', 'id')->toArray();
                                    } else {
                                        $grouped[$topic->name] = [$topic->id => $topic->name];
                                    }
                                }
                                return $grouped;
                            }),
                            
                        Forms\Components\TimePicker::make('start_time')->seconds(false),
                        Forms\Components\TimePicker::make('finish_time')->seconds(false),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Verification Details')
                    ->schema([
                        Forms\Components\TextInput::make('data_collector')->required(),
                        Forms\Components\DatePicker::make('collection_date')->required(),
                        Forms\Components\TextInput::make('verified_by')
                            ->default(fn () => $user->name)
                            ->disabled($user->hasRole('regional_programs_support_officer'))
                            ->required(),
                        Forms\Components\DatePicker::make('verification_date')->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Youth Participants')
                    ->schema([
                        Forms\Components\Repeater::make('youth_attendance_entries')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('age')->numeric(),
                                Forms\Components\Select::make('gender')
                                    ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'])
                                    ->required(),
                                Forms\Components\Select::make('youth_region')
                                    ->label('Youth Region')
                                    ->options([
                                        'Hhohho' => 'Hhohho',
                                        'Manzini' => 'Manzini',
                                        'Lubombo' => 'Lubombo',
                                        'Shiselweni' => 'Shiselweni',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('is_employed')
                                    ->label('Are you employed?')
                                    ->options([
                                        1 => 'Yes',
                                        0 => 'No'
                                    ])
                                    ->reactive(),

                                Forms\Components\Select::make('employment_type')
                                    ->options([
                                        'Formal' => 'Formal', 
                                        'Informal' => 'Informal', 
                                        'Piece Work' => 'Piece Work'
                                    ])
                                    ->visible(fn (callable $get) => $get('is_employed') == 1)
                                    ->required(fn (callable $get) => $get('is_employed') == 1)
                                    ->nullable(),

                                Forms\Components\Select::make('education_level')
                                    ->label('Education Level')
                                    ->options([
                                        'Primary' => 'Primary',
                                        'Secondary' => 'Secondary',
                                        'High School' => 'High School',
                                        'Tertiary' => 'Tertiary',
                                    ]),
                                Forms\Components\TextInput::make('institution')->nullable(),
                                Forms\Components\TextInput::make('contact')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->nullable(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->collapsed()
                            ->addActionLabel('Add Youth Participant'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('region.name')->label('Region')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('venue')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('activity_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('activity_type')->sortable(),
                Tables\Columns\TextColumn::make('youth_attendance_entries_count')->counts('youth_attendance_entries')->label('Participants')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region_id')
                    ->options(Region::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                    ->label('Export XLSX')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn (YouthAttendance $record) =>
                        Excel::download(new YouthAttendanceExport($record), "youth-attendance-{$record->activity_date->format('Y-m-d')}.xlsx")
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListYouthAttendances::route('/'),
            'create' => Pages\CreateYouthAttendance::route('/create'),
            'edit' => Pages\EditYouthAttendance::route('/{record}/edit'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;

        // Auto-fill region for regional officers
        if ($user->hasRole('regional_programs_support_officer')) {
            $data['region_id'] = $user->region_id;
            $data['verified_by'] = $user->name;
        }

        return $data;
    }
}