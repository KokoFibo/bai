<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class Datatidaklengkap extends Component
{
    public function isDataUtamaLengkap()
    {
        $data = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where(function ($query) {
                $query->where('metode_penggajian', '')
                    ->orWhere('gaji_pokok', '')
                    ->orWhere('company_id', 100)
                    ->orWhere('placement_id', 100)
                    ->orWhere('jabatan_id', 100);
            })
            ->get();

        return $data;
    }

    public function render()
    {
        $statusKaryawan = ['PKWT', 'PKWTT', 'Dirumahkan'];

        $metode_penggajian = Karyawan::where('metode_penggajian', '')
            ->whereIn('status_karyawan', $statusKaryawan)
            ->get();


        $company = Karyawan::where('company_id', 100)
            ->whereIn('status_karyawan', $statusKaryawan)
            ->get();

        $placement = Karyawan::where('placement_id', 100)
            ->whereIn('status_karyawan', $statusKaryawan)
            ->get();

        $jabatan = Karyawan::where('jabatan_id', 100)
            ->whereIn('status_karyawan', $statusKaryawan)
            ->get();

        $data = $this->isDataUtamaLengkap();

        return view('livewire.datatidaklengkap', [
            'metode_penggajian' => $metode_penggajian,
            'company' => $company,
            'placement' => $placement,
            'jabatan' => $jabatan,
        ]);
    }
}
