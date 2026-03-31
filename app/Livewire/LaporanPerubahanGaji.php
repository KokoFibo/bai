<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanGajiPeriodeExport;

class LaporanPerubahanGaji extends Component
{
    public $bulan_awal;
    public $tahun_awal;
    public $bulan_akhir;
    public $tahun_akhir;

    public $months = [];
    public $data = [];

    public function exportExcel()
    {
        return Excel::download(
            new LaporanGajiPeriodeExport($this->data, $this->months, 'Tabel Perubahan Gaji Karyawan STI '),
            'laporan-perubahan-gaji-STI-periode.xlsx'
        );
    }

    public function mount()
    {
        $this->bulan_awal = 9;
        $this->tahun_awal = 2025;
        $this->bulan_akhir = now()->month;
        $this->tahun_akhir = now()->year;
    }

    public function proses()
    {
        $start = Carbon::create($this->tahun_awal, $this->bulan_awal, 1);
        $end   = Carbon::create($this->tahun_akhir, $this->bulan_akhir, 1);

        // Generate daftar bulan
        $this->months = [];
        $period = $start->copy();

        while ($period <= $end) {
            $this->months[] = $period->format('Y-m');
            $period->addMonth();
        }

        // Ambil data payroll + filter status karyawan
        $payrolls = DB::table('payrolls')
            ->join('karyawans', 'payrolls.id_karyawan', '=', 'karyawans.id_karyawan')
            ->select(
                'payrolls.id_karyawan',
                'karyawans.nama as nama',
                'karyawans.company_id',
                'karyawans.placement_id',
                DB::raw('DATE_FORMAT(payrolls.date, "%Y-%m") as periode'),
                'payrolls.gaji_pokok'
            )
            ->whereBetween('payrolls.date', [
                $start->copy()->startOfMonth(),
                $end->copy()->endOfMonth()
            ])
            ->whereIn('karyawans.status_karyawan', ['PKWT', 'PKWTT'])
            ->orderBy('payrolls.id_karyawan')
            ->get();

        // Pivot manual
        $result = [];

        foreach ($payrolls as $row) {

            if (!isset($result[$row->id_karyawan])) {
                $result[$row->id_karyawan] = [
                    'id'          => $row->id_karyawan,
                    'nama'        => $row->nama,
                    'company'     => nama_company($row->company_id),
                    'placement' => nama_placement($row->placement_id),
                ];

                // isi default null
                foreach ($this->months as $m) {
                    $result[$row->id_karyawan][$m] = null;
                }
            }

            $result[$row->id_karyawan][$row->periode] = $row->gaji_pokok;
        }

        $this->data = $result;
    }

    public function render()
    {
        return view('livewire.laporan-perubahan-gaji');
    }
}
