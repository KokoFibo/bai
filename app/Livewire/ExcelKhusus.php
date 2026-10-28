<?php

namespace App\Livewire;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\karyawanExportKhususBaru;
use Carbon\Carbon;

class ExcelKhusus extends Component
{
    public $semuaData = false;
    public $bulan;
    public $tahun;

    public function mount()
    {
        // Default: bulan lalu, tahun berjalan
        $this->bulan = now()->subMonth()->month;
        $this->tahun = now()->year;
    }

    public function export()
    {
        $this->validate([
            'semuaData' => 'required|boolean',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|between:2000,2100',
        ]);

        $namaFile = 'Data_Karyawan_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new karyawanExportKhususBaru(
                $this->semuaData,
                $this->bulan,
                $this->tahun
            ),
            'Data_Karyawan_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.excel-khusus', [
            'daftarBulan' => [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ],
            'daftarTahun' => range(now()->year - 10, now()->year + 1),
        ]);
    }
}
