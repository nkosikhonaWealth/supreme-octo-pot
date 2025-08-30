<?php

namespace App\Livewire;

use Livewire\Component;

class BusinessProfileComponent extends Component
{
    public function render()
    {
        return view('livewire.business-profile-component')->layout('layouts.home');
    }
}
