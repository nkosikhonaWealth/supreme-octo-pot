<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EventAttendanceReportResource\Pages;
use App\Filament\Admin\Resources\EventAttendanceReportResource\RelationManagers;
use App\Models\EventAttendanceReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class EventAttendanceReportResource extends Resource
{
    protected static ?string $model = EventAttendanceReport::class;
    protected static ?string $navigationGroup = 'M&E';
    protected static ?string $navigationLabel = 'Event Attendance Report';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        if (!auth()->user()->hasRole(['m_&_e','executive'])) {
            $query->where('user_id', auth()->id());
        }
        return $query;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('stakeholder_level')
                ->label('Stakeholder Level')
                ->options(['International' => 'International', 'National' => 'National'])
                ->required(),
            TextInput::make('region')->required(),
            TextInput::make('location')->required(),
            Select::make('event_type')
                ->options(['Meeting'=>'Meeting','Workshop'=>'Workshop','Launch'=>'Launch','Consultation'=>'Consultation','Other'=>'Other'])
                ->required(),
            DatePicker::make('engagement_date')->label('Date of Engagement')->required(),
            DatePicker::make('report_date')->label('Date of Report Submission')->required(),
            TextInput::make('programme_area')->label('ENYC Programme Area Represented')->required(),
            Textarea::make('purpose')->required(),
            Textarea::make('summary')->label('Summary of Proceedings')->required(),
            Textarea::make('key_themes')->label('Key Themes / Topics Discussed'),
            Textarea::make('key_stakeholders')->label('Key Stakeholders Present'),
            Textarea::make('opportunities')->label('Opportunities for ENYC'),
            Repeater::make('action_items')
                ->schema([
                    TextInput::make('item')->label('Action Item'),
                    TextInput::make('responsible')->label('Responsible Party'),
                    TextInput::make('timeline')->label('Timeline'),
                    TextInput::make('status')->label('Status'),
                ])->label('Action Points')->columns(4),
            Textarea::make('lessons')->label('Lessons Learnt / Reflections'),
            FileUpload::make('supporting_materials')
                ->multiple()
                ->disk('public')
                ->directory('event_reports')
                ->acceptedFileTypes(['application/pdf','image/*'])
                ->maxSize(4096)
                ->label('Supporting Materials (agenda, slides, photos, etc.)'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Submitted By')->sortable()->searchable(),
                TextColumn::make('stakeholder_level'),
                TextColumn::make('event_type'),
                TextColumn::make('region'),
                TextColumn::make('engagement_date')->date(),
                TextColumn::make('report_date')->date(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEventAttendanceReports::route('/'),
            'create' => Pages\CreateEventAttendanceReport::route('/create'),
            'edit' => Pages\EditEventAttendanceReport::route('/{record}/edit'),
        ];
    }
}
