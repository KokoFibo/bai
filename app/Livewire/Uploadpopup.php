<?php

namespace App\Livewire;

use Livewire\Component;

class Uploadpopup extends Component
{
    public $showPopup = true;

    public function closePopup()
    {
        $this->showPopup = false;
    }


    public function render()
    {
        return view('livewire.uploadpopup');
    }
}
