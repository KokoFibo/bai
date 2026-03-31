<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;

class PerbulanKurangBayar extends Component
{
    public $month;
    public function render()
    {

        $year = 2025;
        $data = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
            ->select(
                'yfrekappresensis.*',
                'karyawans.metode_penggajian',
                'karyawans.nama'
            )
            ->whereMonth('yfrekappresensis.date', $this->month)
            ->whereYear('yfrekappresensis.date', $year)
            ->where('karyawans.metode_penggajian', 'Perbulan')
            // ->where('total_jam_kerja_libur', '<', 4)
            ->where('total_jam_kerja', '<=', 7)
            ->where('total_jam_kerja', '>', 0)
            ->where('total_hari_kerja', 0)
            ->get();
        $total = 0;
        $total = $data->count();
        // dd($data);

        return view('livewire.perbulan-kurang-bayar', [
            'data' => $data,
            'total' => $total
        ]);
    }
}
