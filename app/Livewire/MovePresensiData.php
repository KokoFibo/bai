<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rekapbackup;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;


class MovePresensiData extends Component
{
    public $month, $year, $today;

    public $tahun, $bulan, $getYear, $getMonth, $dataBulan, $dataTahun, $totalData;

    public function compact()
    {
        if (Schema::hasTable('rekapbackups')) {
            // Cari tanggal terbaru di backup
            $latestDate = DB::table('rekapbackups')->max('date');

            if ($latestDate) {
                // Ambil cutoff date (12 bulan ke belakang dari yang terbaru)
                $cutoff = \Carbon\Carbon::parse($latestDate)->subMonths(12)->startOfMonth();

                // Hapus data yang lebih lama dari cutoff
                DB::table('rekapbackups')
                    ->where('date', '<', $cutoff)
                    ->delete();

                $this->dispatch(
                    'message',
                    type: 'success',
                    title: "Data lama sebelum {$cutoff->format('m/Y')} berhasil dihapus. Hanya tersisa 12 bulan terakhir."
                );
            } else {
                $this->dispatch(
                    'message',
                    type: 'info',
                    title: "Tidak ada data di rekapbackups."
                );
            }
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: "Table 'rekapbackups' tidak ditemukan!"
            );
        }
    }

    public function cancel()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->dataTahun = Yfrekappresensi::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();

        // $this->render();
    }


    public function move()
    {
        if (Schema::hasTable('rekapbackups')) {
            $exists = DB::table('rekapbackups')
                ->whereYear('date', $this->getYear)
                ->whereMonth('date', $this->getMonth)
                ->exists();

            DB::transaction(function () use ($exists) {
                if ($exists) {
                    // Hapus data backup lama untuk bulan & tahun yang sama
                    DB::table('rekapbackups')
                        ->whereYear('date', $this->getYear)
                        ->whereMonth('date', $this->getMonth)
                        ->delete();
                }

                // Copy data dari tabel asal ke backup
                Yfrekappresensi::whereYear('date', $this->getYear)
                    ->whereMonth('date', $this->getMonth)
                    ->chunk(500, function ($datas) {
                        $insertData = $datas->map(function ($data) {
                            // Hindari duplikat primary key
                            return Arr::except($data->toArray(), ['id']);
                        })->toArray();

                        DB::table('rekapbackups')->insert($insertData);
                    });

                // Setelah berhasil backup → hapus data dari tabel asal
                Yfrekappresensi::whereYear('date', $this->getYear)
                    ->whereMonth('date', $this->getMonth)
                    ->delete();
            });

            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data rekap presensi bulan ' . $this->getMonth . '/' . $this->getYear . ' berhasil di-backup dan dipindahkan.'
            );
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: "Table 'rekapbackups' tidak ditemukan!"
            );
        }
    }

    public function mount()
    {
        $this->today = now();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->getYear = "";
        $this->getMonth = "";
        $this->totalData = 0;
        $this->dataTahun = Yfrekappresensi::selectRaw('YEAR(date) as year')
            ->groupByRaw('YEAR(date)')
            ->pluck('year')
            ->all();
    }

    public function updatedGetMonth()
    {
        $this->totalData = Yfrekappresensi::whereYear('date', $this->getYear)->whereMonth('date', $this->getMonth)->count();
    }
    public function updatedGetYear()
    {

        $currentMonth = $this->month;
        $lastMonth = ($currentMonth - 1) == 0 ? 12 : ($currentMonth - 1);

        $this->dataBulan = Yfrekappresensi::whereYear('date', $this->getYear)
            ->whereNotIn(DB::raw('MONTH(date)'), [$currentMonth, $lastMonth])
            ->selectRaw('MONTH(date) as month')
            ->groupByRaw('MONTH(date)')
            ->pluck('month')
            ->all();
    }

    public function render()
    {
        return view('livewire.move-presensi-data');
    }
}
