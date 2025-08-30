<?php

namespace App\Livewire;

use Livewire\Component;

class ApplicationGuideComponent extends Component
{
    public function render()
    {
        return view('livewire.application-guide-component')->layout('layouts.home');
    }
}
