<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeleteMonthlyPresensi extends Component
{
    public $bulan;
    public $tahun;
    public $confirmDelete = '';

    public $availableMonths = [];
    public $availableYears = [];

    public $totalData = 0;
    public $totalKaryawan = 0;

    public $periode;
    public $availablePeriods = [];

    public function mount()
    {
        $this->availablePeriods = Yfrekappresensi::query()
            ->selectRaw("
            YEAR(date) as tahun,
            MONTH(date) as bulan,
            COUNT(*) as total
        ")
            ->groupByRaw('YEAR(date), MONTH(date)')
            ->orderByRaw('YEAR(date) DESC')
            ->orderByRaw('MONTH(date) DESC')
            ->get();
    }

    public function updatedPeriode()
    {
        if (!$this->periode) {
            return;
        }

        [$this->tahun, $this->bulan] = explode('-', $this->periode);

        $this->loadSummary();
    }
    public function updatedBulan()
    {
        $this->loadSummary();
    }

    public function updatedTahun()
    {
        $this->loadSummary();
    }

    protected function loadSummary()
    {
        if (!$this->bulan || !$this->tahun) {

            $this->totalData = 0;
            $this->totalKaryawan = 0;

            return;
        }

        $query = Yfrekappresensi::query()
            ->whereYear('date', $this->tahun)
            ->whereMonth('date', $this->bulan);

        $this->totalData = $query->count();

        $this->totalKaryawan = (clone $query)
            ->distinct('karyawan_id')
            ->count('karyawan_id');
    }

    public function deleteData()
    {
        $this->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        if ($this->confirmDelete !== 'HAPUS') {

            $this->addError(
                'confirmDelete',
                'Silakan ketik HAPUS untuk melanjutkan.'
            );

            return;
        }

        DB::transaction(function () {

            Yfrekappresensi::query()
                ->whereYear('date', $this->tahun)
                ->whereMonth('date', $this->bulan)
                ->delete();
        });

        session()->flash(
            'success',
            'Data presensi berhasil dihapus.'
        );

        $this->reset([
            'bulan',
            'tahun',
            'confirmDelete',
            'totalData',
            'totalKaryawan'
        ]);
    }

    public function render()
    {
        return view('livewire.delete-monthly-presensi');
    }
}
