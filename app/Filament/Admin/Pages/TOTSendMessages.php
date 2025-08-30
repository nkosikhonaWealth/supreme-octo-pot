<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use App\Models\Participant;
use App\Models\ParticipantMessageLog;
use App\Mail\ProgramUpdateMail;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Bus;


class TOTSendMessages extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Send TOT Messages';
    protected static ?string $navigationGroup = 'Results';

    protected static string $view = 'filament.admin.pages.t-o-t-send-messages';
    protected static ?int $navigationSort = 5;

     public string $currentTab = 'Awarded'; // Awarded, Unsuccessful, Logs

    public ?string $awardedMessage = null;
    public ?string $unsuccessfulMessage = null;

    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill([
            'awardedMessage' => 'Congratulations! You have been awarded...',
            'unsuccessfulMessage' => 'Thank you for applying. Unfortunately...',
        ]);
    }

    public function updatedCurrentTab(): void
    {
        $this->resetTable();
    }

    protected function getTablePaginationPageSize(): int
    {
        return $this->currentTab === 'Logs' ? 50 : 10; // or any default for other tabs
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Tabs::make('Messages')->tabs([
                Tabs\Tab::make('Awarded')->schema([
                    RichEditor::make('awardedMessage')
                        ->label('Message to Awarded Applicants')
                        ->required(),
                    FileUpload::make('awardedDeliveredCsv')
                        ->label('Delivered Emails CSV')
                        ->directory('uploads')
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->helperText('Upload a CSV file with one email per line to skip those emails.')
                        ->nullable(),
                    Forms\Components\Actions::make([
                        Action::make('sendAwarded')
                            ->label('Send to Awarded')
                            ->button()
                            ->color('success')
                            ->action(fn() => $this->sendMessages(
                                $this->getAcceptedParticipants()->get(),
                                $this->formData['awardedMessage'] ?? '',
                                'Awarded',
                                $this->loadDeliveredEmailsFromCsv($this->formData['awardedDeliveredCsv'] ?? null)
                            )),
                    ]),
                ]),
                Tabs\Tab::make('Unsuccessful')->schema([
                    RichEditor::make('unsuccessfulMessage')
                        ->label('Message to Unsuccessful Applicants')
                        ->required(),
                    FileUpload::make('unsuccessfulDeliveredCsv')
                        ->label('Delivered Emails CSV')
                        ->directory('uploads')
                        ->acceptedFileTypes(['text/csv', 'text/plain'])
                        ->helperText('Upload a CSV file with one email per line to skip those emails.')
                        ->nullable(),
                    Forms\Components\Actions::make([
                        Action::make('sendUnsuccessful')
                            ->label('Send to Unsuccessful')
                            ->button()
                            ->color('danger')
                            ->action(fn() => $this->sendMessages(
                                $this->getUnsuccessfulParticipants()->get(),
                                $this->formData['unsuccessfulMessage'] ?? '',
                                'Unsuccessful',
                                $this->loadDeliveredEmailsFromCsv($this->formData['unsuccessfulDeliveredCsv'] ?? null)
                            )),
                    ]),
                ]),
            ]),
        ])->statePath('formData');
    }

    protected function getTableQuery()
    {
        return match ($this->currentTab) {
            'Awarded' => $this->getAcceptedParticipants(),
            'Unsuccessful' => $this->getUnsuccessfulParticipants(),
            'Logs' => ParticipantMessageLog::query()->with('participant')->latest(),
            default => $this->getAcceptedParticipants(),
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
                TextColumn::make('result')->label('Result')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Accepted' => 'success',
                        'Awarded' => 'info',
                        'Unsuccessful' => 'danger',
                    }),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sent' => 'secondary',
                        'Failed' => 'danger',
                        'Queued' => 'warning',
                        'Opened' => 'success',
                        'Delivered' => 'info',
                    }),
                TextColumn::make('error_message')->label('Message')->wrap(),
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
                        'Queued' => 'Queued',
                    ])
                    ->placeholder('All Statuses'),

                Tables\Filters\SelectFilter::make('result')
                    ->label('Result')
                    ->options([
                        'Awarded' => 'Awarded',
                        'Unsuccessful' => 'Unsuccessful',
                    ])
                    ->placeholder('All Results'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Sent From'),
                        Forms\Components\DatePicker::make('created_until')->label('Sent Until'),
                    ])
                    ->query(fn ($query, array $data) =>
                        $query
                            ->when($data['created_from'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['created_until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
                    ),
            ],
            default => [],
        };
    }

    protected function getTableBulkActions(): array
    {
        return match ($this->currentTab) {
            'Awarded', 'Unsuccessful' => [
                Tables\Actions\BulkAction::make('exportApplicants')
                    ->label('Export Applicants')
                    ->action(function (Collection $records, array $data) {
                        $filename = 'tot-applicants-export-' . now()->format('Y-m-d-H-i-s') . '.csv';
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => "attachment; filename=\"$filename\"",
                        ];
                        
                        $callback = function() use ($records) {
                            $file = fopen('php://output', 'w');
                            
                            // Write CSV headers
                            fputcsv($file, [
                                'Name', 'Phone', 'Gender', 'Region', 'Inkhundla'
                            ]);
                            
                            // Write data rows
                            foreach ($records as $record) {
                                fputcsv($file, [
                                    $record->user->name ?? '',              
                                    $record->phone ?? '',         
                                    $record->gender ?? '',        
                                    $record->region ?? '',        
                                    $record->inkhundla ?? '',      
                                ]);
                            }
                            
                            fclose($file);
                        };
                        
                        return new StreamedResponse($callback, 200, $headers);
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
            ],
            'Logs' => [
                Tables\Actions\BulkAction::make('exportLogs')
                    ->label('Export Logs')
                    ->action(function (Collection $records) {
                        $filename = 'tot-message-logs-' . now()->format('Y-m-d-H-i-s') . '.csv';
                        $headers = [
                            'Content-Type' => 'text/csv',
                            'Content-Disposition' => "attachment; filename=\"$filename\"",
                        ];

                        $callback = function () use ($records) {
                            $file = fopen('php://output', 'w');

                            // CSV Headers
                            fputcsv($file, [
                                'Name', 'Email', 'Phone', 'Region', 'Result', 'Status', 'Message'
                            ]);

                            // Rows
                            foreach ($records as $record) {
                                fputcsv($file, [
                                    $record->participant->user->name ?? '',
                                    $record->participant->user->email ?? '',
                                    $record->participant->phone ?? '',
                                    $record->participant->region ?? '',
                                    $record->result ?? '',
                                    $record->status ?? '',
                                    $record->error_message ?? '',
                                ]);
                            }

                            fclose($file);
                        };

                        return new \Symfony\Component\HttpFoundation\StreamedResponse($callback, 200, $headers);
                    })
                    ->deselectRecordsAfterCompletion()
                    ->requiresConfirmation(),
            ],
            default => [],
        };
    }

    public function getAwardedParticipants()
    {
        return Participant::with(['user', 'TOT'])
        ->whereHas('TOT.participant_result', fn ($q) => $q->where('status', 'Awarded'));
    }

    public function getAcceptedParticipants()
    {
        return Participant::with(['user', 'TOT'])
        ->whereHas('TOT.participant_result', fn ($q) => $q->where('status', 'Accepted'));
    }

    public function getUnsuccessfulParticipants()
    {
        return Participant::with(['user', 'TOT'])
        ->whereHas('messageLog', function ($q) {
            $q->where('status', 'Queued')
              ->where('result', 'Unsuccessful');
        });
    }

    protected function sendMessages($participants, string $message, string $type, array $deliveredEmails = [])
    {
        $deliveredEmails = array_map('strtolower', $deliveredEmails);

        $dailyLimit = 280;
        $emailsSentToday = $this->getTodaySentEmailCount();
        $sendTime = now();

        foreach ($participants as $participant) {
            $user = $participant->user;
            $email = strtolower($user->email);
            $name = $user->name;

            // Skip if already delivered (from uploaded CSV)
            if (in_array($email, $deliveredEmails)) {
                $this->logMessage($participant->id, $email, $type, 'Skipped', 'Already delivered');
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->logMessage($participant->id, $email, $type, 'Failed', 'Invalid email');
                continue;
            }

            try {
                if ($emailsSentToday < $dailyLimit) {
                    Mail::to($email)->queue(new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, $type, 'Sent', null);
                    $emailsSentToday++;
                } else {
                    Mail::to($email)
                        ->later($sendTime->copy()->addDay(), new ProgramUpdateMail($message, $name));
                    $this->logMessage($participant->id, $email, $type, 'Queued', 'Queued for tomorrow');
                }

            } catch (\Exception $e) {
                $this->logMessage($participant->id, $email, $type, 'Failed', $e->getMessage());
            }
        }

        Notification::make()
            ->title("Messages Processed For {$type} Group")
            ->success()
            ->send();
    }

    protected function loadDeliveredEmailsFromCsv($filePath): array
    {
        // Handle null or array
        if (is_array($filePath)) {
            $filePath = $filePath[0] ?? null;
        }

        if (!$filePath || !file_exists(storage_path('app/' . $filePath))) {
            return [];
        }

        $emails = [];
        if (($handle = fopen(storage_path('app/' . $filePath), 'r')) !== false) {
            while (($row = fgetcsv($handle)) !== false) {
                $emails[] = strtolower(trim($row[0]));
            }
            fclose($handle);
        }
        return $emails;
    }

    protected function getTodaySentEmailCount()
    {
        return ParticipantMessageLog::whereDate('created_at', now()->toDateString())
            ->where('status', 'Sent')
            ->count();
    }

    protected function logMessage($participantId, $email, $result, $status, $errorMessage)
    {
        ParticipantMessageLog::create([
            'participant_id' => $participantId,
            'email' => $email,
            'result' => $result,
            'status' => $status,
            'error_message' => $errorMessage,
        ]);
    }
}
