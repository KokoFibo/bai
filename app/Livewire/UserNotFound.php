<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;

class UserNotFound extends Component
{
    public $name, $password, $username, $role, $language;
    public $is_save;

    public function mount()
    {
        $this->is_save = false;
    }

    public function save()
    {
        $data = new User;
        $data->name = $this->name;
        $data->password = $this->password;
        $data->username = $this->username;
        $data->role = $this->role;
        $data->language = $this->language;
        $data->save();
        $this->reset();
        $this->is_save = false;


        // $this->dispatch('success', message: 'User created successfully');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'User created successfully',
        );
    }



    public function create($id)
    {
        $data = Karyawan::where('id_karyawan', $id)->first();
        $this->name = $data->nama;
        $this->password = generatePassword($data->tanggal_lahir);
        $this->username = $data->id_karyawan;
        $this->role = 1;
        $this->language = 'Id';
        $this->is_save = true;
    }
    public function render()
    {
        $datakaryawan = Karyawan::select('id_karyawan')->get()->toArray();
        $datauser = User::select('username')->get()->toArray();

        // Extract values from arrays
        $karyawanIds = array_column($datakaryawan, 'id_karyawan');
        $usernames = array_column($datauser, 'username');

        // Find elements in $karyawanIds that are not in $usernames
        $missingKaryawanIds = array_diff($karyawanIds, $usernames);

        // Output the result
        // dd($missingKaryawanIds);
        return view('livewire.user-not-found', ['missingKaryawanIds' => $missingKaryawanIds]);
    }
}
