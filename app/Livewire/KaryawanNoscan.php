<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InfoKaryawanExport;
use Livewire\WithPagination;

class KaryawanNoscan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month, $year;
    public $showKaryawanNoscan = false;
    public $selectBulan;
    public $noscanFrom, $noscanTo, $periodeNoscan;
    public $perpage = 5;
    public $metode = "Perbulan";


    public function excelNoscan()
    {

        $karyawan_noscan = Yfrekappresensi::join(
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
            ->where('yfrekappresensis.no_scan_history', 'No Scan');

        // Filter tanggal
        if ($this->noscanFrom != "" && $this->noscanTo != "") {

            // Jika user pilih rentang tanggal manual
            $karyawan_noscan->whereBetween('date', [$this->noscanFrom, $this->noscanTo]);
        } else {

            // Default: pakai bulan & tahun yang dipilih di dropdown
            $karyawan_noscan->whereYear('date', $this->year)
                ->whereMonth('date', $this->month);
        }

        $karyawan_noscan = $karyawan_noscan
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

        $periode = $this->periodeNoscan;
        $metode_penggajian = $this->metode;
        if ($this->metode == "Semua") $metode_penggajian = "";
        $title = "Rekap Karyawan " . $metode_penggajian . " No Scan " . $periode;
        $label = "Jumlah karyawan " . $metode_penggajian . " No Scan";
        return Excel::download(
            new InfoKaryawanExport($karyawan_noscan, $periode, $label, $title),
            'Karyawan No Scan ' . $metode_penggajian . " " . $periode . '.xlsx'
        );
    }

    public function updatedSelectBulan()
    {
        $this->noscanFrom = "";
        $this->noscanTo = "";
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

    public function toggleKaryawanNoscan()
    {
        $this->showKaryawanNoscan = !$this->showKaryawanNoscan;
    }


    public function mount()
    {
        $this->month = now()->month; // bulan sekarang (1–12)
        $this->year  = now()->year;  // tahun sekarang (misal 2025)
        $this->selectBulan = 0; // bulan ini
        $this->noscanFrom = "";
        $this->noscanTo = "";
        $this->metode = "Perbulan";
    }

    public function render()
    {
        // Query base
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
            ->where('yfrekappresensis.no_scan_history', 'No Scan');

        // Filter tanggal
        if ($this->noscanFrom != "" && $this->noscanTo != "") {
            $baseQuery->whereBetween('date', [$this->noscanFrom, $this->noscanTo]);
        } else {
            $baseQuery->whereYear('date', $this->year)
                ->whereMonth('date', $this->month);
        }

        // 🔥 TOTAL sebelum paginate
        $total_karyawan_noscan = (clone $baseQuery)
            ->select('yfrekappresensis.user_id')
            ->selectRaw('COUNT(*) as total_noscan')
            ->groupBy('yfrekappresensis.user_id')
            ->get()
            ->count();

        // 🔥 DATA untuk tabel + pagination
        $karyawan_noscan = $baseQuery
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
            ->selectRaw('COUNT(*) as total_noscan')
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
            ->orderBy('total_noscan', 'desc')
            ->paginate($this->perpage);


        if ($this->noscanFrom == "" && $this->noscanTo == "") {
            $this->periodeNoscan = "( " . nama_bulan($this->month) . $this->year . " )";
        } else {
            $this->periodeNoscan = "Periode ( " . Carbon::parse($this->noscanFrom)->translatedFormat('j M Y') . " - " . Carbon::parse($this->noscanTo)->translatedFormat('j M Y') . " )";
        }

        // $total_karyawan_noscan = $karyawan_noscan->count();
        return view(
            'livewire.karyawan-noscan',
            [
                'karyawan_noscan' => $karyawan_noscan,
                'total_karyawan_noscan' => $total_karyawan_noscan,
            ]
        );
    }
}
