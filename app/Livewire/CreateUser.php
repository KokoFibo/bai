<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    public $id_karyawan;
    public $name;
    public $email;
    public $role;
    public $password;
    public $is_create = true;

    public function resetPassword()
    {
        $user = User::where('username', $this->id_karyawan)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            $this->dispatch(
                'message',
                type: 'success',
                title: 'Password berhasil di reset',
                position: 'center'
            );
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'User tidak ditemukan',
                position: 'center'
            );
        }
    }

    public function create()
    {
        User::create([
            'name' => titleCase($this->name),
            'email' =>  trim($this->email),
            'username' => trim($this->id_karyawan),
            'role' => trim($this->role),
            'password' => Hash::make($this->password),
        ]);
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Created',
            position: 'center'
        );
    }

    public function updatedIdKaryawan()
    {
        $user = User::where('username', $this->id_karyawan)->first();
        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->password = $user->password;
            $this->is_create = false;
        } else {
            $this->name = '';
            $this->email = '';
            $this->role = '';
            $this->password = '';
            $this->is_create = true;
        }
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
