<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestBrevoEmail extends Command
{
    protected $signature = 'test:brevo-email {email}';
    protected $description = 'Send a test email through Brevo SMTP';

    public function handle()
    {
        $to = $this->argument('email');

        try {
            Mail::raw('This is a test email from ENYCYDP Brevo setup.', function ($message) use ($to) {
                $message->to($to)
                        ->subject('Test Email from ENYCYDP');
            });

            $this->info("Test email sent to {$to}!");
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
