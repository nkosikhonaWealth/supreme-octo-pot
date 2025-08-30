<?php

namespace App\Filament\Admin\Pages;

use App\Models\YouthMessage;
use App\Models\YouthMessageLog;
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

class SendYouthMessages extends Page implements Forms\Contracts\HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Youth Messaging';
    protected static string $view = 'filament.admin.pages.send-youth-messages';
    protected static ?int $navigationSort = 2;

    public string $currentTab = 'Awarded'; // Tabs: Awarded, Unsuccessful, Logs

    public ?string $awardedMessage = null;
    public ?string $unsuccessfulMessage = null;
    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill([
            'awardedMessage' => 'Please fill in this form...',
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
                        ->label('Message to Youth Beneficiaries')
                        ->required(),
                    Forms\Components\Actions::make([
                        Action::make('sendAwarded')
                            ->label('Send to Youth Beneficiaries')
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
            ]),
        ])->statePath('formData');
    }

    protected function getTableQuery()
    {
        return match ($this->currentTab) {
            'Awarded' => $this->getAwardedParticipants(),
            'Logs' => YouthMessageLog::query()->with('youth_message')->latest(),
            default => $this->getAwardedParticipants(),
        };
    }

    protected function getTableColumns(): array
    {
        return match ($this->currentTab) {
            'Awarded' => [
                TextColumn::make('user.name')->label('Name')->searchable()->sortable(),
                TextColumn::make('region')->label('Region')->searchable()->sortable(),
                TextColumn::make('phone')->label('Phone')->searchable()->sortable(),
                TextColumn::make('user.email')->label('Email')->searchable()->sortable(),
            ],
            'Logs' => [
                TextColumn::make('youth_message.user.name')->label('Name')->sortable(),
                TextColumn::make('youth_message.user.email')->label('Email'),
                TextColumn::make('youth_message.phone')->label('Phone'),
                TextColumn::make('youth_message.region')->label('Region'),
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

    public function getAwardedParticipants()
    {
        return YouthMessage::with(['user']);
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
                $this->logMessage($participant->id, $email, $status, 'Invalid email');
                continue;
            }

            try {
                $result = $this->getResultStatus($participant);

                if ($emailsSentToday < $dailyLimit) {
                    Mail::to($email)
                        ->queue(new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, 'Sent', null);
                    $emailsSentToday++;
                } else {
                    Mail::to($email)
                        ->later($sendTime->copy()->addDay(), new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, 'Queued', 'Queued For Tomorrow');
                }

            } catch (\Exception $e) {
                $this->logMessage($participant->id, $email, 'Failed', $e->getMessage());
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
                         ->count() + YouthMessageLog::whereDate('created_at', now()->toDateString())
                         ->where('status', 'Sent')
                         ->count();
    }

    protected function logMessage($participantId, $email, $status,$error_message)
    {
        YouthMessageLog::create([
            'youth_message_id' => $participantId,
            'email' => $email,
            'status' => $status,
            'error_message' => $error_message,
        ]);
    }
}
