<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Yfrekappresensi;

class Placementreport extends Component
{
    public $placement_id, $last_date;
    public $jumlah_karyawan, $jumlah_laki_laki, $jumlah_perempuan, $jumlah_shift_malam;
    public $shift_pagi_produksi, $shift_pagi_quality_control, $shift_pagi_gudang, $shift_pagi_engineering, $shift_pagi_ga, $shift_pagi_exim, $shift_pagi_bd, $shift_pagi_procurement, $shift_pagi_total;
    public $shift_malam_produksi, $shift_malam_quality_control, $shift_malam_gudang, $shift_malam_engineering, $shift_malam_ga, $shift_malam_exim, $shift_malam_bd, $shift_malam_procurement, $shift_malam_total;
    public $resign_produksi, $resign_quality_control, $resign_gudang, $resign_engineering, $resign_ga, $resign_exim, $resign_bd, $resign_procurement, $resign_total;
    public $blacklist_produksi, $blacklist_quality_control, $blacklist_gudang, $blacklist_engineering, $blacklist_ga, $blacklist_exim, $blacklist_bd, $blacklist_procurement, $blacklist_total;
    public $baru_produksi, $baru_quality_control, $baru_gudang, $baru_engineering, $baru_ga, $baru_exim, $baru_bd, $baru_procurement, $baru_total;
    public $karyawan_lebih_1_tahun, $karyawan_3_12_bulan, $karyawan_dibawah_3_bulan;
    public $placements;
    public function mount()
    {

        // $this->placement_id = 'YCME';
        $this->last_date = Yfrekappresensi::latest('date')->value('date');
        $this->placements = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->pluck('placement_id')->unique();
    }

    public function close()
    {
        $this->placement_id = '';
    }

    public function doQuery()
    {
        $this->jumlah_karyawan = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('placement_id', $this->placement_id)
            ->whereIn('department_id', [10, 11, 6, 2, 5, 3, 1, 9])
            ->count();

        $this->jumlah_shift_malam = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('placement_id', $this->placement_id)
            ->whereIn('department_id', [10, 11, 6, 2, 5, 3, 1, 9])
            ->count();

        $this->jumlah_laki_laki = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('gender', 'Laki-laki')
            ->where('placement_id', $this->placement_id)
            ->whereIn('department_id', [10, 11, 6, 2, 5, 3, 1, 9])
            ->count();
        $this->jumlah_perempuan = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('gender', 'Perempuan')
            ->where('placement_id', $this->placement_id)
            ->whereIn('department_id', [10, 11, 6, 2, 5, 3, 1, 9])
            ->count();

        $this->shift_pagi_produksi = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 10)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_produksi = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 10)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_quality_control = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 11)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_quality_control = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 11)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_gudang = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 6)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_gudang = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 6)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_engineering = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 2)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_engineering = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 2)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_ga = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 5)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_ga = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 5)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_exim = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 3)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_exim = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 3)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_bd = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 1)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_bd = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 1)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_procurement = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Pagi')
            ->where('department_id', 9)
            ->where('placement_id', $this->placement_id)
            ->count();
        $this->shift_malam_procurement = Yfrekappresensi::join('karyawans', 'karyawans.id', 'yfrekappresensis.karyawan_id')
            ->where('date', $this->last_date)
            ->where('shift', 'Malam')
            ->where('department_id', 9)
            ->where('placement_id', $this->placement_id)
            ->count();

        $this->shift_pagi_total = $this->shift_pagi_produksi + $this->shift_pagi_quality_control + $this->shift_pagi_gudang + $this->shift_pagi_engineering +
            $this->shift_pagi_ga + $this->shift_pagi_exim + $this->shift_pagi_bd + $this->shift_pagi_procurement;
        $this->shift_malam_total = $this->shift_malam_produksi + $this->shift_malam_quality_control + $this->shift_malam_gudang + $this->shift_malam_engineering +
            $this->shift_malam_ga + $this->shift_malam_exim + $this->shift_malam_bd + $this->shift_malam_procurement;

        // Karywawan Resign
        $this->resign_produksi = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 10)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_quality_control = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 11)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_gudang = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 6)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_engineering = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 2)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_ga = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 5)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_exim = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 3)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_bd = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 1)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_procurement = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 9)
            ->where('tanggal_resigned', $this->last_date)
            ->count();
        $this->resign_total = $this->resign_produksi + $this->resign_quality_control + $this->resign_gudang + $this->resign_engineering + $this->resign_ga + $this->resign_exim + $this->resign_bd + $this->resign_procurement + $this->resign_total;

        // Karywawan Blacklist
        $this->blacklist_produksi = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 10)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_quality_control = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 11)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_gudang = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 6)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_engineering = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 2)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_ga = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 5)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_exim = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 3)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_bd = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 1)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_procurement = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 9)
            ->where('tanggal_blacklist', $this->last_date)
            ->count();
        $this->blacklist_total = $this->blacklist_produksi + $this->blacklist_quality_control + $this->blacklist_gudang + $this->blacklist_engineering + $this->blacklist_ga + $this->blacklist_exim + $this->blacklist_bd + $this->blacklist_procurement + $this->blacklist_total;

        // Karywawan Baru
        $this->baru_produksi = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 10)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_quality_control = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 11)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_gudang = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 6)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_engineering = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 2)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_ga = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 5)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_exim = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 3)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_bd = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 1)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_procurement = Karyawan::where('placement_id', $this->placement_id)
            ->where('department_id', 9)
            ->where('tanggal_bergabung', $this->last_date)
            ->count();
        $this->baru_total =
            $this->baru_produksi + $this->baru_quality_control + $this->baru_gudang + $this->baru_engineering + $this->baru_ga + $this->baru_exim + $this->baru_bd + $this->baru_procurement + $this->baru_total;

        // card yg di paling bawah
        $this->karyawan_lebih_1_tahun = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where('placement_id', $this->placement_id)
            ->whereDate('tanggal_bergabung', '<=', now()->subYears(1))
            ->count();
        $this->karyawan_3_12_bulan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where('placement_id', $this->placement_id)
            ->whereDate('tanggal_bergabung', '>=', now()->subMonths(12)) // Joined within the last 12 months
            ->whereDate('tanggal_bergabung', '<=', now()->subMonths(3))
            ->count();
        $this->karyawan_dibawah_3_bulan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where('placement_id', $this->placement_id)
            ->where('tanggal_bergabung', '>=', now()->subMonths(3)->format('Y-m-d'))
            ->count();
    }


    public function render()
    {
        if ($this->placement_id != '') {
            $this->doQuery();
        }
        return view('livewire.placementreport');
    }
}
