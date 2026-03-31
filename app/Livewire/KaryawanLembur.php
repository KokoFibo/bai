<?php

namespace App\Livewire;

use Livewire\Component;

use Carbon\Carbon;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InfoKaryawanExport;
use Livewire\WithPagination;

class KaryawanLembur extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month, $year;
    public $showKaryawanLembur = false;
    public $selectBulan;
    public $periodeLembur;
    public $perpage = 5;
    public $metode = "Perbulan";

    public function excelLembur()
    {

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
            ->where(function ($q) {
                $q->where('yfrekappresensis.total_jam_lembur', '>', 0)
                    ->orWhere('yfrekappresensis.total_jam_lembur_libur', '>', 0);
            });


        $baseQuery->whereYear('date', $this->year)
            ->whereMonth('date', $this->month);

        // 🔥 Total karyawan lembur ≥ 20 jam
        $total_karyawan_lembur = (clone $baseQuery)
            ->select('yfrekappresensis.user_id')
            ->selectRaw('SUM(total_jam_lembur + total_jam_lembur_libur) AS total_jam')
            ->groupBy('yfrekappresensis.user_id')
            ->having('total_jam', '>=', 20)
            ->get()
            ->count();

        // 🔥 Ambil data untuk tabel
        $karyawan_lembur = $baseQuery
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
            ->selectRaw('SUM(total_jam_lembur + total_jam_lembur_libur) AS total')
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
            ->having('total', '>=', 20)
            ->orderBy('total', 'desc')
            ->get();


        $periode = $this->periodeLembur;
        $metode_penggajian = $this->metode;
        if ($this->metode == "Semua") $metode_penggajian = "";
        $title = "Rekap Karyawan " . $metode_penggajian . " Lembur diatas 20 jam " . $periode;
        $label = "Jumlah karyawan " . $metode_penggajian . " yang lembur diatas 20 jam";
        return Excel::download(
            new InfoKaryawanExport($karyawan_lembur, $periode, $label, $title),
            'Karyawan ' . $metode_penggajian . ' lembur diatas 20 jam ' . $periode . '.xlsx'
        );
    }

    public function updatedSelectBulan()
    {
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

    public function toggleKaryawanLembur()
    {
        $this->showKaryawanLembur = !$this->showKaryawanLembur;
    }

    public function back()
    {
        $this->month = now()->month; // bulan sekarang (1–12)
        $this->year  = now()->year;  // tahun sekarang (misal 2025)
    }
    public function mount()
    {
        $this->month = now()->month; // bulan sekarang (1–12)
        $this->year  = now()->year;  // tahun sekarang (misal 2025)
        $this->metode = "Perbulan";
    }

    public function render()
    {
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
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            // lembur minimal 1 jam (kerja / libur)
            ->where(function ($q) {
                $q->where('yfrekappresensis.total_jam_lembur', '>', 0)
                    ->orWhere('yfrekappresensis.total_jam_lembur_libur', '>', 0);
            });


        // 🔥 Hitung total karyawan lembur >= 20 jam
        $total_karyawan_lembur = (clone $baseQuery)
            ->select('yfrekappresensis.user_id')
            ->selectRaw('SUM(total_jam_lembur + total_jam_lembur_libur) AS total_jam')
            ->groupBy('yfrekappresensis.user_id')
            ->having('total_jam', '>=', 20)
            ->get()
            ->count();


        // 🔥 Ambil data untuk tabel
        $karyawan_lembur = $baseQuery
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
            ->selectRaw('SUM(total_jam_lembur + total_jam_lembur_libur) AS total')
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
            ->having('total', '>=', 20)
            ->orderBy('total', 'desc')
            ->paginate($this->perpage);


        $this->periodeLembur = "( " . nama_bulan($this->month) . " " . $this->year . " )";


        return view(
            'livewire.karyawan-lembur',
            [
                'karyawan_lembur' => $karyawan_lembur,
                'total_karyawan_lembur' => $total_karyawan_lembur,
            ]
        );
    }
}
