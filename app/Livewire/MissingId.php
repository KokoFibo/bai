<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Yfrekappresensi;

class MissingId extends Component
{
    public $array =[];
    public function render()
    {
        $rekap = Yfrekappresensi::distinct('user_id')->get('user_id');
        $array = [] ;
                foreach ($rekap as $r) {
                    $karyawan = Karyawan::where('id_karyawan' , $r->user_id)->first();
                    if ($karyawan === null) {
                $this->array[] = [
                    'Karyawan_id' => $r->user_id,
                ];
            }
        }
        return view('livewire.missing-id');
    }
}
