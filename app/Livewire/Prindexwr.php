<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Prindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $periode;
    public $search = '';
    public $cx = 0;
    public $columnName = 'user_id';
    public $direction = 'desc';
    public $perpage = 10;
    public $year;
    public $month;
    public $select_month, $select_year;


    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function mount()
    {
        if (now()->day < 5) {
            $this->year =
                now()->subMonth()->year;
            $this->month =
                now()->subMonth()->month;
        } else {
            $this->year = now()->year;
            $this->month = now()->month;
        }


        $getTglTerakhir = Yfrekappresensi::select('date')
            ->orderBy('date', 'desc')
            ->first();
        if ($getTglTerakhir != null) {
            $this->periode = buatTanggal($getTglTerakhir->date);
        } else {
            $this->periode = '2000-01-01';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function buat_payroll()
    {
        // supaya tidak dilakukan bersamaan
        $lock = Lock::find(1);
        if ($lock->build == 1) {
            // $this->dispatch('error', message: 'Mohon dicoba sebentar lagi');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Mohon dicoba sebentar lagi',
            );
            return;
        } else {
            $lock->build = 1;
            $lock->save();
        }


        $result = build_payroll($this->month, $this->year);
        if ($result == 0) {
            // $this->dispatch('error', message: 'Data Presensi tidak ada');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Presensi tidak ada',
            );
        } else {
            // $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Payroll Karyawan Sudah di Built',
            );
        }

        $lock->build = 0;
        $lock->save();
        return redirect()->to('/payrollindex');
    }

    // ok1
    // #[On('getPayroll')]



    // ok3
    public function bonus_potongan()
    {
        $bonus = 0;
        $potongaan = 0;
        $all_bonus = 0;
        $all_potongan = 0;
        $tambahan = Tambahan::whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year)
            ->get();

        foreach ($tambahan as $d) {
            $all_bonus = $d->uang_makan + $d->bonus_lain;
            $all_potongan = $d->baju_esd + $d->gelas + $d->sandal + $d->seragam + $d->sport_bra + $d->hijab_instan + $d->id_card_hilang + $d->masker_hijau + $d->potongan_lain;
            $id_payroll = Payroll::whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('id_karyawan', $d->user_id)
                ->first();
            if ($id_payroll != null) {
                $payroll = Payroll::find($id_payroll->id);
                $payroll->bonus1x = $payroll->bonus1x + $all_bonus;
                $payroll->potongan1x = $payroll->potongan1x + $all_potongan;
                $payroll->total = $payroll->total + $all_bonus - $all_potongan;
                $payroll->save();
            }
        }

        // $this->dispatch('success', message: 'Bonus dan Potangan added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Bonus dan Potangan added',
        );
    }

    public function updatedYear()
    {
        $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        $this->month = $this->select_month[0];
    }

    public function render()
    {
        $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $months = Payroll::select(DB::raw('MONTH(date) as month'))
            ->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        if (!in_array($this->month, $months)) {
            $months[] = $this->month;
        }

        $this->select_month = $months;

        // $periodePayroll = DB::table('yfrekappresensis')
        //     ->select(DB::raw('YEAR(date) year, MONTH(date) month, MONTHNAME(date) month_name'))
        //     ->distinct()
        //     ->orderBy('year', 'desc')
        //     ->orderBy('month', 'desc')
        //     ->get();
        $this->cx++;


        $filteredData = Jamkerjaid::select(['jamkerjaids.*', 'karyawans.nama'])
            ->join('karyawans', 'jamkerjaids.karyawan_id', '=', 'karyawans.id')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            // ->whereMonth('date', 'like', '%' . $this->month . '%')
            //     ->whereYear('date', 'like', '%' . $this->year . '%')
            ->where(function ($query) {
                $query->when($this->search, function ($subQuery) {
                    $subQuery
                        ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('user_id', trim($this->search))
                        ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%')
                        ->orWhere('status_karyawan', 'LIKE', '%' . trim($this->search) . '%');
                });
            })

            ->orderBy($this->columnName, $this->direction)
            ->orderBy('user_id', 'asc')
            ->paginate($this->perpage);


        if ($filteredData->isNotEmpty()) {
            $lastData = $filteredData[0]->last_data_date;
        } else {
            $lastData = null;
        }

        $tgl = Jamkerjaid::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->select('created_at')->first();
        if ($tgl != null) {
            $last_build = Carbon::parse($tgl->created_at)->diffForHumans();
        } else {
            $last_build = 0;
        }

        // return view('livewire.prindexwr', compact(['filteredData', 'periodePayroll', 'lastData', 'last_build']));
        return view('livewire.prindexwr', compact(['filteredData',  'lastData', 'last_build']));
    }
}
