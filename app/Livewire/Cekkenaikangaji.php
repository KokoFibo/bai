<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Cekkenaikangaji extends Component
{
    public $id_karyawan;
    public $tahun;
    public $hasil = [];

    public function mount()
    {
        $this->tahun = now()->year;
    }

    public function proses()
    {
        $this->validate([
            'id_karyawan' => 'required|numeric',
            'tahun' => 'required|numeric',
        ]);

        $rows = DB::table('payrolls')
            ->select('date', 'gaji_pokok')
            ->where('id_karyawan', $this->id_karyawan)
            ->whereYear('date', $this->tahun)
            ->orderBy('date')
            ->get();

        $this->hasil = $rows->map(function ($row, $index) use ($rows) {

            $row->bulan = Carbon::parse($row->date)->format('F');

            $row->selisih = 0;
            $row->persen = 0;
            $row->status = 'Tetap';

            if ($index > 0) {
                $prev = $rows[$index - 1]->gaji_pokok;

                $row->selisih = $row->gaji_pokok - $prev;
                $row->persen = $prev > 0
                    ? round(($row->selisih / $prev) * 100, 2)
                    : 0;

                $row->status = $row->selisih > 0
                    ? 'Naik'
                    : ($row->selisih < 0 ? 'Turun' : 'Tetap');
            }

            return $row;
        });
    }



    public function render()
    {

        return view(
            'livewire.cekkenaikangaji'
        );
    }
}
