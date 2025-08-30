<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Participant;
use App\Models\TVET;
use App\Models\User;
use App\Models\Application;
use Filament\Notifications\Notification;
use App\Models\Entrepreneurship;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
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
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationRecievedMail;
use App\Mail\ApplicationProcessedMail;

class ParticipantView extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Participant Management';
    protected static ?string $navigationLabel = 'Participant Applications';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.admin.pages.participant-view';

    public array $participantIds = [];
    public array $participants_array = [];
    public $participant; 
    public $participants; 
    public $currentIndex = 0;

    public function mount()
    {
        // Get participantIds from the query string
        $participantIdsString = request()->query('participantIds');

        // Convert to an array if present
        if ($participantIdsString) {
            $this->participantIds = explode(',', $participantIdsString);
            $this->participants = Participant::whereIn('id', $this->participantIds)->get();
        }
        else
        {
            $this->participants = Participant::all();  
            $this->participants_array =  Participant::all()->pluck('id')->toArray();
        }

        

    }

    private function loadParticipant()
    {
        if (!empty($this->participantIds)) {

            $this->participant = Participant::find($this->participantIds[$this->currentIndex]);
        }
        else
        {
            $this->participant = Participant::find($this->participants_array[$this->currentIndex]);
        }
    }


    protected function getActions(): array
    {
        return [
            Action::make('process_application')
            ->label('Process')
            ->modalHeading('ENYC TVET Support Programme Application')
            ->fillForm(function (): array {
                if ($this->participant->pathway === 'TVET') {
                    $tvet = $this->participant->TVET()->first();
                    if (!$tvet) return [];

                    $application = Application::where('t_v_e_t_id', $tvet->id)->first();
                    return $application ? [
                        'status' => $application->status,
                        'recommendation' => $application->recommendation,
                        'organisation' => $application->organisation,
                        'shortlist' => $application->shortlist,
                        'comment' => $application->comment,
                        'notify' => $application->notify,
                        'notified' => $application->notified,
                    ] : [];
                }
                return [];
            })
            ->form([
                Select::make('status')
                    ->label('Application Status')
                    ->options([
                        'Approved' => 'Approved',
                        'Pending' => 'Pending',
                        'Referred' => 'Referred',
                        'Not Yet' => 'Not Yet'
                    ])
                    ->required(),
                Textarea::make('recommendation')
                    ->label('Recommendation(s) For The Applicant')
                    ->rows(3),
                Textarea::make('comment')
                    ->label('Comments On The Application')
                    ->rows(3),
                Select::make('organisation')
                    ->label('Organisation The Applicant Is Referred To')
                    ->options([
                        'None' => 'None',
                        'Youth Enterprise Fund' => 'Youth Enterprise Fund',
                        'Other' => 'Other'
                    ])
                    ->placeholder("Select Organisation"),
                Toggle::make('shortlist')
                    ->label('Shortlist Applicant')
                    ->required(),
            ])
            ->action(function (array $data) {
                if ($this->participant->pathway !== 'TVET') {
                    Notification::make()
                        ->title('Invalid Pathway')
                        ->danger()
                        ->body('This Participant Is Not In The TVET Pathway')
                        ->send();
                    return;
                }

                $tvet = $this->participant->TVET()->first();
                if (!$tvet) {
                    Notification::make()
                        ->title('TVET Record Not Found')
                        ->danger()
                        ->send();
                    return;
                }

                $application = Application::updateOrCreate(
                    ['t_v_e_t_id' => $tvet->id],
                    $data
                );

                Notification::make()
                    ->title($application->wasRecentlyCreated ? 'Application Added!' : 'Application Processed!')
                    ->success()
                    ->send();

                $user = $this->participant->user()->first();
                if (!$user || !$user->email) {
                    Notification::make()
                        ->title('User Email Not Found')
                        ->warning()
                        ->send();
                    return;
                }

                try {
                    Mail::to($user->email)->send(new ApplicationProcessedMail($this->participant, $user, $application));
                    Notification::make()
                        ->title('Email Sent Successfully!')
                        ->success()
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Failed to Send Email')
                        ->danger()
                        ->body($e->getMessage())
                        ->send();
                }
            }),
            Action::make('previous')
                ->label('<')
                ->action(function () {
                    if ($this->currentIndex > 0) {
                        $this->currentIndex--;
                        $this->loadParticipant();
                    } else {
                        Notification::make()
                            ->title('This Is The First Application In Your List')
                            ->warning()
                            ->send();
                    }
                }),
            Action::make('next')
                ->label('>')
                ->action(function () {
                    if ($this->currentIndex < count($this->participantIds) - 1) {
                        $this->currentIndex++;
                        $this->loadParticipant();
                    } 
                    elseif ($this->currentIndex < count($this->participants_array) - 1)
                    {
                        $this->currentIndex++;
                        $this->loadParticipant();
                    }
                    else {
                        Notification::make()
                            ->title('This Is The Last Application In Your List')
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }

    public function getHeading(): string
    {
        return 'ENYC T-VET Support Programme - Applications';
    }

    protected function getViewData(): array
    {
        $this->loadParticipant();
        
        if (!$this->participant) {
            $this->currentIndex = 0;
            $this->loadParticipant();
        }

        return [
            'participant' => $this->participant,
        ];
    }

    public function nextParticipant()
    {
        if ($this->currentIndex < count($this->participantIds) - 1) {
                $this->currentIndex++;
                $this->loadParticipant();
            }
            elseif ($this->currentIndex < count($this->participants_array) - 1)
            {
                $this->currentIndex++;
                $this->loadParticipant();
            } else {
                Notification::make()
                    ->title('This Is The Last Application In Your List')
                    ->warning()
                    ->send();
            }
    }

    public function previousParticipant()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->loadParticipant();
        } else {
            Notification::make()
                ->title('This Is The First Application In Your List')
                ->warning()
                ->send();
        }
    }    
}
