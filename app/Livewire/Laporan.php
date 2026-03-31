<?php

namespace App\Livewire;

use App\Models\Payroll;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Laporan extends Component
{
    public $year;
    public $month;
    public $bulanans;


    public function mount()
    {
        $this->year = now()->year;
        // $this->month = now()->month;
        $this->month = 4;
    }

    // public function loadData()
    // {
    //     $this->bulanans = Payroll::selectRaw('MONTH(date) as bulan, COUNT(*) as total_pegawai, SUM(total) as total_gaji')
    //         ->whereYear('date', $this->year)
    //         ->groupBy(DB::raw('MONTH(date)'))
    //         ->orderBy('bulan')
    //         ->get();
    // }

    public function render()
    {
        $data = Payroll::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->select('placement_id', 'company_id', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total) as total'))
            ->groupBy('placement_id', 'company_id')
            ->get()
            ->groupBy('placement_id');

        $totalStaff = $data->flatten()->sum('jumlah');
        $totalAmount = $data->flatten()->sum('total');

        $this->bulanans = Payroll::selectRaw('MONTH(date) as bulan, COUNT(*) as total_pegawai, SUM(total) as total_gaji')
            ->whereYear('date', $this->year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy('bulan')
            ->get();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfToday = Carbon::now(); // untuk MTD


        // start 

        $tahun = now()->year;

        // Ambil data dari Januari hingga bulan ini
        $laporan_bulanan = collect();
        foreach (range(1, now()->month) as $bulan) {
            $laporan = DB::table('payrolls')
                ->selectRaw('placement_id, SUM(total) as total_gaji, COUNT(DISTINCT id_karyawan) as jumlah_karyawan, 
                SUM(tambahan_shift_malam) as tambahan_shift_malam,
                SUM(jam_kerja) as jam_kerja,  
                SUM(jam_lembur) as jam_lembur, 
                SUM(bonus1x) as bonus1x, 
                SUM(potongan1x) + sum(denda_lupa_absen) + sum(denda_resigned)+ sum(iuran_air) as potongan1x,
                SUM(gaji_pokok/198*jam_kerja) as total_gaji_pokok,
                SUM(gaji_lembur*jam_lembur) as total_lemburan
                

                ')
                ->where('metode_penggajian', 'Perjam')
                ->whereYear('date', $tahun)
                ->whereMonth('date', $bulan)
                ->groupBy('placement_id')
                ->get()
                ->map(function ($row) use ($bulan) {
                    return (object)[
                        'bulan' => $bulan,
                        'placement_id' => $row->placement_id,
                        'total_gaji' => $row->total_gaji,
                        'jumlah_karyawan' => $row->jumlah_karyawan,
                        'tambahan_shift_malam' => $row->tambahan_shift_malam,
                        'jam_kerja' => $row->jam_kerja,
                        'jam_lembur' => $row->jam_lembur,
                        'bonus1x' => $row->bonus1x,
                        'potongan1x' => $row->potongan1x,
                        'total_gaji_pokok' => $row->total_gaji_pokok,
                        'total_lemburan' => $row->total_lemburan,
                        // 'rata_rata_gaji' => $row->total_gaji_pokok / $row->total_gaji,
                        'rata_rata_gaji' => $row->jumlah_karyawan > 0
                            ? $row->total_gaji_pokok / $row->jumlah_karyawan
                            : 0,

                        'rata_rata_gaji_perjam' => $row->jam_kerja > 0
                            ? $row->total_gaji_pokok / $row->jam_kerja
                            : 0,

                        'rata_rata_lembur_perjam' => $row->jam_lembur > 0
                            ? ($row->total_lemburan ?? 0) / $row->jam_lembur
                            : 0,
                    ];
                });

            $laporan_bulanan = $laporan_bulanan->merge($laporan);
        }


        return view('livewire.laporan', [

            'bulanans' => $this->bulanans,
            'data' => $data,
            'totalStaff' => $totalStaff,
            'totalAmount' => $totalAmount,
            'laporan_bulanan' => $laporan_bulanan,
        ]);
    }
}
