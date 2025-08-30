<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Participant;

class ServicesComponent extends Component
{
    public function render()
    {
        $hhohhoF = Participant::where('region','Hhohho')->where('gender','Female')->count();
        $hhohhoM = Participant::where('region','Hhohho')->where('gender','Male')->count();
        $manziniF = Participant::where('region','Manzini')->where('gender','Female')->count();
        $manziniM = Participant::where('region','Manzini')->where('gender','Male')->count();
        $shiselweniF = Participant::where('region','Shiselweni')->where('gender','Female')->count();
        $shiselweniM = Participant::where('region','Shiselweni')->where('gender','Male')->count();
        $lubomboF = Participant::where('region','Lubombo')->where('gender','Female')->count();
        $lubomboM = Participant::where('region','Lubombo')->where('gender','Male')->count();
        $participants = Participant::all()->count();

        return view('livewire.services-component',['hhohhoF'=>$hhohhoF,'hhohhoM'=>$hhohhoM,'manziniF'=>$manziniF,'manziniM'=>$manziniM,'shiselweniF'=>$shiselweniF,'shiselweniM'=>$shiselweniM,'lubomboF'=>$lubomboF,'lubomboM'=>$lubomboM,'participants'=>$participants])->layout('layouts.home');
    }
}
