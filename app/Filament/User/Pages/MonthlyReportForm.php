<?php

namespace App\Filament\User\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Form;
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
use App\Models\MonthlyReport;
use App\Models\Participant;

class MonthlyReportForm extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static string $view = 'filament.user.pages.monthly-report-form';
    protected static ?string $navigationGroup = 'Monthly Reports';
    protected static ?string $title = 'Add Monthly Report';
    protected static ?string $navigationLabel = 'Add Monthly Report';
    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(['report_month' => Carbon::now()->startOfMonth(),]);
    }
    
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('report_month')
                    ->label('Report Month')
                    ->required()
                    ->native(false)
                    ->displayFormat('M Y')
                    ->rule(function () {
                        $participantId = auth()->user()?->participant?->id;

                        return Rule::unique(MonthlyReport::class, 'report_month')
                            ->where(fn ($query) => $query->where('participant_id', $participantId));
                    })
                    ->validationAttribute('report month'),
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
                    ->required(),
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
                    ->required(),
                Select::make('income_improvement_status')
                    ->label('Has Your Ability To Earn Income Improved Since Receiving The ENYC Toolkit?')
                    ->options([
                        'Yes, I Now Earn Regular Income' => 'Yes, I Now Earn Regular Income',
                        'Yes, I Earn Occasionally (Depending On Jobs)' => 'Yes, I Earn Occasionally (Depending On Jobs)',
                        'Not Yet, But I’m Working On It' => 'Not Yet, But I’m Working On It',
                        'No There Has Been No Income Change' => 'No There Has Been No Income Change',
                    ])
                    ->placeholder('Select Your Income Improvement Status')
                    ->required(),
                FileUpload::make('proof_of_work')
                    ->label('If You Have Used The Toolkit, Please Attach Pictures Of How You Have Used It.')
                    ->disk('public') 
                    ->directory('proof-of-work')
                    ->multiple()
                    ->maxFiles(7)
                    ->acceptedFileTypes(['image/*']),
                TextInput::make('income_generated')
                    ->label('How Much Do You Earn In A Good Month Using The Toolkit?')
                    ->prefix('SZL')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                TextInput::make('estimated_expenses')
                    ->label('How Much Are Your Monthly Expenses?')
                    ->numeric()
                    ->prefix('SZL')
                    ->minValue(0)
                    ->required(),
                TextInput::make('amount_saved')
                    ->label('How Much Goes Into Your Monthly Savings?')
                    ->numeric()
                    ->prefix('SZL')
                    ->minValue(0)
                    ->nullable(),
                Select::make('self_reliance_confidence')
                    ->label('Do You Feel More Confident Or Prepared To Support Yourself And Your Family As A Result Of The Training And Toolkit Support?')
                    ->options([
                        'Yes, I Feel Fully Confident And Capable' => 'Yes, I Feel Fully Confident And Capable',
                        'Yes, But I Still Need More Support Or Mentorship' => 'Yes, But I Still Need More Support Or Mentorship',
                        'I’m Unsure' => 'I’m Unsure',
                        'No, I Don’t Feel Confident Yet' => 'No, I Don’t Feel Confident Yet',
                    ])
                    ->placeholder('Select How Confident You Are To Support Yourself')
                    ->required(),
                Forms\Components\Section::make('Employment Information')
                    ->schema([
                        Forms\Components\TextInput::make('people_hired_seasonal')
                            ->label('Number of People Hired (Seasonal)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->step(1),
                        Forms\Components\TextInput::make('people_hired_temporal')
                            ->label('Number of People Hired (Temporal)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->step(1),
                        Forms\Components\TextInput::make('people_hired_full_time')
                            ->label('Number of People Hired (Full-Time)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->step(1),
                    ])->columns(3),

                Forms\Components\Section::make('Financial Assistance')
                    ->schema([
                        Forms\Components\Select::make('received_financial_assistance')
                            ->label('Have You Received Any Other Financial Assistance Ever Since You Received The Toolkit?')
                            ->options([
                                'yes' => 'Yes',
                                'no' => 'No',
                            ])
                            ->reactive()
                            ->required(),
                            
                        Forms\Components\Select::make('assistance_type')
                            ->label('If Yes, Select How You Received Assistance')
                            ->options([
                                'grant' => 'Grant',
                                'loan' => 'Loan',
                            ])
                            ->visible(fn (callable $get) => $get('received_financial_assistance') === 'yes')
                            ->required(fn (callable $get) => $get('received_financial_assistance') === 'yes'),
                    ])->columns(2),
                RichEditor::make('additional_support_needs')
                    ->label('What Additional Support Would Help You Fully Benefit From The Toolkit? (Include Trainings Needed If Any)')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $participantId = auth()->user()->participant->id ?? null;

        if (! $participantId) {
            Notification::make()
                ->title('Participant not found')
                ->danger()
                ->send();
            return;
        }

        try {
            $validated = $this->form->getState();

            validator(
                array_merge($validated, ['participant_id' => $participantId]),
                [
                    'report_month' => [
                        'required',
                        Rule::unique(MonthlyReport::class)
                            ->where(fn ($query) => $query->where('participant_id', $participantId)),
                    ],
                    // Add more validations as needed
                ],
                [
                    'report_month.unique' => 'You have already submitted a report for this month.',
                ]
            )->validate();

            MonthlyReport::create(array_merge(
                $validated,
                ['participant_id' => $participantId]
            ));

            Notification::make()
                ->title('Monthly Report Submitted')
                ->success()
                ->send();

            $this->form->fill(); // reset form

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Failed to Submit Monthly Report')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('submit')
                ->label('Submit Report')
                ->action('submit')
                ->button()
                ->color('primary'),
        ];
    }
}
