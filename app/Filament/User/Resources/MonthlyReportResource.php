<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\MonthlyReportResource\Pages;
use App\Filament\User\Resources\MonthlyReportResource\RelationManagers;
use App\Models\MonthlyReport;
use App\Models\Participant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class MonthlyReportResource extends Resource
{
    protected static ?string $model = MonthlyReport::class;
    protected static ?string $title = 'Manage Monthly Reports';
    protected static ?string $navigationLabel = 'Manage Monthly Reports';
    protected static ?string $navigationGroup = 'Monthly Reports';
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?int $navigationSort = 5;
    
    public static function shouldRegisterNavigation(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        
        $user = auth()->user()->loadMissing('participant.TVET.participant_result');
        
        // Check if user has a participant record
        $participant = $user->participant;
    
        if (! $participant) {
            return false;
        }
    
        // Check if TVET record exists
        $tvet = $participant->TVET;
    
        if (! $tvet) {
            return false;
        }
    
        // Check if ParticipantResult exists and is 'Awarded'
        $result = $tvet->participant_result;
    
        return $result && $result->status === 'Awarded';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('created_at')
                    ->label('Date Filed')
                    ->default(now())
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->dehydrated(fn (string $operation): bool => $operation === 'create'),
                Select::make('employment_status')
                    ->label('What is your current employment status since completing the training or receiving toolkit support from ENYC?')
                    ->options(
                        [
                            'Self-Employed' => 'Self-Employed (Running Own Business)',
                            'Employed By Someone Else' => 'Employed By Someone Else',
                            'Doing Piece Jobs/Occasional Work' => 'Doing Piece Jobs/Occasional Work',
                            'Intern/Apprentice' => 'Intern/Apprentice',
                            'Employed' => 'Employed (Formal/Informal)',
                            'Unemployed But Looking For Work' => 'Unemployed But Looking For Work',
                            'Not Working And Not Looking For Work' => 'Not Working And Not Looking For Work',
                        ]
                    )
                    ->placeholder('Select Your Current Employment Status')
                    ->required()
                    ->columnSpan('full'),
                Select::make('toolkit_usage_status')
                    ->label('Since receiving the toolkit from ENYC, how have you been using it to support your livelihood?')
                    ->options(
                        [
                            'I Use It To Run My Own Small Business' => 'I Use It To Run My Own Small Business',
                            'I Use It For Part-Time Work Or Piece Jobs' => 'I Use It For Part-Time Work Or Piece Jobs',
                            'I Use It To Offer Services To My Community At A Fee' => 'I Use It To Offer Services To My Community At A Fee',
                            'I Haven’t Used It Yet, But I Plan To' => 'I Haven’t Used It Yet, But I Plan To',
                            'I Haven’t Used It And Don’t Plan To' => 'I Haven’t Used It And Don’t Plan To',
                        ]
                    )
                    ->placeholder('Select How You Have Been Using The Toolkit')
                    ->required()
                    ->columnSpan('full'),
                Select::make('income_improvement_status')
                    ->label('Has Your Ability To Earn Income Improved Since Receiving The ENYC Toolkit?')
                    ->options([
                        'Yes, I Now Earn Regular Income' => 'Yes, I Now Earn Regular Income',
                        'Yes, I Earn Occasionally (Depending On Jobs)' => 'Yes, I Earn Occasionally (Depending On Jobs)',
                        'Not Yet, But I’m Working On It' => 'Not Yet, But I’m Working On It',
                        'No There Has Been No Income Change' => 'No There Has Been No Income Change',
                    ])
                    ->placeholder('Select Your Income Improvement Status')
                    ->required()
                    ->columnSpan('full'),
                FileUpload::make('proof_of_work')
                    ->label('If You Have Used The Toolkit, Please Attach Pictures Of How You Have Used It.')
                    ->disk('public') 
                    ->directory('proof-of-work')
                    ->multiple()
                    ->maxFiles(7)
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpan('full'),
                TextInput::make('income_generated')
                    ->label('How Much Do You Earn In A Good Month Using The Toolkit?')
                    ->prefix('SZL')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->columnSpan('full'),
                TextInput::make('estimated_expenses')
                    ->label('How Much Are Your Monthly Expenses?')
                    ->numeric()
                    ->prefix('SZL')
                    ->minValue(0)
                    ->required()
                    ->columnSpan('full'),
                TextInput::make('amount_saved')
                    ->label('How Much Goes Into Your Monthly Savings?')
                    ->numeric()
                    ->prefix('SZL')
                    ->minValue(0)
                    ->nullable()
                    ->columnSpan('full'),
                Select::make('self_reliance_confidence')
                    ->label('Do You Feel More Confident Or Prepared To Support Yourself And Your Family As A Result Of The Training And Toolkit Support?')
                    ->options([
                        'Yes, I Feel Fully Confident And Capable' => 'Yes, I Feel Fully Confident And Capable',
                        'Yes, But I Still Need More Support Or Mentorship' => 'Yes, But I Still Need More Support Or Mentorship',
                        'I’m Unsure' => 'I’m Unsure',
                        'No, I Don’t Feel Confident Yet' => 'No, I Don’t Feel Confident Yet',
                    ])
                    ->placeholder('Select How Confident You Are To Support Yourself')
                    ->required()
                    ->columnSpan('full'),
                RichEditor::make('additional_support_needs')
                    ->label('What Additional Support Would Help You Fully Benefit From The Toolkit? (Include Trainings Needed If Any)')
                    ->required()
                    ->columnSpan('full'),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_month')
                    ->label('Month')
                    ->date('M Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('employment_status')
                    ->label('Employment Status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('income_generated')
                    ->label('Income')
                    ->money('SZL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimated_expenses')
                    ->label('Expenses')
                    ->money('SZL')
                    ->sortable(),
                Tables\Columns\IconColumn::make('admin_verified')
                    ->label('Verified')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('participant_id', Auth::user()->participant->id ?? null);
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
            'index' => Pages\ListMonthlyReports::route('/'),
            'create' => Pages\CreateMonthlyReport::route('/create'),
            'edit' => Pages\EditMonthlyReport::route('/{record}/edit'),
        ];
    }
}
