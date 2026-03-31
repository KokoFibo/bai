<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\THRExport;


class Hitungthr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $cutOffDate = '2025-03-20';
    public function excel()
    {
        $nama_file = 'THR_OS_2025.xlsx';

        return Excel::download(new THRExport($this->cutOffDate), $nama_file,);
    }

    public function render()
    {

        $data = Karyawan::with(['placement', 'company', 'department', 'jabatan'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->get();
        $total = 0;
        foreach ($data as $d) {
            $total = $total + hitungTHR($d->id_karyawan,  $d->tanggal_bergabung, $d->gaji_pokok, $this->cutOffDate);
        }
        $karyawans = Karyawan::with(['placement', 'company', 'department', 'jabatan'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->paginate(10);
        return view('livewire.hitungthr', [
            'karyawans' => $karyawans,
            'total' => $total,

            'cutOffDate' => $this->cutOffDate
        ]);
    }
}
