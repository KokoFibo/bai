<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Karyawan;

class Turnover extends Component
{
    public $year;
    public $years = [];

    public function mount()
    {
        $this->years = Karyawan::selectRaw('YEAR(tanggal_bergabung) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $this->year = now()->year;
    }

    public function getTurnoverDataProperty()
    {
        $data = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($this->year, $month, 1)->startOfMonth();
            $endOfMonth   = $startOfMonth->copy()->endOfMonth();

            // Karyawan awal
            $awal = Karyawan::where('tanggal_bergabung', '<', $startOfMonth)
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereNull('tanggal_resigned')
                        ->orWhere('tanggal_resigned', '>=', $startOfMonth);
                })
                ->where(function ($q) use ($startOfMonth) {
                    $q->whereNull('tanggal_blacklist')
                        ->orWhere('tanggal_blacklist', '>=', $startOfMonth);
                })
                ->count();

            // Karyawan masuk
            $masuk = Karyawan::whereBetween('tanggal_bergabung', [$startOfMonth, $endOfMonth])->count();

            // Karyawan keluar
            $keluar = Karyawan::where(function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_resigned', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('tanggal_blacklist', [$startOfMonth, $endOfMonth]);
            })
                ->count();


            // Karyawan akhir
            $akhir = $awal + $masuk - $keluar;

            // Turnover
            $rataRata = ($awal + $akhir) / 2;
            $turnover = $rataRata > 0 ? round(($keluar / $rataRata) * 100, 2) : 0;

            $data[] = [
                'bulan'    => $startOfMonth->translatedFormat('F'),
                'awal'     => $awal,
                'masuk'    => $masuk,
                'keluar'   => $keluar,
                'akhir'    => $akhir,
                'turnover' => $turnover,
            ];
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.turnover');
    }
}
