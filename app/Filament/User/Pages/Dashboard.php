<?php

namespace App\Filament\User\Pages;

use Filament\Pages\Page;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use App\Models\Participant;
use App\Models\TVET;
use App\Models\Application;
use App\Filament\User\Widgets\MonthlyReportStatus;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Forms\Get;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.user.pages.dashboard';

    protected $listeners = ['open-edit-details-modal' => 'openEditModal'];

    public bool $showStartModal = false;

    public function openEditModal()
    {
        $this->dispatch('open-modal', id: 'edit_details');
    }

    public function getHeading(): string
    {
        return 'ENYC Youth Development Program - User Dashboard';
    }

    public function mount(): void
    {
        if (request()->has('openModal') && request('openModal') === 'edit_details') {
            $this->dispatch('open-edit-details-modal'); // Open modal directly
            return;
        }
        if (!Participant::where('user_id', Auth::id())->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Welcome To The ENYC Youth Development Program Portal')
                ->body('You Haven’t Completed Your Profile Yet. Please Click The "Edit Details" Button Below To Begin.')
                ->persistent()
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('startProfile')
                        ->label('Proceed')
                        ->button()
                        ->color('primary')
                        ->url(url()->current() . '?openModal=edit_details')
                ])
                ->send();
        }
    }

    public function triggerProfileModal()
    {
        $this->dispatch('open-edit_details-modal');
    }

    public function getActions(): array
    {
        return [
            Action::make('edit_details')
            ->label('Edit Details')
            ->modalHeading('Edit Participant Details')
            ->fillForm(function (): array {
                $participant = Participant::firstOrNew([
                    'user_id' => Auth::id(),
                ]);
                if ($participant) {
                    return [
                        'gender'               => $participant->gender,
                        'd_o_b'                => $participant->d_o_b,
                        'phone'                => $participant->phone,
                        'marital_status'       => $participant->marital_status,
                        'living_situation'     => $participant->living_situation,
                        'residential_address'  => $participant->residential_address,
                        'inkhundla'            => $participant->inkhundla,
                        'region'               => $participant->region,
                        'disability'           => $participant->disability,
                        'disability_name'      => $participant->disability_name,
                        'family_situation'     => $participant->family_situation,
                        'family_role'          => $participant->family_role,
                        'beneficiaries'        => $participant->beneficiaries,
                        'id_upload'            => $participant->id_upload,
                        'identity_number'      => $participant->identity_number,  
                    ];
                }
                return [];
            })
            ->form([
                FileUpload::make('id_upload')
                    ->label('Upload Your Identity Card')
                    ->disk('public')  
                    ->visibility('public')
                    ->directory('identity')
                    ->acceptedFileTypes(['image/*'])
                    ->helperText(str('The Image Should Be Less Than 1 MB And Must Be Named With Your Name. NOTE: Wait For The Image To Finish Uploading And Have A Green Overlay Before Proceeding.'))
                    ->required(), 
                TextInput::make('identity_number')
                    ->label('Identity Number')
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other'
                    ])
                    ->required(),
                DatePicker::make('d_o_b')
                    ->label('Date of Birth')
                    ->minDate('1990-01-01')
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
                    ->options([
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Divorced' => 'Divorced',
                        'Widowed' => 'Widowed'
                    ])
                    ->placeholder('Select Marital Status')
                    ->required(),
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
                    ->required(),
                Select::make('disability')
                    ->label('Disability')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No'
                    ])
                    ->required(),
                TextInput::make('disability_name')
                    ->label('What Disability Do You Have?'),
                TextInput::make('beneficiaries')
                    ->label('How Many Dependents Do You Have?')
                    ->numeric()
                    ->maxLength(2)
                    ->required(),
            ])
            ->action(function (array $data): void {
                try {
                    $participant = \App\Models\Participant::firstOrNew([
                        'user_id' => auth()->id(),
                    ]);

                    $data['pathway'] = 'Commonwealth Youth Council';

                    $participant->fill($data);

                    if (!$participant->user_id) {
                        $participant->user_id = auth()->id();
                    }

                    $participant->save();

                    Notification::make()
                        ->title('Personal Details Saved!')
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Failed to Save Details')
                        ->body('An error occurred: ' . $e->getMessage())
                        ->danger()
                        ->send();

                }
            })
        ];
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
        'participant_details' => $this->getParticipantStatus(),
        'application_feedback' => $this->getApplicationFeedback(),
        ]);
    }

    protected function getParticipantStatus()
    {
        return Participant::where('user_id', Auth::id())->first();
    }
    
    protected function getApplicationFeedback()
    {
        $participant = Participant::where('user_id', Auth::id())->first();
    
        if (!$participant) {
            return false;
        }
    
        if ($participant->pathway === 'TVET') {
            $tvet = TVET::where('participant_id', $participant->id)->first();
            
            if (!$tvet) {
                return false;
            }
    
            return Application::where('t_v_e_t_id', $tvet->id)->first() ?: false;
        }
    
        return false;
    }
}
