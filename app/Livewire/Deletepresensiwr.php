<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Deletepresensiwr extends Component
{
    public $tanggal;
    public function delete () {
       $data = DB::table('rekap_presensis')
       ->whereDate('date', $this->tanggal)
       ->get();
       if($data->isEmpty()){

        $this->dispatch('error', message: 'Data presensi tidak ditemukan');

        } else {
            DB::table('rekap_presensis')
            ->whereDate('date', $this->tanggal)->delete();
        $this->dispatch('success', message: 'Data presensi sudah di delete');

       }
    }
    public function exit () {
        $this->reset();
        return redirect()->to('/presensi');
        // or sepertoi dibawah juga bisa
        // return redirect('/presensi');

    }
    public function render()
    {
        return view('livewire.deletepresensiwr')
        ->layout('layouts.appeloe');
    }
}
