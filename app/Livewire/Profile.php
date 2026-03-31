<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    public $current_email;
    public $email;
    public $current_password;
    public $old_password;
    public $new_password;
    public $confirm_password;
    public $language;
    public $kontak_darurat, $kontak_darurat2, $hp1, $hp2, $id, $hubungan1, $hubungan2;
    public $etnis;

    public function updateEtnis()
    {
        // $user = User::find(Auth::user()->id);
        if (auth()->user()->language == 'Cn') {
            $this->validate([
                'etnis' => 'required',
            ], [
                'etnis.required' => '必填项',
            ]);
        } else {
            $this->validate([
                'etnis' => 'required',
            ], [
                'etnis.required' => 'Wajib diisi',
            ]);
        }
        $user = Karyawan::where('id_karyawan', auth()->user()->username)->first();
        $user->etnis = $this->etnis;
        $user->save();
        if (auth()->user()->language == 'Id')
            $this->dispatch('success', message: 'Etnis berhasil di update');
        else $this->dispatch('success', message: '语言已成功更改');
    }

    public function changeLanguage()
    {
        $user = User::find(Auth::user()->id);
        if ($user->language != $this->language) {
            $user->language = $this->language;
            $user->save();
            if (auth()->user()->language == 'Cn')
                $this->dispatch('success', message: 'Bahasa berhasil di rubah');
            else $this->dispatch('success', message: '语言已成功更改');
        }
    }

    public function changePassword()
    {
        if (auth()->user()->language == 'Cn') {
            $this->validate([
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6|different:old_password',
                'confirm_password' => 'required|same:new_password',
            ], [
                'old_password.required' => '必填项',
                'old_password.min' => '最少需要6个字符',
                'new_password.required' => '必填项',
                'new_password.min' => '最少需要6个字符',
                'new_password.different:old_password' => '必须与旧密码不同',
                'confirm_password.required' => '必填项',
                'confirm_password.same:new_password' => '密码不同',
            ]);
        } else {
            $this->validate([
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6|different:old_password',
                'confirm_password' => 'required|same:new_password',
            ], [
                'old_password.required' => 'Wajib diisi',
                'old_password.min' => 'Minimal harus 6 karakter',
                'new_password.required' => 'Wajib diisi',
                'new_password.min' => 'Minimal harus 6 karakter',
                'new_password.different:old_password' => 'Harus berbeda dengan password lama',
                'confirm_password.required' => 'Wajib diisi',
                'confirm_password.same:new_password' => 'Password berbeda',
            ]);
        }

        $user = User::find(Auth::user()->id);
        if (Hash::check($this->old_password, Auth::user()->password)) {
            $user->password = Hash::make($this->new_password);

            $user->save();
            if (auth()->user()->language == 'Cn')
                $this->dispatch('success', message: '密码已成功更改');
            else $this->dispatch('success', message: 'Password berhasil di rubah');
        } else {
            if (auth()->user()->language == 'Cn')
                $this->dispatch('error', message: '密码更改失败');
            else $this->dispatch('error', message: 'Password gagal di rubah');
        }
    }

    public function changeEmail()
    {
        $this->validate([
            'email' => 'email|unique:users',
        ], [
            'email.email' => 'Format email salah',
            'email.unique' => 'Email ini sudah terdaftar dalam database kami, gunakan yang lain',
        ]);

        $user = User::find(Auth::user()->id);
        $user->email = $this->email;
        $data_karyawan = Karyawan::where('id_karyawan', $user->username)->first();
        $karyawan = Karyawan::find($data_karyawan->id);
        $karyawan->email = $this->email;
        $user->save();
        $karyawan->save();

        if (auth()->user()->language == 'Cn')
            $this->dispatch('success', message: '电子邮件已成功更改');
        else $this->dispatch('success', message: 'Email berhasil di rubah');
    }

    public function mount()
    {
        $this->current_email = auth()->user()->email;
        $this->language = auth()->user()->language;
        $data = Karyawan::where('id_karyawan', auth()->user()->username)->first();
        if ($data != null) {
            $this->kontak_darurat = $data->kontak_darurat;
            $this->hp1 = $data->hp1;
            $this->hp2 = $data->hp2;
            $this->id = $data->id;
            $this->etnis = $data->etnis;
        }
    }

    public function update_kontak_darurat()
    {
        if (auth()->user()->language == 'Cn') {
            $this->validate([
                'kontak_darurat' => 'required',
                'kontak_darurat2' => 'nullable',
                'hubungan1' => 'nullable',
                'hubungan2' => 'nullable',
                'hp1' => 'required|numeric|min_digits:10',
                'hp2' => 'nullable',
            ], [
                'kontak_darurat.required' => '必填项',
                'hp1.required' => '必填项',
                'hp1.numeric' => '这应该是数字0到9',
                'hp1.min_digits' => '最少需要10位数字',


            ]);
        } else {

            $this->validate([
                'kontak_darurat' => 'required',
                'kontak_darurat2' => 'nullable',
                'hubungan1' => 'nullable',
                'hubungan2' => 'nullable',
                'hp1' => 'required|numeric|min_digits:10',
                'hp2' => 'nullable',
            ], [
                'kontak_darurat.required' => 'Wajib diisi',
                'hp1.required' => 'Wajib diisi',
                'hp1.numeric' => 'Harus berupa angka 0..9',
                'hp1.min_digits' => 'Minimal 10 digit',


            ]);
        }

        $data = Karyawan::find($this->id);
        if ($data == null) {
            $this->dispatch('error', message: 'Data Karyawan tidak ada');
            return;
        }
        $data->hubungan1 = $this->hubungan1;
        $data->hubungan2 = $this->hubungan2;
        $data->kontak_darurat = $this->kontak_darurat;
        $data->kontak_darurat2 = $this->kontak_darurat2;
        $data->hp1 = $this->hp1;
        $data->hp2 = $this->hp2;
        $data->save();
        if (auth()->user()->language == 'Cn')
            $this->dispatch('success', message: '紧急联系信息已更新');
        else $this->dispatch('success', message: 'Data kontak darurat sudah di update');
    }
    public function render()
    {
        return view('livewire.profile')->layout('layouts.polos');
    }
}
