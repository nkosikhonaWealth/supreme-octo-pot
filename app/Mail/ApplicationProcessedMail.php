<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Participant;
use App\Models\User;
use App\Models\Application;

class ApplicationProcessedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $participant;
    public $application;

    /**
     * Create a new message instance.
     */
    public function __construct(Participant $participant, User $user, Application $application)
    {
        $this->user = $user;
        $this->participant = $participant;
        $this->application = $application;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Eswatini National Youth Council T-VET Support Programme Application Processed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_processed',
            with: [
                'user' => $this->user,
                'participant' => $this->participant,
                'application' => $this->application,  
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
