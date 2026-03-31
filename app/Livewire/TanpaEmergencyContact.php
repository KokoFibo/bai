<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExcelTanpaContactDarurat;

class TanpaEmergencyContact extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $year, $month;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }
    public function excel()
    {
        $datas = Karyawan::where('kontak_darurat', '')
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->get(['id_karyawan', 'nama', 'email', 'hp', 'telepon', 'company', 'placement', 'departemen']);
        $nama_file = 'Tanpa_Kontak_Darurat_' . monthName($this->month) . '_' . $this->year . '.xlsx';
        return Excel::download(new ExcelTanpaContactDarurat($datas), $nama_file);
    }
    public function render()
    {
        $datas = Karyawan::where('kontak_darurat', '')
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->paginate(10);
        return view('livewire.tanpa-emergency-contact', [
            'datas' => $datas
        ]);
    }
}
