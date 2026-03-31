<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;

class Rubahid extends Component
{
    public $idLama, $idBaru;

    public function rubah_id()
    {
        // dd($this->idBaru, $this->idLama);

        $karyawan = Karyawan::where('id_karyawan', $this->idLama)->first();
        $user = User::where('username', $this->idLama)->first();

        $exist = Karyawan::where('id_karyawan', $this->idBaru)->first();

        // if ($exist) {
        //     dd($exist);
        // }

        if ($user && $karyawan && !$exist) {


            $karyawan->id_karyawan = $this->idBaru;
            $user->username = $this->idBaru;
            $karyawan->save();
            $user->save();
            $this->dispatch(
                'message',
                type: 'success',
                title: 'ID Karyawan dan user Sudah di Update',
                position: 'center'
            );
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'ID Karyawan tidak ditemukan',
                position: 'center'
            );
        }
    }
    public function render()
    {
        return view('livewire.rubahid');
    }
}
