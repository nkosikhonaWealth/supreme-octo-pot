<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\User;
use App\Models\Participant;
use App\Models\TOTApplication;
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

class TOTApplications extends Page
{
    public $name;
    public $email;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static string $view = 'filament.user.pages.t-o-t-applications';
    protected static ?string $navigationGroup = 'My Activities';
    protected static ?string $title = 'Training Of Trainers Application';
    protected static ?string $navigationLabel = 'Training Of Trainers Application';
    protected static ?int $navigationSort = 4;

    public $defaultAction = '';

    public function mount()
    {
        
    }

    public function getHeading(): string
    {
        return 'ENYC Training Of Trainers Application ';
    }

    public function getActions(): array
    {
        return [
            Action::make('edit_application')
            ->label('Edit Application')
            ->modalHeading('Edit Application Details')
            ->fillForm(function (): array {
                $participant = \App\Models\Participant::where('user_id', auth()->id())->first();
                $tot_application = \App\Models\TOTApplication::where('participant_id', $participant->id)->first();
                if ($tot_application) {
                    return [
                        'current_activity' => $tot_application->current_activity,
                        'certificate_upload' => $tot_application->certificate_upload,
                        'cv_upload' => $tot_application->cv_upload,
                        'youth_organization_response' => $tot_application->youth_organization_response,
                        'youth_organization_name' => $tot_application->youth_organization_name,
                        'youth_organization_duties' => $tot_application->youth_organization_duties,
                        'current_residence' => $tot_application->current_residence,
                        'motivation' => $tot_application->motivation,
                    ];
                }
                return [];
            })
            ->form([
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
                            FileUpload::make('certificate_upload')
                            ->label('Upload Your Certifcate(s)')
                            ->multiple()
                            ->disk('public')  
                            ->visibility('public')
                            ->directory('documents')
                            ->helperText(str('This Is A Certificate From High School Or Tertiary. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->maxFiles(3)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            FileUpload::make('cv_upload')
                            ->label('Upload CV')
                            ->helperText(str('Add Other Document(s) To Support Your Application e.g. C.V, etc. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->multiple()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('documents')
                            ->maxFiles(7)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            Select::make('youth_organization_response')
                            ->label('Have You Worked With A Youth-Focused Organization?')
                            ->helperText(str('Whether you were a member or you worked for organizations that help young people such as Young Hereos, World Vision, etc.'))
                            ->options(
                                [
                                    'Yes' => 'Yes I Have',
                                    'No' => 'No I Have Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('youth_organization_name')
                            ->label('What Is The Name Of The Organization?')
                            ->helperText(str('If You Answered No Above, Write None. Otherwise Write The Name Of Your Organization e.g. Langalethu Charity Organization'))
                            ->columnSpan(2),
                            Textarea::make('youth_organization_duties')
                                ->label('If You Answered No Above, Write None. Otherwise Biefly State What What You Did And How You Helped Young People.')
                                ->helperText(str('Maximum Of 400 Words or 3 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(3)
                                ->columnSpan(2),
                                Select::make('current_residence')
                            ->label('Do you currently reside in your Inkhundla?')
                            ->options(
                                [
                                    'No' => 'No',
                                    'Yes' => 'Yes',
                                ]
                            )
                            ->placeholder('Select Whether You Currently Reside In Your Inkhundla')
                            ->columnSpan(2)
                            ->required(),
                            Textarea::make('motivation')
                                ->label('Please Tell Us Why You Have Applied And Why You Are The Best Candidate For The Job?')
                                ->helperText(str('Maximum Of 700 Words or 4 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(5)
                                ->columnSpan(2),
            ])
            ->action(function (array $data): void {
                $participant = \App\Models\Participant::where('user_id', auth()->id())->first();
                $tot_application = \App\Models\TOTApplication::where('participant_id', $participant->id)->first();
                if ($tot_application) {
                    $tot_application->update($data);
                    Notification::make()
                    ->title('Application Details Updated!')
                    ->success()
                    ->send();
                }
                else{
                    $TOT = TOTApplication::create([
                        'participant_id' => $participant->id,
                        'current_activity' => $data['current_activity'],
                        'certificate_upload' => $data['certificate_upload'],
                        'cv_upload' => $data['cv_upload'],
                        'youth_organization_response' => $data['youth_organization_response'],
                        'youth_organization_name' => $data['youth_organization_name'],
                        'youth_organization_duties' => $data['youth_organization_duties'],
                        'current_residence' => $data['current_residence'],
                        'motivation' => $data['motivation'],
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
                if($participant->TOT)
                {
                    return [
                        TOTApplication::where('participant_id',$participant->id)->first()
                    ];
                }
                else
                {
                    return [
                            Participant::where('user_id',Auth::user()->id)->first()
                        ];
                }
            }
    }

    public function createAction(): Action
    {
        return
        Action::make('create')
        ->modalHeading('Your Personal Details')
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
            Step::make('Training of Trainers Application')
                ->description('Your Application To Be Trained As A Trainer')
                ->schema([
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
                            FileUpload::make('certificate_upload')
                            ->label('Upload Your Certifcate(s)')
                            ->multiple()
                            ->disk('public')  
                            ->visibility('public')
                            ->directory('documents')
                            ->helperText(str('This Is A Certificate From High School Or Tertiary. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->maxFiles(3)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            FileUpload::make('cv_upload')
                            ->label('Upload CV')
                            ->helperText(str('Add Other Document(s) To Support Your Application e.g. C.V, etc. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                            ->multiple()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('documents')
                            ->maxFiles(7)
                            ->maxSize(4096)
                            ->columnSpan(2)
                            ->required(),
                            Select::make('youth_organization_response')
                            ->label('Have You Worked With A Youth-Focused Organization?')
                            ->helperText(str('Whether you were a member or you worked for organizations that help young people such as Young Hereos, World Vision, etc.'))
                            ->options(
                                [
                                    'Yes' => 'Yes I Have',
                                    'No' => 'No I Have Not',
                                ]
                            )
                            ->placeholder('Select Your Answer')
                            ->columnSpan(2)
                            ->required(),
                            TextInput::make('youth_organization_name')
                            ->label('What Is The Name Of The Organization?')
                            ->helperText(str('If You Answered No Above, Write None. Otherwise Write The Name Of Your Organization e.g. Langalethu Charity Organization'))
                            ->columnSpan(2),
                            Textarea::make('youth_organization_duties')
                                ->label('If You Answered No Above, Write None. Otherwise Biefly State What What You Did And How You Helped Young People.')
                                ->helperText(str('Maximum Of 400 Words or 3 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(3)
                                ->columnSpan(2),
                                Select::make('current_residence')
                            ->label('Do you currently reside in your Inkhundla?')
                            ->options(
                                [
                                    'No' => 'No',
                                    'Yes' => 'Yes',
                                ]
                            )
                            ->placeholder('Select Whether You Currently Reside In Your Inkhundla')
                            ->columnSpan(2)
                            ->required(),
                            Textarea::make('motivation')
                                ->label('Please Tell Us Why You Have Applied And Why You Are The Best Candidate For The Job?')
                                ->helperText(str('Maximum Of 700 Words or 4 Sentences.'))
                                ->required()
                                ->placeholder('Write Your Message Here')
                                ->rows(5)
                                ->columnSpan(2),
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
                    'pathway' => 'Training Of Trainers',
                    'region' => $data['region'],
                    'disability' => $data['disability'],
                    'disability_name' => $data['disability_name'],
                    'family_situation' => $data['family_situation'],
                    'family_role' => $data['family_role'],
                    'beneficiaries' => $data['beneficiaries'],
                ]);
                $TOT = TOTApplication::create([
                    'participant_id' => $participant->id,
                    'current_activity' => $data['current_activity'],
                    'certificate_upload' => $data['certificate_upload'],
                    'cv_upload' => $data['cv_upload'],
                    'youth_organization_response' => $data['youth_organization_response'],
                    'youth_organization_name' => $data['youth_organization_name'],
                    'youth_organization_duties' => $data['youth_organization_duties'],
                    'current_residence' => $data['current_residence'],
                    'motivation' => $data['motivation'],
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
