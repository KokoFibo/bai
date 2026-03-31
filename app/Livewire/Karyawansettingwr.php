<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class Karyawansettingwr extends Component
{
    public $search;
    public $id;
    public $username;
    public $tanggal_lahir;

    public function resetPassword()
    {
        if ($this->username != null) {
            $user = User::where('username', $this->username)->first();
            if ($user == null) {
                // $this->dispatch('error', message: 'ID: ' . $this->username . ' tidak terdapat pada table USER');
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'ID: ' . $this->username . ' tidak terdapat pada table USER',
                );
                return;
            }
            $data = User::find($user->id);

            if ($data) {
                $data->password = Hash::make(generatePassword($this->tanggal_lahir));
                $data->save();
                // $this->dispatch('success', message: 'Password berhasil di reset');
                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'ID: ' . $this->username . ' Password berhasil di reset',
                );
            }
        }
    }
    public function render()
    {
        if ($this->search) {
            $search = '%' . trim($this->search) . '%';
            $data = Karyawan::where('id_karyawan', $this->search)
                ->orWhere('nama', $this->search)
                ->first();
            if ($data != null) {
                $user = User::where('username', $data->id_karyawan)->first();
                $this->username = $data->id_karyawan;
                $this->tanggal_lahir = $data->tanggal_lahir;
            } else {
                $data = null;
                $this->id = null;
            }
        } else {
            $data = null;
            $this->id = null;
        }
        return view('livewire.karyawansettingwr', compact(['data']));
    }
}
