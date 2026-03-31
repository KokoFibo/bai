<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class UserMobile extends Component
{
    use WithPagination;

    public $total_hari_kerja;
    public $total_jam_kerja;
    public $total_jam_lembur;
    public $total_keterlambatan;
    public  $selectedMonth;
    public  $selectedYear;
    public $user_id;
    public $data_payroll;
    public $is_slipGaji = false;
    public $data_karyawan;
    public $total_tambahan_shift_malam;
    public $tambahan_shift_malam;
    public $cx;
    public $isEmergencyContact;
    public $isEtnis;
    public $select_month, $select_year;
    public $latest_month, $latest_year;
    public $is_detail;
    public $show;


    // public function close () {
    //     $this->is_slipGaji = false;
    // }


    public function UpdatedSelectedYear()
    {

        if ($this->is_detail) {

            $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))
                ->whereYear('date', $this->selectedYear)
                ->distinct()
                ->pluck('month')
                ->toArray();
            $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->pluck('year')
                ->toArray();
        } else {
            $this->select_month = Yfrekappresensi::select(DB::raw('MONTH(date) as month'))
                ->whereYear('date', $this->selectedYear)
                ->distinct()
                ->pluck('month')
                ->toArray();
            $this->select_year = Yfrekappresensi::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->pluck('year')
                ->toArray();
        }
    }

    public function slip_gaji()
    {
        // dd($this->selectedMonth, $this->selectedYear);
        // kkk
        $dataPayroll = Payroll::orderBy('date', 'desc')->first();
        $this->selectedMonth = \Carbon\Carbon::parse($dataPayroll->date)->format('m');
        $this->selectedYear = \Carbon\Carbon::parse($dataPayroll->date)->format('Y');
        $this->selectedMonth = (int)$this->selectedMonth;


        $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))
            ->whereYear('date', $this->selectedYear)
            ->distinct()
            ->pluck('month')
            ->toArray();
        $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        // kll
        $this->data_payroll = Payroll::with('jamkerjaid')->whereMonth('date', $this->selectedMonth)

            ->whereYear('date', $this->selectedYear)
            ->where('id_karyawan', $this->user_id)->first();
        $this->data_karyawan = Karyawan::where('id_karyawan', $this->user_id)->first();
        // plk


        if ($this->data_payroll != null) {
            $this->is_slipGaji = true;
            // $this->dataKaryawanArr = [
            //     'ID Karyawan' => $this->id_karyawan,
            //     'Nama' => $this->nama,
            // ];
            $this->is_detail = true;
        }
    }

    public function detail_gaji()
    {
        $dataYfrekappresensi = Yfrekappresensi::orderBy('date', 'desc')->first();
        $this->selectedMonth = \Carbon\Carbon::parse($dataYfrekappresensi->date)->format('m');
        $this->selectedYear = \Carbon\Carbon::parse($dataYfrekappresensi->date)->format('Y');
        $this->selectedMonth = (int)$this->selectedMonth;

        $this->is_detail = false;
        $this->select_month = Yfrekappresensi::select(DB::raw('MONTH(date) as month'))
            ->whereYear('date', $this->selectedYear)
            ->distinct()
            ->pluck('month')
            ->toArray();
        $this->select_year = Yfrekappresensi::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();
    }


    public function mount()
    {
        $data_lock = Lock::find(1);

        if ($data_lock->slip_gaji == 1) {
            $this->is_slipGaji = false;
        } else {
            $this->is_slipGaji = true;
        }
        $is_detail = false;
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->isEmergencyContact = false;
        $this->isEtnis = false;

        $this->select_month = Yfrekappresensi::select(DB::raw('MONTH(date) as month'))
            ->distinct()
            ->pluck('month')
            ->toArray();
        $this->select_year = Yfrekappresensi::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();
        $dataYfrekappresensi = Yfrekappresensi::orderBy('date', 'desc')->first();
        $this->selectedMonth = \Carbon\Carbon::parse($dataYfrekappresensi->date)->format('m');
        $this->selectedYear = \Carbon\Carbon::parse($dataYfrekappresensi->date)->format('Y');
        $this->selectedMonth = (int)$this->selectedMonth;

        $this->latest_month = $this->selectedMonth;
        $this->latest_year = $this->selectedYear;

        $this->show = false;
    }

    public function clear_data()
    {
        $this->total_hari_kerja = 0;
        $this->total_jam_kerja = 0;
        $this->total_jam_lembur = 0;
        $this->total_keterlambatan = 0;
        $this->total_tambahan_shift_malam = 0;
    }

    public function render()
    {
        if ($this->isEmergencyContact && $this->isEtnis) {
            $this->show = true;
        }
        if (
            auth()->user()->role == 10000 ||
            auth()->user()->role == 20000 ||
            auth()->user()->role == 30000 ||
            auth()->user()->role == 40000 ||
            auth()->user()->role == 50000
        ) {
            $this->show = true;
        }

        // $this->user_id = 103;
        // $this->user_id = 3283;
        // $this->user_id = 1070;
        $this->user_id = auth()->user()->username;
        // $selectedMonth = 11;

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $tambahan_shift_malam = 0;
        $langsungLembur = 0;
        $total_tambahan_shift_malam = 0;


        $this->clear_data();

        $data_karyawan = Karyawan::where('id_karyawan', $this->user_id)->first();
        if ($data_karyawan != null) {
            if ($data_karyawan->kontak_darurat && $data_karyawan->hp1)  $this->isEmergencyContact = true;
            if ($data_karyawan->etnis)  $this->isEtnis = true;
        }
        if ($this->isEmergencyContact && $this->isEtnis) $this->show = true;
        $data = Yfrekappresensi::where('user_id', $this->user_id)
            ->whereMonth('date', $this->selectedMonth)
            ->whereYear('date', $this->selectedYear)
            ->where('no_scan', null)
            // ->orderBy('date', 'desc')->simplePaginate(5);
            ->orderBy('date', 'desc')->get();

        $data1 = Yfrekappresensi::where('user_id', $this->user_id)
            ->whereMonth('date', $this->selectedMonth)
            ->whereYear('date', $this->selectedYear)
            ->get();

        foreach ($data1 as $d) {
            if ($d->no_scan == null) {

                $tgl = tgl_doang($d->date);
                $tambahan_shift_malam = 0;
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));

                if ($d->karyawan->jabatan === 'Satpam') {
                    $jam_kerja = ($terlambat >= 6) ? 0.5 : $jam_kerja;
                }

                $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan, get_placement($d->user_id));


                if (is_sunday($d->date)) {
                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 * 2
                        + $langsungLembur * 2;
                } else {
                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                }

                if ($d->shift == 'Malam') {
                    if (is_saturday($d->date)) {
                        if ($jam_kerja >= 6) {
                            // $jam_lembur = $jam_lembur + 1;
                            $tambahan_shift_malam = 1;
                        }
                    } else if (is_sunday($d->date)) {
                        if ($jam_kerja >= 16) {
                            // $jam_lembur = $jam_lembur + 2;
                            $tambahan_shift_malam = 1;
                        }
                    } else {
                        if ($jam_kerja >= 8) {
                            // $jam_lembur = $jam_lembur + 1;
                            $tambahan_shift_malam = 1;
                        }
                    }
                }

                if (($jam_lembur >= 9) && (is_sunday($d->date) == false) && ($d->karyawan->jabatan != 'Driver')) {
                    $jam_lembur = 0;
                }
                if ($d->karyawan->placement == 'YIG' || $d->karyawan->placement == 'YSM' || $d->karyawan->jabatan == 'Satpam') {
                    if (is_friday($d->date)) {
                        $jam_kerja = 7.5;
                    } elseif (is_saturday($d->date)) {
                        $jam_kerja = 6;
                    } else {
                        $jam_kerja = 8;
                    }

                    if ($d->karyawan->jabatan == 'Satpam' && is_sunday($d->date)) {
                        $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    }
                    if ($d->karyawan->jabatan == 'Satpam' && is_saturday($d->date)) {
                        // $jam_lembur = 0;
                    }
                }


                // if (is_sunday($d->date) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                //     $jam_lembur = $jam_kerja;
                //     $jam_kerja = 0;
                // }


                if ($d->karyawan->jabatan != 'Translator') {
                    if (
                        is_libur_nasional($d->date) &&  !is_sunday($d->date)
                        && $d->karyawan->jabatan != 'Translator'

                    ) {
                        $jam_kerja *= 2;
                        $jam_lembur *= 2;
                    }
                } else {
                    if (is_sunday($d->date)) {
                        $jam_kerja /= 2;
                        $jam_lembur /= 2;
                    }
                }
                // khusus placement YAM yev yev... tgl 2024-04-07 dan 2024-04-09  
                $rule1 = ($d->date == '2024-04-07' || $d->date == '2024-04-09') &&  (substr($d->karyawan->placement, 0, 3) == "YEV" || $d->karyawan->placement == 'YAM');
                if ($rule1) {
                    $jam_kerja /= 2;
                    $jam_lembur /= 2;
                }



                $this->total_hari_kerja++;

                if ((is_sunday($d->date) || is_libur_nasional($d->date)) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                    $this->total_hari_kerja--;
                }



                $this->total_jam_kerja = $this->total_jam_kerja + $jam_kerja;
                $this->total_jam_lembur = $this->total_jam_lembur + $jam_lembur;
                $this->total_keterlambatan = $this->total_keterlambatan + $terlambat;
                $this->total_tambahan_shift_malam = $this->total_tambahan_shift_malam + $tambahan_shift_malam;
            }
        }
        $lanjut = false;
        foreach ($data1 as $d) {
            if ($d->no_scan != 'No Scan') $lanjut = true;
        }

        $this->data_payroll = Payroll::with('jamkerjaid')

            // block code dibawah ini untuk BISA tampilkan slip gaji bulan lalu
            ->whereMonth('date', $this->selectedMonth)
            ->whereYear('date', $this->selectedYear)

            // block code dibawah ini untuk TIDAK BISA tampilkan slip gaji bulan lalu
            // ->whereMonth('date', $this->latest_month)
            // ->whereYear('date', $this->latest_year)

            ->where('id_karyawan', $this->user_id)->first();
        $this->data_karyawan = Karyawan::where('id_karyawan', $this->user_id)->first();


        return view('livewire.user-mobile', compact(['data', 'lanjut']))->layout('layouts.polos');
    }
}
