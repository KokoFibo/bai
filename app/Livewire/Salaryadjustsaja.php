<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Carbon\Carbon;
use Livewire\Component;

class Salaryadjustsaja extends Component
{
    public $bulan = '2025-11';
    public $listBulan = [];

    public function mount()
    {
        $this->listBulan = Karyawan::selectRaw("
                DATE_FORMAT(tanggal_bergabung,'%Y-%m') as bulan
            ")
            ->whereBetween('tanggal_bergabung', ['2025-11-01', '2026-03-31'])
            ->distinct()
            ->orderBy('bulan')
            ->pluck('bulan')
            ->toArray();
    }

    public function updateGaji($id, $gaji)
    {
        Karyawan::find($id)->update([
            'gaji_pokok' => $gaji,
        ]);

        // $this->dispatch(
        //     'message',
        //     type: 'success',
        //     title: 'Gaji berhasil diperbarui.'
        // );
    }

    public function render()
    {
        $awalBulan = Carbon::parse($this->bulan . '-01')->startOfMonth();
        $akhirBulan = Carbon::parse($this->bulan . '-01')->endOfMonth();

        $data = Karyawan::selectRaw("
                *,
                DATEDIFF(CURDATE(), tanggal_bergabung) as lama_hari
            ")
            ->whereNotIn('status_karyawan', ['Blacklist', 'Resigned'])
            ->whereBetween('tanggal_bergabung', [$awalBulan, $akhirBulan])
            ->where('gaji_pokok', '<', 2500000)
            ->orderBy('tanggal_bergabung')
            ->get();

        return view('livewire.salaryadjustsaja', [
            'data' => $data,
            'awalBulan' => $awalBulan,
            'akhirBulan' => $akhirBulan,
        ]);
    }
}
