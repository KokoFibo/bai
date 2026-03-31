<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Mobileinformation;

class UserInformation extends Component
{
    public function render()
    {
        $data = Mobileinformation::orderBy('date', 'desc')->get();
        return view('livewire.user-information', compact('data'))->layout('layouts.polos');
    }
}
