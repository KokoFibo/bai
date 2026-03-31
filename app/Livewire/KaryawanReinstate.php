<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use App\Rules\FileSizeLimit;
use Livewire\Attributes\Url;
use App\Models\Applicantfile;
use Livewire\WithFileUploads;
use App\Livewire\Karyawanindexwr;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\RequiredIf;
use Google\Service\YouTube\ThirdPartyLinkStatus;
use Intervention\Image\ImageManagerStatic as Image;


class KaryawanReinstate extends Component
{
    public $id;
    public $id_karyawan, $nama, $status_karyawan;



    public function mount($id)
    {
        $this->id = $id;
        $data = Karyawan::find($this->id);
        $this->id_karyawan = $data->id_karyawan;
        $this->nama = $data->nama;
        $this->status_karyawan = '';
    }

    public function reinstateConfirmation()
    {
        $this->dispatch('show-reinstate-confirmation', $this->nama);
    }

    #[On('reinstate-confirmed')]
    public function reinstate()
    {
        $this->validate([
            'status_karyawan' => 'required'
        ]);

        $data_lama = Karyawan::find($this->id);
        if (!$data_lama) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Karyawan ' . $this->id . ' tidak ditemukan.',
                position: 'center'
            );
            return;
        }
        $data_baru = $data_lama->replicate();

        $data_baru->status_karyawan = $this->status_karyawan;
        $data_baru->tanggal_bergabung = date('Y-m-d', strtotime(now()->toDateString()));
        $new_id = getNextIdKaryawan();
        $data_baru->id_karyawan = $new_id;
        $data_baru->save();

        $user = User::where('username', $data_lama->id_karyawan)->first();
        $user->password = Hash::make(generatePassword($data_lama->tanggal_lahir));
        $user->username = $new_id;
        $user->save();
        $this->dispatch(
            'reinstate',
            type: 'reinstate',
            title: 'Status sudah berhasil di rubah',
        )->to(Karyawanindexwr::class);

        return redirect()->to('/karyawanindex');
    }

    public function cancel()
    {
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {
        return view('livewire.karyawan-reinstate')
            ->layout('layouts.appeloe');
    }
}
