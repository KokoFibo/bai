<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InfoKaryawanExport;
use Livewire\WithPagination;


class KaryawanTerlambat extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month, $year;
    public $showKaryawanTerlambat = false;
    public $selectBulan;
    public $terlambatFrom, $terlambatTo, $periodeTerlambat;
    public $perpage = 5;
    public $metode = "Perbulan";



    public function excelTerlambat()
    {
        $karyawan_telat = Yfrekappresensi::join(
            'karyawans',
            'karyawans.id_karyawan',
            '=',
            'yfrekappresensis.user_id'
        )
            ->whereIn('karyawans.status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->when(
                $this->metode != "Semua",
                function ($q) {
                    return $q->where('karyawans.metode_penggajian', $this->metode);
                }
            )
            ->where('yfrekappresensis.late', '>', 0);

        // Filter tanggal
        if ($this->terlambatFrom != "" && $this->terlambatTo != "") {

            // Jika user pilih rentang tanggal manual
            $karyawan_telat->whereBetween('date', [$this->terlambatFrom, $this->terlambatTo]);
        } else {

            // Default: pakai bulan & tahun yang dipilih di dropdown
            $karyawan_telat->whereYear('date', $this->year)
                ->whereMonth('date', $this->month);
        }

        $karyawan_telat = $karyawan_telat
            ->select(
                'yfrekappresensis.user_id',
                'karyawans.nama',
                'karyawans.jabatan_id',
                'karyawans.placement_id',
                'karyawans.department_id',
                'karyawans.status_karyawan',
                'karyawans.metode_penggajian',
                'karyawans.company_id'
            )
            ->selectRaw('COUNT(*) as total')
            ->groupBy(
                'yfrekappresensis.user_id',
                'karyawans.nama',
                'karyawans.jabatan_id',
                'karyawans.placement_id',
                'karyawans.department_id',
                'karyawans.status_karyawan',
                'karyawans.metode_penggajian',
                'karyawans.company_id'
            )
            ->orderBy('total', 'desc')
            ->get();
        $periode = $this->periodeTerlambat;
        $metode_penggajian = $this->metode;
        if ($this->metode == "Semua") $metode_penggajian = "";
        $title = "Rekap Karyawan " . $metode_penggajian . " Terlambat " . $periode;
        $label = "Jumlah karyawan " . $metode_penggajian . " Terlambat";
        return Excel::download(
            new InfoKaryawanExport($karyawan_telat, $periode, $label, $title),
            'Karyawan Terlambat ' . $metode_penggajian . ' ' . $periode . '.xlsx'
        );
    }

    public function updatedSelectBulan()
    {
        $this->terlambatFrom = "";
        $this->terlambatTo = "";
        if ($this->selectBulan == 0) {
            // Bulan sekarang
            $this->month = now()->month;
            $this->year  = now()->year;
        } else {
            // Bulan lalu
            $this->month = now()->subMonth()->month;
            $this->year  = now()->subMonth()->year;
        }
    }

    public function toggleKaryawanTerlambat()
    {
        $this->showKaryawanTerlambat = !$this->showKaryawanTerlambat;
    }


    public function mount()
    {
        $this->month = now()->month; // bulan sekarang (1–12)
        $this->year  = now()->year;  // tahun sekarang (misal 2025)
        $this->selectBulan = 0; // bulan ini
        $this->terlambatFrom = "";
        $this->terlambatTo = "";
        $this->metode = "Perbulan";
    }
    public function render()
    {
        // BASE QUERY
        $baseQuery = Yfrekappresensi::join(
            'karyawans',
            'karyawans.id_karyawan',
            '=',
            'yfrekappresensis.user_id'
        )
            ->whereIn('karyawans.status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->when($this->metode != "Semua", function ($q) {
                $q->where('karyawans.metode_penggajian', $this->metode);
            })
            ->where('yfrekappresensis.late', '>', 0);

        // Filter tanggal
        if ($this->terlambatFrom != "" && $this->terlambatTo != "") {

            // Rentang tanggal manual
            $baseQuery->whereBetween('date', [$this->terlambatFrom, $this->terlambatTo]);
        } else {

            // Default bulan+tahun
            $baseQuery->whereYear('date', $this->year)
                ->whereMonth('date', $this->month);
        }

        // ================================
        // 🔥 TOTAL sebelum pagination
        // ================================
        $total_karyawan_telat = (clone $baseQuery)
            ->select('yfrekappresensis.user_id')
            ->selectRaw('COUNT(*) as total_terlambat')
            ->groupBy('yfrekappresensis.user_id')
            ->get()
            ->count();

        // ================================
        // 🔥 DATA untuk table + paginate
        // ================================
        $karyawan_telat = $baseQuery
            ->select(
                'yfrekappresensis.user_id',
                'karyawans.nama',
                'karyawans.jabatan_id',
                'karyawans.placement_id',
                'karyawans.department_id',
                'karyawans.status_karyawan',
                'karyawans.metode_penggajian',
                'karyawans.company_id'
            )
            ->selectRaw('COUNT(*) as total_terlambat')
            ->groupBy(
                'yfrekappresensis.user_id',
                'karyawans.nama',
                'karyawans.jabatan_id',
                'karyawans.placement_id',
                'karyawans.department_id',
                'karyawans.status_karyawan',
                'karyawans.metode_penggajian',
                'karyawans.company_id'
            )
            ->orderBy('total_terlambat', 'desc')
            ->paginate($this->perpage);


        if ($this->terlambatFrom == "" && $this->terlambatTo == "") {
            $this->periodeTerlambat = "( " . nama_bulan($this->month) . $this->year . " )";
        } else {
            $this->periodeTerlambat = "Periode ( " . Carbon::parse($this->terlambatFrom)->translatedFormat('j M Y') . " - " . Carbon::parse($this->terlambatTo)->translatedFormat('j M Y') . " )";
        }

        // $total_karyawan_telat = $karyawan_telat->count();
        return view('livewire.karyawan-terlambat', [
            'karyawan_telat' => $karyawan_telat,
            'total_karyawan_telat' => $total_karyawan_telat,
        ]);
    }
}
