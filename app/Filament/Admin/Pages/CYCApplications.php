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

class CYCApplications extends Page
{
    protected static string $view = 'filament.admin.pages.c-y-c-applications';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $title = 'CYC Applications';
    protected static ?string $navigationLabel = 'CYC Applications';
    protected static ?string $navigationGroup = 'CYC Applications Management';
    protected static ?int $navigationSort = 4;

    public array $participantIds = [];
    public array $participants_array = [];
    public $participant;
    public $participants;
    public $currentIndex = 0;

    public function mount()
    {
        // Get participant IDs from query string if provided
        $participantIdsString = request()->query('participantIds');

        if ($participantIdsString) {
            $this->participantIds = explode(',', $participantIdsString);
            $this->participants = Participant::whereIn('id', $this->participantIds)->get();
        } else {
            // Default: Load participants who have submitted CYC applications
            $this->participants = Participant::has('CYC')->get();
            $this->participants_array = $this->participants->pluck('id')->toArray();
        }
    }

    private function loadParticipant()
    {
        if (!empty($this->participantIds)) {
            $this->participant = Participant::find($this->participantIds[$this->currentIndex]);
        } else {
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
        $limit = !empty($this->participantIds) ? count($this->participantIds) : count($this->participants_array);

        if ($this->currentIndex < $limit - 1) {
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
