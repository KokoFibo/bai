<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfpresensi;

class Removepresensiwr extends Component
{
    public function remove () {
        Yfpresensi::truncate();
        $this->dispatch( 'success', message: 'Presensi Buffer Sudah di removed' );

    }
    public function render()
    {
        return view('livewire.removepresensiwr');
    }
}
