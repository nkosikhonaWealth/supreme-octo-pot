<?php

namespace App\Filament\Admin\Pages;

use App\Models\TestEmail;
use App\Models\TestEmailLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Mail\ProgramUpdateMail;

class MessageTestPage extends Page implements Forms\Contracts\HasForms, HasTable
{
    use Forms\Concerns\InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $navigationGroup = 'Quality Control';
    protected static ?string $navigationLabel = 'Message Test';
    protected static string $view = 'filament.admin.pages.message-test-page';

    public string $currentTab = 'Test'; // Tabs: Test, TestLogs

    public ?string $testMessage = null;
    public array $formData = [];

    public function mount(): void
    {
        $this->form->fill([
            'testMessage' => 'This is a test message for the pilot email group.',
        ]);
    }

    public function updatedCurrentTab()
    {
        $this->resetTable();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Test Email')->tabs([
                Tabs\Tab::make('Test')->schema([
                    RichEditor::make('testMessage')
                        ->label('Message to Test Participants')
                        ->required(),
                    Forms\Components\Actions::make([
                        Action::make('sendTest')
                            ->label('Send Test Emails')
                            ->button()
                            ->color('info')
                            ->action(function () {
                                $message = $this->formData['testMessage'] ?? '';
                                
                                $this->sendMessages(
                                    $this->getTestParticipants()->get(),
                                    $message,
                                    'Test'
                                );
                            }),
                    ]),
                ]),
            ]),
        ])->statePath('formData');
    }

    


    protected function getExtraTestEmails(): array
    {
        return [
            ['email' => 'nkosikhonad52@gmail.com', 'name' => 'Siphosethu Hlubi'],
        ];
    }

    protected function getTableQuery()
    {
        return match ($this->currentTab) {
            'Test' => $this->getTestParticipants(),
            'TestLogs' => TestEmailLog::query()->latest(),
            default => $this->getTestParticipants(),
        };
    }

    protected function getTableColumns(): array
    {
        return match ($this->currentTab) {
            'Test' => [
                TextColumn::make('name')->label('Name')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
            ],
            'TestLogs' => [
                TextColumn::make('name')->label('Name')->sortable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sent' => 'success',
                        'Failed' => 'danger',
                    }),
                TextColumn::make('error')->label('Error')
                ->wrap(),
                TextColumn::make('created_at')->label('Sent At')->dateTime(),
            ],
            default => [],
        };
    }

    public function getTestParticipants()
    {
        return \App\Models\TestEmail::query();
    }

    protected function sendMessages($testEmails, string $message, string $type)
    {
        $delaySeconds = 0;
        $emailsFromDb = $testEmails->map(function ($test) {
            return [
                'email' => $test->email,
                'name' => $test->name ?? 'Test User',
            ];
        })->toArray();

        $allEmails = array_merge($emailsFromDb, $this->getExtraTestEmails());

        foreach ($allEmails as $test) {
        $email = $test['email'];
        $name = $test['name'] ?? 'Test User';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            TestEmailLog::create([
                'name' => $name,
                'email' => $email,
                'status' => 'Invalid email',
            ]);
            continue;
        }

        try {
            Mail::to($email)->queue(new ProgramUpdateMail($message,$name));

            TestEmailLog::create([
                'email' => $email,
                'name' => $name,
                'status' => 'Sent',
            ]);
        } catch (\Exception $e) {
            TestEmailLog::create([
                'name' => $name,
                'email' => $email,
                'status' => 'Failed',
                'error' => $e->getMessage(),
            ]);
        }
    }

    Notification::make()
        ->title("Test Emails Sent.")
        ->success()
        ->send();
}

    protected function logMessage($name, $email, $status, $error)
    {
        TestEmailLog::create([
            'name' => $name,
            'email' => $email,
            'status' => $status,
            'error' => $error,
        ]);
    }
}
