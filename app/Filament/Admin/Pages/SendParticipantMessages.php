<?php

namespace App\Filament\Admin\Pages;

use App\Models\Participant;
use App\Models\ParticipantMessageLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\RichEditor;
use App\Mail\ProgramUpdateMail;
use Filament\Tables\Filters\DateFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class SendParticipantMessages extends Page implements Forms\Contracts\HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Results';
    protected static string $view = 'filament.admin.pages.send-participant-messages';
    protected static ?int $navigationSort = 5;

    public string $currentTab = 'Awarded'; // Tabs: Awarded, Unsuccessful, Logs

    public ?string $awardedMessage = null;
    public ?string $unsuccessfulMessage = null;
    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill([
            'awardedMessage' => 'Congratulations! You have been awarded...',
            'unsuccessfulMessage' => 'Thank you for participating. Unfortunately...',
        ]);
    }

    public function updatedCurrentTab()
    {
        $this->resetTable();
    }
    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Messages')->tabs([
                Tabs\Tab::make('Awarded')->schema([
                    RichEditor::make('awardedMessage')
                        ->label('Message to Awarded Participants')
                        ->required(),
                    Forms\Components\Actions::make([
                        Action::make('sendAwarded')
                            ->label('Send to Awarded')
                            ->button()
                            ->color('success')
                            ->action(function () {
                                $message = $this->formData['awardedMessage'] ?? '';
                                
                                $this->sendMessages(
                                    $this->getAwardedParticipants()->get(),
                                    $message,
                                    'Awarded'
                                );
                            }),
                    ]),
                ]),
                Tabs\Tab::make('Unsuccessful')->schema([
                    RichEditor::make('unsuccessfulMessage')
                        ->label('Message to Unsuccessful Participants')
                        ->required(),
                    Forms\Components\Actions::make([
                        Action::make('sendUnsuccessful')
                            ->label('Send to Unsuccessful')
                            ->button()
                            ->color('danger')
                            ->action(function () {
                                $message = $this->formData['unsuccessfulMessage'] ?? '';
                                
                                $this->sendMessages(
                                    $this->getUnsuccessfulParticipants()->get(),
                                    $message,
                                    'Unsuccessful'
                                );
                            }),
                    ]),
                ]),
            ]),
        ])->statePath('formData');
    }

    protected function getTableQuery()
    {
        return match ($this->currentTab) {
            'Awarded' => $this->getAwardedParticipants(),
            'Unsuccessful' => $this->getUnsuccessfulParticipants(),
            'Logs' => ParticipantMessageLog::query()->with('participant')->latest(),
            default => $this->getAwardedParticipants(),
        };
    }

    protected function getTableColumns(): array
    {
        return match ($this->currentTab) {
            'Awarded', 'Unsuccessful' => [
                TextColumn::make('user.name')->label('Name')->searchable()->sortable(),
                TextColumn::make('region')->label('Region')->searchable()->sortable(),
                TextColumn::make('phone')->label('Phone')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable()->sortable(),
            ],
            'Logs' => [
                TextColumn::make('participant.user.name')->label('Name')->sortable(),
                TextColumn::make('participant.user.email')->label('Email'),
                TextColumn::make('participant.phone')->label('Phone'),
                TextColumn::make('participant.region')->label('Region'),
                TextColumn::make('result')->label('result')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Awarded' => 'success',
                        'Unsuccessful' => 'danger',
                    }),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sent' => 'secondary',
                        'Failed' => 'danger',
                        'Delivered' => 'info',
                        'Opened' => 'success',
                    }),
                TextColumn::make('error_message')->label('Message')
                ->wrap(),
                TextColumn::make('updated_at')->label('Time')->dateTime(),
            ],
            default => [],
        };
    }

    protected function getTableFilters(): array
    {
        return match ($this->currentTab) {
            'Logs' => [
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Sent' => 'Sent',
                        'Failed' => 'Failed',
                    ])
                    ->placeholder('All Statuses'),
                    
                Tables\Filters\SelectFilter::make('result')
                    ->label('Result Type')
                    ->options([
                        'Awarded' => 'Awarded',
                        'Unsuccessful' => 'Unsuccessful',
                    ])
                    ->placeholder('All Results'),

                    Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Sent From'),
                        DatePicker::make('created_until')->label('Sent Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ],
            default => [], 
        };
    }

    protected function getTableBulkActions(): array
    {
        return match ($this->currentTab) {
            'Logs' => [
                BulkAction::make('resendEmails')
                    ->label('Resend Emails')
                    ->form([
                        RichEditor::make('message')
                            ->label('Message To Send')
                            ->required(),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $delaySeconds = 0;

                        foreach ($records as $record) {
                            $participant = $record->participant;
                            $user = $participant->user;
                            $email = $user->email ?? $record->email;
                            $name = $user->name ?? 'Participant';
                            $result = $record->result;

                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                ParticipantMessageLog::create([
                                    'participant_id' => $participant->id,
                                    'email' => $email,
                                    'result' => $result,
                                    'status' => 'Failed',
                                    'error_message' => 'Invalid email format',
                                ]);
                                continue;
                            }

                            try {
                                Mail::to($email)
                                    ->later(now()->addSeconds($delaySeconds), new ProgramUpdateMail($data['message'], $name));

                                ParticipantMessageLog::create([
                                    'participant_id' => $participant->id,
                                    'email' => $email,
                                    'result' => $result,
                                    'status' => 'Sent',
                                    'error_message' => null,
                                ]);

                                $delaySeconds += 20;
                            } catch (\Exception $e) {
                                ParticipantMessageLog::create([
                                    'participant_id' => $participant->id,
                                    'email' => $email,
                                    'result' => $result,
                                    'status' => 'Failed',
                                    'error_message' => $e->getMessage(),
                                ]);
                            }
                        }

                        Notification::make()
                            ->title('Emails Re-Sent and Logged.')
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
            ],
            default => [],
        };
    }

    protected function getValidationEmails(): array
    {
        return [
            ['email' => 'ayandam@snyc.org.sz', 'name' => 'Ayanda Manyatsi'],
            ['email' => 'info@snyc.org.sz', 'name' => 'ENYC Reception'],
            ['email' => 'nkosikhonad52@gmail.com', 'name' => 'Siphosethu Hlubi'],
        ];
    }

    public function getAwardedParticipants()
    {
        return Participant::with(['user', 'TVET'])
            ->whereHas('TVET.participant_result', fn ($q) => $q->where('status', 'Awarded'));
    }

    public function getUnsuccessfulParticipants()
    {
        return Participant::with(['user', 'TVET'])
            ->whereHas('TVET.participant_result', fn ($q) => $q->whereNull('status'));
    }

    protected function sendMessages($participants, string $message, string $type)
    {
        $dailyLimit = 300;
        $emailsSentToday = $this->getTodaySentEmailCount(); 
        $sendTime = now();
        
        foreach ($participants as $participant) {
            $user = $participant->user;
            $email = $user->email;
            $name = $user->name;

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $status = 'Failed';
                $result = $this->getResultStatus($participant);
                $this->logMessage($participant->id, $email, $result, $status, 'Invalid email');
                continue;
            }

            try {
                $result = $this->getResultStatus($participant);

                if ($emailsSentToday < $dailyLimit) {
                    Mail::to($email)
                        ->queue(new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, $result, 'Sent', null);
                    $emailsSentToday++;
                } else {
                    Mail::to($email)
                        ->later($sendTime->copy()->addDay(), new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, $result, 'Queued', 'Queued For Tomorrow');
                }

            } catch (\Exception $e) {
                $this->logMessage($participant->id, $email, $result ?? 'Unknown', 'Failed', $e->getMessage());
            }
        }

        Notification::make()
            ->title("Messages Processed For {$type} Group")
            ->success()
            ->send();
    }

    protected function getTodaySentEmailCount()
    {
        return ParticipantMessageLog::whereDate('created_at', now()->toDateString())
                         ->where('status', 'Sent')
                         ->count();
    }

    protected function logMessage($participantId, $email, $result, $status,$error_message)
    {
        ParticipantMessageLog::create([
            'participant_id' => $participantId,
            'email' => $email,
            'result' => $result,
            'status' => $status,
            'error_message' => $error_message,
        ]);
    }

    protected function getResultStatus($participant): string
    {
        return $participant->TVET->participant_result->status === 'Awarded'
            ? 'Awarded'
            : 'Unsuccessful';
    }
}
