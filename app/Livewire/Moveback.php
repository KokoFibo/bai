<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rekapbackup;
use Illuminate\Support\Arr;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Moveback extends Component
{
    public $month, $year, $today;

    public $tahun, $bulan, $getYear, $getMonth, $dataBulan, $dataTahun, $totalData;

    public function cancel()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->dataTahun = Rekapbackup::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();
    }


    public function moveBack()
    {
        DB::transaction(function () {
            // 1. Hapus dulu data lama bulan & tahun tertentu dari tabel asal
            DB::table('yfrekappresensis')
                ->whereYear('date', $this->getYear)
                ->whereMonth('date', $this->getMonth)
                ->delete();

            // 2. Ambil data dari backup, lalu restore ke tabel asal
            Rekapbackup::whereYear('date', $this->getYear)
                ->whereMonth('date', $this->getMonth)
                ->chunk(500, function ($datas) {
                    $insertData = $datas->map(function ($data) {
                        // Hilangkan kolom 'id' agar tidak bentrok dengan auto increment
                        return Arr::except($data->toArray(), ['id']);
                    })->toArray();

                    DB::table('yfrekappresensis')->insert($insertData);
                });
        });

        // 3. Kirim notifikasi sukses
        $this->dispatch(
            'message',
            type: 'success',
            title: "Data rekap presensi bulan {$this->getMonth}/{$this->getYear} berhasil di-restore."
        );
    }

    public function mount()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->totalData = 0;
        $this->dataTahun = Rekapbackup::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();
    }

    public function updatedGetMonth()
    {
        $this->totalData = Rekapbackup::whereYear('date', $this->getYear)->whereMonth('date', $this->getMonth)->count();
    }
    public function updatedGetYear()
    {

        $currentMonth = $this->month;
        $lastMonth = ($currentMonth - 1) == 0 ? 12 : ($currentMonth - 1);

        $this->dataBulan = Rekapbackup::whereYear('date', $this->getYear)
            ->whereNotIn(DB::raw('MONTH(date)'), [$currentMonth, $lastMonth])
            ->selectRaw('MONTH(date) as month')
            ->groupByRaw('MONTH(date)')
            ->pluck('month')
            ->all();
    }
    public function render()
    {
        return view('livewire.moveback');
    }
}
