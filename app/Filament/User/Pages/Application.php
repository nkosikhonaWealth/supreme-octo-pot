<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\User;
use App\Models\Participant;
use App\Models\TVET;
use App\Models\Entrepreneurship;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationRecievedMail;
use App\Mail\ApplicationProcessedMail;
use Illuminate\Support\HtmlString;

class Application extends Page
{
    public $name;
    public $email;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static string $view = 'filament.user.pages.application';
    protected static ?string $navigationGroup = 'My Activities';
    protected static ?string $title = 'My ENYC YDP Application';
    protected static ?string $navigationLabel = 'My ENYC YDP Application';
    protected static ?int $navigationSort = 4;

    public $defaultAction = '';

    public function mount()
    {
        if(!$this->getApplicationStatus()[0])
        {
            $this->defaultAction = 'create';
        }
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

    public function getHeading(): string
    {
        return 'ENYC TVET Support Programme - User Application ';
    }

    public function getActions(): array
    {
        return [
            Action::make('edit_application')
            ->label('Edit Application')
            ->modalHeading('Edit Application Details')
            ->fillForm(function (): array {
                $participant = \App\Models\Participant::where('user_id', auth()->id())->first();
                $tvet_application = \App\Models\TVET::where('participant_id', $participant->id)->first();
                if ($tvet_application) {
                    return [
                        'vocational_skill' => $tvet_application->vocational_skill,
                        'current_activity' => $tvet_application->current_activity,
                        'duration' => $tvet_application->duration,
                        'toolkit_use' => $tvet_application->toolkit_use,
                        'recent_assistance' => $tvet_application->recent_assistance,
                        'certificate_upload' => $tvet_application->certificate_upload,
                        'finance_upload' => $tvet_application->finance_upload,
                        'motivation' => $tvet_application->motivation,
                        'account' => $tvet_application->account,
                        'account_number' => $tvet_application->account_number,
                        'vocational_skill_obtained' => $tvet_application->vocational_skill_obtained,
                        'youth_organization_response' => $tvet_application->youth_organization_response,
                        'youth_organization_name' => $tvet_application->youth_organization_name,
                    ];
                }
                return [];
            })
            ->form([
                Select::make('vocational_skill')
                            ->label('What Is Your Vocational Skill?')
                            ->options(
                                [
                                    'Carpentry' => 'Carpentry',
                                    'Electrician' => 'Electrician',
                                    'Motor Mechanic' => 'Motor Mechanic',
                                    'Plumbing' => 'Plumbing',
                                    'Sewing' => 'Sewing',
                                    'Welding' => 'Welding',
                                ]
                            )
                            ->placeholder('Select Your Vocational Skill')
                            ->required(),
                            Select::make('vocational_skill_obtained')
                            ->label('How Did You Obtain Your Vocational Skill?')
                            ->options(
                                [
                                    'Tertiary Education' => 'Tertiary Education',
                                    'Practical Experience Under A Qualified Technician' => 'Practical Experience Under A Qualified Technician',
                                    'Practical Experience Under A Non-Qualified Technician' => 'Practical Experience Under A Non-Qualified Technician',
                                ]
                            )
                            ->placeholder('Select How You Obtained Your Vocational Skill')
                            ->required(),
                            FileUpload::make('certificate_upload')
                            ->label('Upload Your Vocational Certifcate(s) / Letter(s)')
                            ->helperText(str('This Is A Certificate From Your Vocational School Or A Letter From Someone You Have Worked With, To Certify That You Have Worked With The Vocational Skill. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->maxFiles(3)
                            ->multiple()
                            ->disk('public')  
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxFiles(3)
                            ->maxSize(4096)
                            ->required(),
                            Select::make('current_activity')
                            ->label('What Is Your Employment Status?')
                            ->options(
                                [
                                    'Employed' => 'Employed',
                                    'Self-Employed' => 'Self-Employed',
                                    'Unemployed' => 'Unemployed',
                                ]
                            )
                            ->placeholder('Select Your Employment Status')
                            ->required(),
                            Select::make('duration')
                            ->label('If Employed, How Long Have You Been Employed?')
                            ->options(
                                [
                                    'Unemployed' => 'Unemployed',
                                    '1 year or less' => '1 year or less',
                                    '2 years' => '2 years',
                                    '3 years' => '3 years',
                                    '4 years or more' => '4 years or more',
                                ]
                            )
                            ->placeholder('Select How Long You Have Been Employed')
                            ->required(),
                            TextInput::make('toolkit_use')
                            ->label('If You Were Awarded A Toolkit For Your Vocational Skill,
                            What Would You Use It For?')
                            ->helperText(str('Maximum Of 300 Words or 2 Sentences.'))
                            ->required(),
                            Select::make('recent_assistance')
                            ->label('In The Past 6 Months, Have You Been Part Of Any Youth Development Programme?')
                            ->options(
                                [
                                    'Yes' => 'Yes I Have',
                                    'No' => 'No I Have Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->required(),
                            Select::make('youth_organization_response')
                            ->label('Are You A Member Of Any Youth-Led Organization?')
                            ->options(
                                [
                                    'Yes' => 'Yes I Am',
                                    'No' => 'No I Am Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->required(),
                            TextInput::make('youth_organization_name')
                            ->label('What Is The Name Of The Organization?')
                            ->helperText(str('If You Answered No Above, Write None. Otherwise Write The Name Of Your Organization e.g. Langalethu Charity Organization')),
                            FileUpload::make('finance_upload')
                            ->label('Upload Business Profile')
                            ->hint(new HtmlString('<a href="/storage/samples/Profile and Portfolio Of Works.pdf" download>Download Example</a>'))
                            ->hintColor('primary')
                            ->helperText(str('Add Other Document(s) To Support Your Request For Assistance e.g. Portfolio, C.V, etc. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->multiple()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxFiles(7)
                            ->maxSize(4096)
                            ->required(),
                            Select::make('account')
                            ->label('Do You Have A Bank / MoMo Account For Business Purposes?')
                            ->options(
                                [
                                    'Yes' => 'Yes',
                                    'No' => 'No',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->helperText(str('This Must Be An Account You Use Strictly For Business'))
                            ->required(),
                            TextInput::make('account_number')
                                ->label('If Yes, Please Write The Account / Phone Number')
                                ->helperText(str('It Must Look As Follows: Standard Bank - 90000 / Momo - 7600 0000. If You Answered No Above, Then Write None.')),
                            Textarea::make('motivation')
                                ->label('Please Tell Us Why You Have Applied And What You
                                Hope To Gain From This Program?')
                                ->helperText(str('Maximum Of 700 Words or 4 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(5),
            ])
            ->action(function (array $data): void {
                $participant = \App\Models\Participant::where('user_id', auth()->id())->first();
                $tvet_application = \App\Models\TVET::where('participant_id', $participant->id)->first();
                if ($tvet_application) {
                    $tvet_application->update($data);
                    Notification::make()
                    ->title('Application Details Updated!')
                    ->success()
                    ->send();
                }
            })
        ];
    }

    protected function getViewData(): array
    {
        return [
            'application_details' => $this->getApplicationStatus(),
            'participant_details' => $this->getParticipantDetails(),
        ];
    }

    protected function getParticipantDetails()
    {
        $participant = Participant::where('user_id',Auth::user()->id)->first();
            if($participant)
            {
                return [
                    Participant::where('user_id',Auth::user()->id)->first()
                ];
            }
    }

    protected function getApplicationStatus()
    {
        $participant = Participant::where('user_id',Auth::user()->id)->first();
            if($participant)
            {
                if($participant->pathway === "Entrepreneurship")
                {
                    return [
                        Entrepreneurship::where('participant_id',$participant->id)->first()
                    ];
                }
                else if($participant->pathway === "TVET")
                {
                    return [
                        TVET::where('participant_id',$participant->id)->first()
                    ];
                }
            }
            else
            {
                return [
                    null
                ];
            }
    }

    public function createAction(): Action
    {
        return
        Action::make('create')
        ->modalHeading('Your ENYC TVET Support Programme Application')
        ->steps([
            Step::make('Personal Details')
                ->description('Enter Your Personal Details')
                ->schema([
                    Select::make('gender')
                        ->label('Gender')
                        ->options(
                            [
                                'Female' => 'Female',
                                'Male' => 'Male',
                                'Other' => 'Other'
                            ]
                        )
                        ->placeholder('Select Gender')
                        ->required(),
                    DatePicker::make('d_o_b')
                    ->label('Date Of Birth')
                    ->minDate('1989-01-01')
                    ->maxDate('2007-01-01')
                    ->required(),
                    TextInput::make('phone')
                    ->label('Phone Number')
                    ->numeric()
                    ->minLength(8)
                    ->maxLength(8)
                    ->startsWith(7)
                    ->prefix('+268')
                    ->required(),
                    Select::make('marital_status')
                        ->label('Marital Status')
                        ->options(
                            [
                                'Single' => 'Single',
                                'Married' => 'Married',
                                'Divorced' => 'Divorced',
                                'Widowed' => 'Widowed'
                            ]
                        )
                        ->placeholder('Select Marital Status')
                        ->required(),
                    Select::make('disability')
                    ->label('Do You Have A Disability?')
                    ->options(
                        [
                            'Yes' => 'Yes',
                            'No' => 'No',
                        ]
                    )
                    ->placeholder('Select An Option')
                    ->live()
                    ->required(),
                    TextInput::make('disability_name')
                    ->label('What Disability Do You Have?'),
                    TextInput::make('beneficiaries')
                    ->label('How Many Dependents Do You Have?')
                    ->numeric()
                    ->maxLength(2)
                    ->required(),
                    TextInput::make('identity_number')
                    ->label('What Is Your ID Number?')
                    ->numeric()
                    ->required(),
                    FileUpload::make('id_upload')
                    ->label('Upload Your Identity Card')
                    ->disk('public')  
                    ->visibility('public')
                    ->directory('identity')
                    ->acceptedFileTypes(['image/*'])
                    ->helperText(str('The Image Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                    ->required(),
                ])
                ->columns(2),
            Step::make('Your Living Situation')
                ->description('Tell Us About Your Living Situation')
                ->schema([
                    Select::make('living_situation')
                    ->label('What Is Your Living Situation?')
                    ->options(
                        [
                            'Parental Home' => 'Parental Home',
                            'Rental' => 'Rental',
                            'Work Quarters' => 'Work Quarters',
                            'School Accomodation' => 'School Accomodation',
                            'Other' => 'Other'
                        ]
                    )
                    ->placeholder('Select Living Situation')
                    ->required(),
                    Select::make('family_situation')
                    ->label('What Is Your Family Situation?')
                    ->options(
                        [
                            'Nuclear Family' => 'Nuclear Family',
                            'Extended Family' => 'Extended Family',
                            'Child Headed Family' => 'Child Headed Family',
                            'Orphaned' => 'Orphaned',
                            'Other' => 'Other'
                        ]
                    )
                    ->placeholder('Select Family Situation')
                    ->required(),
                    Select::make('family_role')
                    ->label('What Is Your Role In Your Family?')
                    ->options(
                        [
                            'Parent' => 'Parent',
                            'Guardian' => 'Guardian',
                            'Child' => 'Child',
                            'Other' => 'Other'
                        ]
                    )
                    ->placeholder('Select Family Role')
                    ->required(),
                    TextInput::make('residential_address')
                    ->label('Residential Address')
                    ->placeholder('Nsuka, next to Royal Kraal')
                    ->required(),
                    Select::make('region')
                    ->label('Region')
                    ->options(
                        [
                            'Hhohho' => 'Hhohho',
                            'Lubombo' => 'Lubombo',
                            'Manzini' => 'Manzini',
                            'Shiselweni' => 'Shiselweni'
                        ]
                    )
                    ->placeholder('Select Region')
                    ->live()
                    ->required(),
                    Select::make('inkhundla')
                    ->label('Inkhundla')
                    ->searchable()
                    ->options(fn (Get $get): array => match ($get('region')) {
                        'Hhohho' => [
                            'Lobamba' => 'Lobamba',
                            'Madlangemphisi' => 'Madlangemphisi',
                            'Ndzingeni' => 'Ndzingeni',
                            'Mayiwane' => 'Mayiwane',
                            'Ntfonjeni' => 'Ntfonjeni',
                            'Piggs Peak' => 'Piggs Peak',
                            'Motshane' => 'Motshane',
                            'Nkhaba' => 'Nkhaba',
                            'Hhukwini' => 'Hhukwini',
                            'Maphalaleni' => 'Maphalaleni',
                            'Mhlangatane' => 'Mhlangatane',
                            'Timphisini' => 'Timphisini',
                            'Mbabane West' => 'Mbabane West',
                            'Mbabane East' => 'Mbabane East',
                            'Siphocosini' => 'Siphocosini',
                        ],
                        'Lubombo' => [
                            'Matsanjeni North' => 'Matsanjeni North',
                            'Siphofaneni' => 'Siphofaneni',
                            'Mpolonjeni' => 'Mpolonjeni',
                            'Dvokodvweni' => 'Dvokodvweni',
                            'Lugongolweni' => 'Lugongolweni',
                            'Lomahasha' => 'Lomahasha',
                            'Lubuli' => 'Lubuli',
                            'Sithobelweni' => 'Sithobelweni',
                            'Nkilongo' => 'Nkilongo',
                            'Mhlume' => 'Mhlume',
                            'Gilgal' => 'Gilgal',
                        ],
                        'Manzini' => [
                            'Ludzeludze' => 'Ludzeludze',
                            'Ekukhanyeni' => 'Ekukhanyeni',
                            'Mkhiweni' => 'Mkhiweni',
                            'Mtfongwaneni' => 'Mtfongwaneni',
                            'Mafutseni' => 'Mafutseni',
                            'LaMgabhi' => 'LaMgabhi',
                            'Mhlambanyatsi' => 'Mhlambanyatsi',
                            'Mangcongco' => 'Mangcongco',
                            'Ngwempisi' => 'Ngwempisi',
                            'Mahlangatsha' => 'Mahlangatsha',
                            'Manzini North' => 'Manzini North',
                            'Manzini South' => 'Manzini South',
                            'Nhlambeni' => 'Nhlambeni',
                            'Kwaluseni' => 'Kwaluseni',
                            'Lobamba Lomdzala' => 'Lobamba Lomdzala',
                            'Ntondozi' => 'Ntondozi',
                            'Phondo' => 'Phondo',
                            'Nkomiyahlaba' => 'Nkomiyahlaba',
                        ],
                        'Shiselweni' => [
                            'Sandleni' => 'Sandleni',
                            'Zombodze Emuva' => 'Zombodze Emuva',
                            'Somntongo' => 'Somntongo',
                            'Matsanjeni' => 'Matsanjeni',
                            'Sigwe' => 'Sigwe',
                            'Shiselweni I' => 'Shiselweni I',
                            'Gege' => 'Gege',
                            'Maseyisini' => 'Maseyisini',
                            'Kubuta' => 'Kubuta',
                            'Mtsambama' => 'Mtsambama',
                            'Nkwene' => 'Nkwene',
                            'Shiselweni II' => 'Shiselweni II',
                            'Hosea' => 'Hosea',
                            'Ngudzeni' => 'Ngudzeni',
                            'KuMethula' => 'KuMethula',
                        ],
                        default => [],
                    })
                    ->placeholder('Select Inkhundla')
                    ->preload()
                    ->required(),
                ])
                ->columns(2),
            Step::make('ENYC TVET Support Programme Application')
                ->description('Your Application To The Program')
                ->schema([
                    Select::make('pathway')
                    ->label('Which Pathway Are You Applying To?')
                    ->options(
                        [
                            'TVET' => 'TVET',
                        ]
                    )
                    ->placeholder('Select The Program You Are Applying For')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('dynamicTypeFields')
                        ->getChildComponentContainer()
                        ->fill()),
                Grid::make(2)
                    ->schema(fn (Get $get): array => match ($get('pathway')) {
                        'TVET' => [
                            Select::make('vocational_skill')
                            ->label('What Is Your Vocational Skill?')
                            ->options(
                                [
                                    'Carpentry' => 'Carpentry',
                                    'Electrician' => 'Electrician',
                                    'Motor Mechanic' => 'Motor Mechanic',
                                    'Plumbing' => 'Plumbing',
                                    'Sewing' => 'Sewing',
                                    'Welding' => 'Welding',
                                ]
                            )
                            ->placeholder('Select Your Vocational Skill')
                            ->columnSpan(2)
                            ->required(),
                            Select::make('vocational_skill_obtained')
                            ->label('How Did You Obtain Your Vocational Skill?')
                            ->options(
                                [
                                    'Tertiary Education' => 'Tertiary Education',
                                    'Practical Experience Under A Qualified Technician' => 'Practical Experience Under A Qualified Technician',
                                    'Practical Experience Under A Non-Qualified Technician' => 'Practical Experience Under A Non-Qualified Technician',
                                ]
                            )
                            ->placeholder('How You Obtained Your  Vocational Skill')
                            ->columnSpan(2)
                            ->required(),
                            FileUpload::make('certificate_upload')
                            ->label('Upload Your Vocational Certifcate(s) / Letter(s)')
                            ->multiple()
                            ->disk('public')  
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->helperText(str('This Is A Certificate From Your Vocational School Or A Letter From Someone You Have Worked With, To Certify That You Have Worked With The Vocational Skill. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->maxFiles(3)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            Select::make('current_activity')
                            ->label('What Is Your Employment Status?')
                            ->options(
                                [
                                    'Employed' => 'Employed',
                                    'Self-Employed' => 'Self-Employed',
                                    'Unemployed' => 'Unemployed',
                                ]
                            )
                            ->placeholder('Select Your Employment Status')
                            ->columnSpan(2)
                            ->required(),
                            Select::make('duration')
                            ->label('If Employed, How Long Have You Been Employed?')
                            ->options(
                                [
                                    'Unemployed' => 'Unemployed',
                                    '1 year or less' => '1 year or less',
                                    '2 years' => '2 years',
                                    '3 years' => '3 years',
                                    '4 years or more' => '4 years or more',
                                ]
                            )
                            ->placeholder('Select How Long You Have Been Doing It')
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('toolkit_use')
                            ->label('If You Were Awarded A Toolkit For Your Vocational Skill,
                            What Would You Use It For?')
                            ->columnSpan(2)
                            ->helperText(str('Maximum Of 300 Words or 2 Sentences.'))
                            ->required(),
                            Select::make('recent_assistance')
                            ->label('In The Past 6 Months, Have You Been Part Of Any Youth Development Programme?')
                            ->options(
                                [
                                    'Yes' => 'Yes I Have',
                                    'No' => 'No I Have Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->columnSpan(2)
                            ->required(),
                            Select::make('youth_organization_response')
                            ->label('Are You A Member Of Any Youth-Led Organization?')
                            ->options(
                                [
                                    'Yes' => 'Yes I Am',
                                    'No' => 'No I Am Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('youth_organization_name')
                            ->label('What Is The Name Of The Organization?')
                            ->helperText(str('If You Answered No Above, Write None. Otherwise Write The Name Of Your Organization e.g. Langalethu Charity Organization'))
                            ->columnSpan(2),
                            FileUpload::make('finance_upload')
                            ->label('Upload Business Profile')
                            ->hint(new HtmlString('<a href="/storage/samples/Profile and Portfolio Of Works.pdf" download>Download Example</a>'))
                            ->hintColor('primary')
                            ->helperText(str('Add Other Document(s) To Support Your Request For Assistance e.g. Portfolio, C.V, etc. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->multiple()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxFiles(7)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            Select::make('account')
                            ->label('Do You Have A Bank / MoMo Account For Business Purposes?')
                            ->options(
                                [
                                    'Yes' => 'Yes',
                                    'No' => 'No',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->helperText(str('This Must Be An Account You Use Strictly For Business'))
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('account_number')
                                ->label('If Yes, Please Write The Account / Phone Number')
                                ->columnSpan(2)
                                ->helperText(str('It Must Look As Follows: Standard Bank - 90000 / Momo - 7600 0000. If You Answered No Above, Then Write None.')),
                            Textarea::make('motivation')
                                ->label('Please Tell Us Why You Have Applied And What You
                                Hope To Gain From This Program?')
                                ->helperText(str('Maximum Of 700 Words or 4 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(5)
                                ->columnSpan(2),
                        ],
                        'Mindsets' => [

                        ],
                        default => [],
                    })
                    ->key('dynamicTypeFields'),
                ]),
            ])
            ->action(function (array $data) {
                $participant = Participant::create([
                    'user_id' => Auth::user()->id,
                    'gender' => $data['gender'],
                    'd_o_b' => $data['d_o_b'],
                    'phone' => $data['phone'],
                    'marital_status' => $data['marital_status'],
                    'identity_number' => $data['identity_number'],
                    'id_upload' => $data['id_upload'],
                    'residential_address' => $data['residential_address'],
                    'living_situation' => $data['living_situation'],
                    'inkhundla' => $data['inkhundla'],
                    'pathway' => $data['pathway'],
                    'region' => $data['region'],
                    'disability' => $data['disability'],
                    'disability_name' => $data['disability_name'],
                    'family_situation' => $data['family_situation'],
                    'family_role' => $data['family_role'],
                    'beneficiaries' => $data['beneficiaries'],
                ]);
                $TVET = TVET::create([
                    'participant_id' => $participant->id,
                    'vocational_skill' => $data['vocational_skill'],
                    'vocational_skill_obtained' => $data['vocational_skill_obtained'],
                    'certificate_upload' => $data['certificate_upload'],
                    'current_activity' => $data['current_activity'],
                    'duration' => $data['duration'],
                    'toolkit_use' => $data['toolkit_use'],
                    'youth_organization_response' => $data['youth_organization_response'],
                    'youth_organization_name' => $data['youth_organization_name'],
                    'recent_assistance' => $data['recent_assistance'],
                    'finance_upload' => $data['finance_upload'],
                    'motivation' => $data['motivation'],
                    'account' => $data['account'],
                    'account_number' => $data['account_number'],
                ]);
                Notification::make()
                    ->title('Application Recieved!')
                    ->success()
                    ->send();
                $user = User::find(Auth::user()->id);
                try {
                        Mail::to($user->email)->send(new ApplicationRecievedMail($participant, $user));
                        Notification::make()
                            ->title('Email Sent!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to Send Email')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
            });
    }
}
