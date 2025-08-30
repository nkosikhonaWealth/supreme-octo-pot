<?php

namespace App\Livewire;

use App\Models\CustomerContact;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ContactMessageComponent extends Component
{
    #[Rule('required|min:3')]
    public $name;
    #[Rule('required|email')]
    public $email;
    #[Rule('required')]
    public $phone;
    #[Rule('required')]
    public $message;

    public function render()
    {
        return view('livewire.contact-message-component');
    }

    public function addMessage()
    {
        dd($this);
        $this->validate();
        CustomerContact::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ]);
    }
}
