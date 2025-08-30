<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactComponent extends Component
{
    public $name;
    public $email;
    public $contact_message;

    public function sendEmail()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'contact_message' => 'required|string',
        ]);

        // Send the email
        Mail::to('iyly@bizgrowsz.online')->send(new ContactMail(
            $this->name,
            $this->email,
            $this->contact_message,
        ));

        // Clear input fields after submission
        $this->reset();

        // Flash success message
        session()->flash('success', 'Your message has been sent successfully!');
    }
    
    public function render()
    {
        return view('livewire.contact-component')->layout('layouts.home');
    }
}
