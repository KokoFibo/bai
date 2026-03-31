<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;

class Gajibpjs extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = Karyawan::where('gaji_bpjs', '>', 0)->where('ptkp', null)->orderBy('ptkp', 'desc')->paginate(10);
        return view('livewire.gajibpjs', [
            'data' => $data
        ]);
    }
}
