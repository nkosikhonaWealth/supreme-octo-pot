<?php

namespace App\Filament\User\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use App\Models\Participant;
use App\Models\CYCApplication;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationRecievedMail;

class CYCApplications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.user.pages.c-y-c-applications';
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'My Activities';
    protected static ?string $title = 'Commonwealth Youth Council Application';
    protected static ?string $navigationLabel = 'Commonwealth Youth Council Application';
    protected static ?int $navigationSort = 3;

    public ?CYCApplication $cyc_application = null;

    public function mount(): void
    {
        $participant = Participant::where('user_id', Auth::id())->first();

        $this->cyc_application = CYCApplication::firstOrNew([
            'participant_id' => $participant->id,
        ]);

        if (!$participant) {
            Notification::make()
                ->danger()
                ->title('Incomplete Profile')
                ->body('Please Complete Your Participant Profile Before Applying.')
                ->send();

            redirect('/user/dashboard');
        }

        if (request()->has('openModal') && request('openModal') === 'edit_details') {
            $this->cyc_application = CYCApplication::firstOrNew([
                'participant_id' => $participant->id,
            ]);

            $this->form->fill($this->cyc_application->toArray());
            return;
        }

        if (!CYCApplication::where('participant_id', $participant->id)->first()) {
            \Filament\Notifications\Notification::make()
                ->title('Welcome To The ENYC Youth Development Program Portal')
                ->body('You Haven’t Completed Your CYC Application Yet. Please Click The "Edit Application" Button Below To Begin.')
                ->persistent()
                ->warning()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('startApplication')
                        ->label('Proceed')
                        ->button()
                        ->color('primary')
                        ->url(url()->current() . '?openModal=edit_details')
                ])
                ->send();
        }

    }

    public function getActions(): array
    {
        return [
            Action::make('edit_application')
                ->label('Edit Application')
                ->modalHeading('Edit Commonwealth Youth Council Application')
                ->fillForm(function (): array {
                    $participant = Participant::where('user_id', auth()->id())->first();
                    $application = CYCApplication::where('participant_id', $participant?->id)->first();

                    if ($application) {
                        return [
                            'sdg_response' => $application->sdg_response,
                            'challenge_response' => $application->challenge_response,
                            'representation_experience' => $application->representation_experience,
                            'representation_details' => $application->representation_details,
                            'leadership_experience' => $application->leadership_experience,
                            'motivation' => $application->motivation,
                            'cv_upload' => $application->cv_upload,
                            'supporting_documents' => $application->supporting_documents,
                        ];
                    }

                    return [];
                })
                ->form([
                    Textarea::make('sdg_response')
                        ->label('What Are The Top 1 - 3 SDGs You Are Passionate About And How Do They Align To The Commonwealth Mandate?')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('challenge_response')
                        ->label('What Is The Biggest Challenge That The Youth Of Eswatini Are Facing And How Can You Advocate For Change At Commonwealth Level?')
                        ->required()
                        ->columnSpanFull(),

                    Toggle::make('representation_experience')
                        ->label('Have You Ever Represented The Youth At Local, National, Or International Level?')
                        ->required(),

                    Textarea::make('representation_details')
                        ->label('If yes, Briefly Describe How And Where You Represented The Youth')
                        ->visible(fn ($get) => $get('representation_experience') === true)
                        ->nullable()
                        ->columnSpanFull(),

                    Textarea::make('leadership_experience')
                        ->label('Briefly Describe Your Leadership / Advocacy Experience')
                        ->required()
                        ->columnSpanFull(),

                    Textarea::make('motivation')
                        ->label('Why Should You Be Chosen To Represent Eswatini At The Commonwealth Youth Council?')
                        ->required()
                        ->columnSpanFull(),

                    FileUpload::make('cv_upload')
                        ->label('Upload your CV Detailing Governance Level Experience')
                        ->directory('cvc-applications/cvs')
                        ->multiple()
                        ->reorderable()
                        ->maxSize(5120)
                        ->helperText('Add other document(s) to support your application e.g. C.V, etc. The document(s) should be less than 1 MB each and must be named with your name. NOTE: Wait For The File(s) To Finish Uploading And Have A Green Overlay Before Proceeding.')
                        ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->preserveFilenames()
                        ->required(),

                    FileUpload::make('supporting_documents')
                        ->label('Upload Supporting Documents (e.g. Certificates)')
                        ->directory('cvc-applications/supporting')
                        ->multiple()
                        ->helperText('This is a certificate from High School, Tertiary, or any other relevant trainings conducted. The document(s) should be less than 1 MB each and must be named with your name. NOTE: Wait For The File(s) To Finish Uploading And Have A Green Overlay Before Proceeding.')
                        ->reorderable()
                        ->maxSize(5120)
                        ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                        ->preserveFilenames()
                        ->nullable(),
                ])
                ->action(function (array $data): void {
                    $participant = Participant::where('user_id', auth()->id())->first();
                    $application = CYCApplication::where('participant_id', $participant->id)->first();

                    if ($application) {
                        $application->update($data);

                        Notification::make()
                            ->title('Application Details Updated!')
                            ->success()
                            ->send();
                    } else {
                        $newApp = CYCApplication::create(array_merge($data, [
                            'participant_id' => $participant->id,
                        ]));

                        Notification::make()
                            ->title('Application Received!')
                            ->success()
                            ->send();

                        $user = User::find(auth()->id());
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
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Textarea::make('sdg_response')
                ->label('What Are The Top 1 - 3 SDGs You Are Passionate About And How Do They Align To The Commonwealth Mandate?')
                ->required()
                ->columnSpanFull(),

            Forms\Components\Textarea::make('challenge_response')
                ->label('What Is The Biggest Challenge That The Youth Of Eswatini Are Facing And How Can You Advocate For Change At Commonwealth Level?')
                ->required()
                ->columnSpanFull(),

            Forms\Components\Toggle::make('representation_experience')
                ->label('Have You Ever Represented The Youth At Local, National, Or International Level?')
                ->required(),

            Forms\Components\Textarea::make('representation_details')
                ->label('If yes, Briefly Describe How And Where You Represented The Youth')
                ->visible(fn ($get) => $get('representation_experience') === true)
                ->nullable()
                ->columnSpanFull(),

            Forms\Components\Textarea::make('leadership_experience')
                ->label('Briefly Describe Your Leadership / Advocacy Experience')
                ->required()
                ->columnSpanFull(),

            Forms\Components\Textarea::make('motivation')
                ->label('Why Should You Be Chosen To Represent Eswatini At The Commonwealth Youth Council?')
                ->required()
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('cv_upload')
                ->label('Upload your CV Detailing Governance Level Experience')
                ->directory('cvc-applications/cvs')
                ->multiple()
                ->reorderable()
                ->maxSize(5120)
                ->helperText(str('Add Other Document(s) To Support Your Application e.g. C.V, etc. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->preserveFilenames()
                ->required(),

            Forms\Components\FileUpload::make('supporting_documents')
                ->label('Upload Supporting Documents (e.g. Certificates)')
                ->directory('cvc-applications/supporting')
                ->multiple()
                ->helperText(str('This Is A Certificate From High School, Tertiary, Or Any Other Relevant Trainings Conducted. The Document(s) Should Be Less Than 1 MB And Must Be Named With Your Name.'))
                ->reorderable()
                ->maxSize(5120)
                ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->preserveFilenames()
                ->nullable(),
        ];
    }

    protected function getFormModel(): CYCApplication|string|null
    {
        return $this->cyc_application;
    }

    public function submit()
    {
        $participant = Participant::where('user_id', Auth::id())->first();

        if (!$participant) {
            Notification::make()
                ->danger()
                ->title('Profile Incomplete')
                ->body('Please Complete Your Participant Profile Before Submitting An Application.')
                ->send();

            return;
        }

        $data = $this->form->getState();
        $data['participant_id'] = $participant->id;

        $this->cyc_application->fill($data)->save();

        $this->cyc_application = CYCApplication::where('participant_id', Auth::user()->participant->id)->first();

        Notification::make()
            ->success()
            ->title('Application Submitted')
            ->body('Your Commonwealth Youth Council application Has Been Saved Successfully.')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('submit')
                ->label('Submit Application')
                ->submit('submit')
                ->color('primary'),
        ];
    }
}
