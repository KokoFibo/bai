<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Gantipassworddeveloper extends Component
{
    public $passBaru;
    public function proses()
    {
        $data = User::whereIn('username', [100000, 80000, 70000, 60000, 50000])->get();
        if ($this->passBaru != '') {


            foreach ($data as $d) {
                $rubahdata = User::find($d->id);
                $rubahdata->password = Hash::make($this->passBaru);
                $rubahdata->save();
            }
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Placement Berhasil dirubah',
            );
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Password belum diisi',
            );
        }
    }

    public function render()
    {
        return view('livewire.gantipassworddeveloper');
    }
}
