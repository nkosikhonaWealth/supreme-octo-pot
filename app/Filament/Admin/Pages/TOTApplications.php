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

class TOTApplications extends Page
{
    protected static string $view = 'filament.admin.pages.t-o-t-applications';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $title = 'TOT Applications';
    protected static ?string $navigationLabel = 'TOT Applications';
    protected static ?string $navigationGroup = 'TOT Applications Management';
    protected static ?int $navigationSort = 3;
    
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
            $this->participants = Participant::has('TOT')->get();
            $this->participants_array = Participant::has('TOT')->pluck('id')->toArray();
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
