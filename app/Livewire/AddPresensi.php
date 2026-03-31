<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Yfrekappresensi;

class AddPresensi extends Component
{
    public $karyawan_id, $user_id, $date;
    public $nama;

    public function updatedUserId()
    {

        $data = Karyawan::where('id_karyawan', $this->user_id)->first();
        if ($data != null) {
            $this->nama = $data->nama;
            $this->karyawan_id = $data->id;
        }
    }

    public function save()
    {
        $data = new Yfrekappresensi;
        $data->karyawan_id = $this->karyawan_id;
        $data->user_id = $this->user_id;
        $data->date = $this->date;
        $data->save();
        $this->reset();
        $this->dispatch('success', message: 'Informasi berhasil di Update');
    }

    public function render()
    {
        return view('livewire.add-presensi');
    }
}
