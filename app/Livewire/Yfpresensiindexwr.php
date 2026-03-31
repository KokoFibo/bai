<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Harikhusus;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use App\Models\Yfrekappresensi;
use Livewire\Attributes\Computed;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class Yfpresensiindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = null;
    public $columnName = 'no_scan_history';
    public $direction = 'desc';
    public $first_in;
    public $first_out;
    public $second_in;
    public $second_out;
    public $overtime_in;
    public $overtime_out;
    public $tanggal;
    public $shift;
    public $date;
    public $user_id;
    public $name;
    public $id;
    public $no_scan = null;
    public $late = null;
    public $btnEdit = true;
    public $selectedId;
    public $perpage = 10;
    public $location = 'All';
    public $late_user_id;
    // variable utk show detail data karyawan
    public $dataArr = [];
    public $total_hari_kerja;
    public $total_jam_kerja;
    public $total_jam_lembur;
    public $total_keterlambatan;
    public $total_tambahan_shift_malam;
    public $paginatedData;
    public $data1;
    public $month;
    public $year;
    public $bulan;
    public $tahun;
    public $lock_presensi;
    public $data_kosong;
    public $is_noscan, $is_kosong;



    public $totalHadir, $totalHadirPagi, $overallNoScan, $totalNoScan;
    public $totalNoScanPagi, $totalLate, $totalLatePagi, $overtime, $overtimePagi, $absensiKosong;

    public function delete_presensi_kosong()
    {
        Yfrekappresensi::whereNull('first_in')
            ->whereNull('first_out')
            ->whereNull('second_in')
            ->whereNull('second_out')
            ->whereNull('overtime_in')
            ->whereNull('overtime_out')
            ->delete();
        $this->dispatch('success', message: 'Data Presensi Kosong Sudah di Delete');
    }

    public function prev()
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', $this->tanggal)->subDay();
        $this->tanggal = $tanggal->format('Y-m-d');
        $this->lock_presensi = $this->getLockPresensi($tanggal);
        $this->bulan = Carbon::parse($tanggal)->format('m');
        $this->tahun = Carbon::parse($tanggal)->format('Y');
    }
    public function next()
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', $this->tanggal)->addDay();
        $this->tanggal = $tanggal->format('Y-m-d');
        $this->lock_presensi = $this->getLockPresensi($tanggal);
        $this->bulan = Carbon::parse($tanggal)->format('m');
        $this->tahun = Carbon::parse($tanggal)->format('Y');
    }
    public function check_no_scan()
    {
        $this->overallNoScan = Yfrekappresensi::where('no_scan', 'No Scan')->count();

        if ($this->tanggal == null) {
            $bulan_no_scan = now()->month;
        } else {
            $bulan_no_scan = Carbon::parse($this->tanggal)->month;
        }

        $this->totalNoScan = Yfrekappresensi::where('no_scan', '!=', null)
            ->whereMonth('date', $bulan_no_scan)
            ->count();

        $this->totalNoScanPagi = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('shift', 'Pagi')
            ->whereMonth('date', $bulan_no_scan)
            // ->where('date', '=', $this->tanggal)
            ->count();
    }

    public function mount()
    {
        try {
            $data = Yfrekappresensi::latest('date')->first();
            $month = \Carbon\Carbon::parse($data->date)->month;
            $year = \Carbon\Carbon::parse($data->date)->year;
            // dd($data->date, $month, $year);
            $this->year = now()->year;
            $this->month = now()->month;
            $this->bulan = now()->month;
            $this->tahun = now()->year;
            $this->lock_presensi = $this->getLockPresensi($data->date);

            $this->is_noscan = false;
            $this->is_kosong = false;
        } catch (\Exception $e) {
            dd($data->karyawan_id);
            return $e->getMessage();
        }
        $this->totalHadir = Yfrekappresensi::query()
            ->where('date', '=', $this->tanggal)
            ->count();
        $this->totalHadirPagi = Yfrekappresensi::where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();

        // $this->check_no_scan();


        // $this->overallNoScan = Yfrekappresensi::where('no_scan', 'No Scan')->count();

        // if ($this->tanggal == null) {
        //     $bulan_no_scan = now()->month;
        // } else {
        //     $bulan_no_scan = Carbon::parse($this->tanggal)->month;
        // }

        // $this->totalNoScan = Yfrekappresensi::where('no_scan', '!=', null)
        //     ->whereMonth('date', $bulan_no_scan)
        //     ->count();

        // $this->totalNoScanPagi = Yfrekappresensi::where('no_scan', 'No Scan')
        //     ->where('shift', 'Pagi')
        //     ->whereMonth('date', $bulan_no_scan)
        //     // ->where('date', '=', $this->tanggal)
        //     ->count();

        $this->totalLate = Yfrekappresensi::where('late', '>', '0')
            ->where('date', '=', $this->tanggal)
            ->count();
        $this->totalLatePagi = Yfrekappresensi::where('late', '>', '0')
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $this->overtime = Yfrekappresensi::where('overtime_in', '!=', null)
            ->where('date', '=', $this->tanggal)
            ->count();
        $this->overtimePagi = Yfrekappresensi::where('overtime_in', '!=', null)
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $this->absensiKosong =  $data = Yfrekappresensi::where('first_in', null)
            ->where('first_out', null)
            ->where('second_in', null)
            ->where('second_out', null)
            ->where('overtime_in', null)
            ->where('overtime_out', null)
            ->count();
    }

    public function delete_no_scan($id)
    {
        $data = Yfrekappresensi::find($id);
        if ($data->no_scan == "") {
            $data->no_scan_history = null;
            $data->save();
            // $this->dispatch('success', message: 'No Scan History sudah di delete');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'No Scan History sudah di delete',
            );
        } else {
            // $this->dispatch('error', message: 'No Scan harus di bersihkan dulu');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'No Scan harus di bersihkan dulu',
            );
        }
    }
    public function getLockPresensi($tanggal1)
    {
        $tanggal = Carbon::parse($tanggal1);

        $currentDate = now();
        // Calculate the start of the last month
        $startLastMonth = $currentDate->copy()->subMonthNoOverflow()->firstOfMonth();
        // Calculate the end of the last month
        $endLastMonth = $currentDate->copy()->subMonthNoOverflow()->lastOfMonth();

        if ($tanggal->lessThan($startLastMonth)) {
            $this->lock_presensi = true;
        } else {
            $lock = Lock::find(1);
            // if (now()->day < 7 && $lock->presensi == false) $this->lock_presensi = 0;
            // Rubah angka tgl 10 utk menentukan batas akhir lock bulan lalu otomatis
            if (now()->day > 10 && $lock->presensi == false) $this->lock_presensi = 1;
            else $this->lock_presensi = $lock->presensi;
        }
        // if ($tanggal->month == now()->month && $tanggal->year == now()->year) dd('ok');
        // $this->lock_presensi == false;
        $parsedTanggal = Carbon::parse($tanggal);
        if ($parsedTanggal->isSameMonth(now()) && $parsedTanggal->isSameYear(now())) $this->lock_presensi = false;

        return  $this->lock_presensi;
    }

    public function updatedTanggal($nilai_tanggal)
    {
        $this->lock_presensi = $this->getLockPresensi($nilai_tanggal);
        $this->bulan = Carbon::parse($nilai_tanggal)->format('m');
        $this->tahun = Carbon::parse($nilai_tanggal)->format('Y');
    }

    // ok1
    public function submitPresensiDetail($user_id)
    {
        $this->showDetail($user_id);
    }


    #[On('delete')]
    public function delete($id)
    {
        Yfrekappresensi::find($id)->delete();
        // $this->dispatch('success', message: 'Data Presensi Sudah di Delete');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Presensi Sudah di Delete',
        );
    }

    //ok1
    public function showDetail($user_id)
    {
        $this->user_id = $user_id;

        $name_karyawan = Karyawan::where('id_karyawan', $user_id)->select('nama')->first();
        $this->name = optional($name_karyawan)->nama;

        $this->dataArr = collect();
        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;
        $tambahan_shift_malam = 0;
        $total_tambahan_shift_malam = 0;



        $data = Yfrekappresensi::with('karyawan')->where('user_id', $user_id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->get();
        //ok2
        if ($data != null) {
            foreach ($data as $d) {

                $tambahan_shift_malam = 0;
                if ($d->no_scan === null) {
                    $tgl = tgl_doang($d->date);
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id));
                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id));
                    // if ($d->date == '2025-05-30') dd($d->user_id, $jam_kerja, $terlambat, $d->date);

                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan_id, $d->karyawan->placement_id);
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
                    // 22 driver
                    if (($jam_lembur >= 9) && (is_sunday($d->date) == false) && ($d->karyawan->jabatan_id != 22)) {
                        $jam_lembur = 0;
                    }
                    // yig = 12, ysm = 13
                    if ($d->karyawan->placement_id == 12 || $d->karyawan->placement_id == 13 || $d->karyawan->jabatan_id == 17) {
                        if (is_friday($d->date)) {
                            $jam_kerja = 7.5;
                        } elseif (is_saturday($d->date)) {
                            $jam_kerja = 6;
                        } else {
                            $jam_kerja = 8;
                        }
                    }
                    if ($d->karyawan->jabatan_id == 17 && is_sunday($d->date)) {
                        $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id));
                    }
                    if ($d->karyawan->jabatan_id == 17 && is_saturday($d->date)) {
                        // $jam_lembur = 0;
                    }
                    // 23 translator
                    if ($d->karyawan->jabatan_id != 23) {
                        if (
                            is_libur_nasional($d->date) &&  !is_sunday($d->date)
                            && $d->karyawan->jabatan_id != 23

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


                    $table_warning = false;
                    if (is_sunday($d->date) || is_libur_nasional($d->date)) {
                        $table_warning = true;
                    }
                    $this->dataArr->push([
                        'tgl' => $tgl,
                        'jam_kerja' => $jam_kerja,
                        'terlambat' => $terlambat,
                        'jam_lembur' => $jam_lembur,
                        'tambahan_shift_malam' => $tambahan_shift_malam,
                        'table_warning' => $table_warning,

                    ]);

                    $total_hari_kerja++;

                    if ((is_sunday($d->date) || is_libur_nasional($d->date)) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                        $total_hari_kerja--;
                    }

                    $total_jam_kerja += $jam_kerja;
                    $total_jam_lembur += $jam_lembur;
                    $total_keterlambatan += $terlambat;
                    $total_tambahan_shift_malam += $tambahan_shift_malam;
                }
            }
            $this->total_hari_kerja = $total_hari_kerja;
            $this->total_jam_kerja = $total_jam_kerja;
            $this->total_jam_lembur = $total_jam_lembur;
            $this->total_keterlambatan = $total_keterlambatan;
            $this->total_tambahan_shift_malam = $total_tambahan_shift_malam;
        }
    }
    public function showDetail_baru($user_id)
    {
        $this->user_id = $user_id;

        $name_karyawan = Karyawan::where('id_karyawan', $user_id)->select('nama')->first();
        $this->name = optional($name_karyawan)->nama;

        $this->dataArr = collect();
        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;
        $tambahan_shift_malam = 0;
        $total_tambahan_shift_malam = 0;



        $data = Yfrekappresensi::with('karyawan')->where('user_id', $user_id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->get();
        //ok2
        if ($data != null) {
            foreach ($data as $d) {
                $tambahan_shift_malam = 0;
                if ($d->no_scan === null) {


                    $tgl = tgl_doang($d->date);

                    $jam_kerja = $d->total_jam_kerja;
                    $jam_kerja = $d->total_jam_kerja;
                    $terlambat = $d->late;
                    $jam_lembur = $d->total_jam_lembur;
                    //    $tambahan_shift_malam = $d->date

                    $table_warning = false;

                    if ($d->shift == 'Malam') {
                        $tgl_khusus = Harikhusus::where('date', $d->date)->first();
                        if ($tgl_khusus) {
                            // tanggal khusus
                            if (khusus_is_saturday($d->date)) {
                                if ($jam_kerja >= 6) {
                                    // $jam_lembur = $jam_lembur + 1;
                                    $tambahan_shift_malam = 1;
                                }
                            } else if (khusus_is_sunday($d->date)) {
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
                        } else {
                            // bukan Tanggal Khusus
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
                    }


                    if (is_sunday($d->date) || is_libur_nasional($d->date)) {
                        $table_warning = true;
                    }

                    $this->dataArr->push([
                        'tgl' => $tgl,
                        'jam_kerja' => $jam_kerja,
                        'terlambat' => $terlambat,
                        'jam_lembur' => $jam_lembur,
                        'tambahan_shift_malam' => $tambahan_shift_malam,
                        'table_warning' => $table_warning,

                    ]);

                    $total_hari_kerja++;

                    if ((is_sunday($d->date) || is_libur_nasional($d->date)) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                        $total_hari_kerja--;
                    }

                    $total_jam_kerja += $jam_kerja;
                    $total_jam_lembur += $jam_lembur;
                    $total_keterlambatan += $terlambat;
                    $total_tambahan_shift_malam += $tambahan_shift_malam;
                }
            }
            $this->total_hari_kerja = $total_hari_kerja;
            $this->total_jam_kerja = $total_jam_kerja;
            $this->total_jam_lembur = $total_jam_lembur;
            $this->total_keterlambatan = $total_keterlambatan;
            $this->total_tambahan_shift_malam = $total_tambahan_shift_malam;
        }
    }

    public function filterNoScan()
    {
        $this->resetPage();
        $this->is_kosong = false;
        $this->is_noscan = true;
    }

    public function filterKosong()
    {
        $this->is_noscan = false;
        $this->is_kosong = true;
        $this->resetPage();
    }


    public function filterLate()
    {
        $this->columnName = 'late_history';
        $this->direction = 'desc';
        $this->search = null;
        $this->resetPage();
        $this->render();
        $this->is_noscan = false;
        $this->is_kosong = false;
        $this->resetPage();
    }

    public function resetTanggal()
    {
        // ini harus di reset ke tanggal terakhir ver d M Y
        $this->is_noscan = false;
        $this->is_kosong = false;

        $this->tanggal = null;
        $this->columnName = 'no_scan_history';
        $this->direction = 'desc';
        $this->search = null;
        $this->resetPage();
        // $this->render();
    }

    public function update($id)
    {
        // dd('ok');
        $this->id = $id;
        $data = Yfrekappresensi::find($id);
        $this->first_in = trimTime($data->first_in);
        $this->first_out = trimTime($data->first_out);
        $this->second_in = trimTime($data->second_in);
        $this->second_out = trimTime($data->second_out);
        $this->overtime_in = trimTime($data->overtime_in);
        $this->overtime_out = trimTime($data->overtime_out);
        $this->late_user_id = $data->user_id;
        // $this->user_id = $data->user_id;
        // $this->name = $data->name;
        $this->shift = $data->shift;
        $this->date = $data->date;
        $this->btnEdit = false;
        $this->selectedId = $id;
    }

    public function save()
    {
        $this->validate([
            'first_in' => 'date_format:H:i|nullable',
            'first_out' => 'date_format:H:i|nullable',
            'second_in' => 'date_format:H:i|nullable',
            'second_out' => 'date_format:H:i|nullable',
            'overtime_in' => 'date_format:H:i|nullable',
            'overtime_out' => 'date_format:H:i|nullable',
        ]);
        // proses penambahan  00 untuk data yg ada isi dan null utk data kosong
        $this->first_in != null ? ($this->first_in = $this->first_in . ':00') : ($this->first_in = null);
        $this->first_out != null ? ($this->first_out = $this->first_out . ':00') : ($this->first_out = null);
        $this->second_in != null ? ($this->second_in = $this->second_in . ':00') : ($this->second_in = null);
        $this->second_out != null ? ($this->second_out = $this->second_out . ':00') : ($this->second_out = null);
        $this->overtime_in != null ? ($this->overtime_in = $this->overtime_in . ':00') : ($this->overtime_in = null);
        $this->overtime_out != null ? ($this->overtime_out = $this->overtime_out . ':00') : ($this->overtime_out = null);

        $data = Yfrekappresensi::find($this->id);

        $data->first_in = $this->first_in;
        $data->first_out = $this->first_out;
        $data->second_in = $this->second_in;
        $data->second_out = $this->second_out;
        $data->overtime_in = $this->overtime_in;
        $data->overtime_out = $this->overtime_out;
        $data->no_scan = noScan($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->overtime_out);
        $data->late = late_check_detail($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->shift, $this->date, $this->late_user_id);
        $data->late_history = $data->late;

        // jadwal puasa
        // dd($data->late);
        // ================================
        $is_saturday = is_saturday($data->date);
        if ($is_saturday) {
            // JIKA HARI SABTU kkk
            if (Carbon::parse($data->first_in)->betweenIncluded('05:30', '15:00')) {
                $data->shift = 'Pagi';
                // dd($data->shift, $is_saturday );
            } else {
                $data->shift = 'Malam';
                // dd($data->shift, $is_saturday );
            }
            if (($data->first_in == null && $data->first_out == null) && ($data->second_in != null && $data->second_out != null)) {
                if (Carbon::parse($data->second_in)->betweenIncluded('05:30', '15:00')) {
                    $data->shift = 'Pagi';
                    // dd($data->shift, $is_saturday );
                }
            }
        } else {
            // JIKA BUKAN HARI SABTU
            if (Carbon::parse($data->first_in)->betweenIncluded('05:30', '15:00')) {
                $data->shift = 'Pagi';
            } else {
                $data->shift = 'Malam';
                // dd($data->shift, $is_saturday );
            }

            if ($data->second_in != null) {

                if (($data->first_in == null && $data->first_out) &&
                    (Carbon::parse($data->second_in)->betweenIncluded('23:00', '23:59') || Carbon::parse($data->second_in)->betweenIncluded('00:00', '01:30'))
                ) {
                    $data->shift = 'Malam';
                }

                if (Carbon::parse($data->second_in)->betweenIncluded('11:00', '15:00')) {
                    $data->shift = 'Pagi';
                }
            }
        }

        $dataKaryawan = Karyawan::where('id_karyawan', $data->user_id)->first();
        $hasil = saveDetail($data->user_id, $data->first_in, $data->first_out, $data->second_in, $data->second_out, $data->late, $data->shift, $data->date, $dataKaryawan->jabatan_id, $data->no_scan, $dataKaryawan->placement_id, $data->overtime_in, $data->overtime_out);
        // dd($hasil['jam_kerja']);
        // if ($data->user_id == 2152) dd($data->user_id, $hasil['jam_kerja']);
        $data->total_hari_kerja = 0;
        $data->total_jam_kerja = 0;
        $data->total_jam_lembur = 0;

        if (isset($hasil['jam_kerja']) && $hasil['jam_kerja'] > 4) {
            $data->total_hari_kerja = 1;
        }

        if (isset($hasil['jam_kerja'])) {
            $data->total_jam_kerja = $hasil['jam_kerja'];
        }
        if (isset($hasil['jam_lembur'])) {
            $data->total_jam_lembur = $hasil['jam_lembur'];
        }

        // hitung tambahan shift malam

        if ($data->shift == 'Malam') {
            if (is_saturday($data->date)) {
                if ($data->total_jam_kerja >= 6) {
                    // $jam_lembur = $jam_lembur + 1;
                    $data->shift_malam = 1;
                }
            } else if (is_sunday($data->date)) {
                if ($data->total_jam_kerja >= 16) {
                    // $jam_lembur = $jam_lembur + 2;
                    $data->shift_malam = 1;
                }
            } else {
                if ($data->total_jam_kerja >= 8) {
                    // $jam_lembur = $jam_lembur + 1;
                    $data->shift_malam = 1;
                } else {
                    $data->shift_malam = 0;
                }
            }
        } else {
            $data->shift_malam = 0;
        }


        // khusus untuk security kode jabatan 17
        if ($dataKaryawan->jabatan_id == 17 && $data->shift == 'Malam') {
            if (is_sunday($data->date) || is_libur_nasional($data->date)) {
                $data->total_jam_kerja = min($data->total_jam_kerja, 16);
            } elseif (is_saturday($data->date)) {
                $data->total_jam_kerja = min($data->total_jam_kerja, 6);
            } else {
                $data->total_jam_kerja = min($data->total_jam_kerja, 8);
            }
        }
        // if ($data->date == '2025-07-11' && $data->user_id == 4125) {
        //     dd($data->date, $data->user_id, $data->total_jam_kerja, $data->shift_malam);
        // }
        // $setengah_hari = (
        //     ($data->first_in === null && $data->first_out !== null) ||
        //     ($data->second_in === null && $data->second_out === null)
        // );



        // if ($data->date === '2025-05-30' && !$setengah_hari) {


        // if ($data->date === '2025-05-30') {
        //     $data->late == null;
        //     $data->late_history = null;
        // }

        $data->save();
        $this->btnEdit = true;

        // $this->dispatch('success', message: 'Data sudah di update');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data sudah di update',
        );
        // $this->dispatch('hide-form');

    }

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }




    public function render()
    {
        $this->check_no_scan();

        // $this->tanggal = date( 'Y-m-d', strtotime( $this->tanggal ) );

        if ($this->tanggal == null) {
            $lastDate = Yfrekappresensi::orderBy('date', 'desc')->first();
            if ($lastDate == null) {
                $this->tanggal = null;
            } else {
                $this->tanggal = Carbon::parse($lastDate->date)->format('Y-m-d');
            }
        }

        if ($this->absensiKosong > 0) {
            $id_kosong = Yfrekappresensi::select('user_id')
                ->whereNull('first_in')
                ->whereNull('first_out')
                ->whereNull('second_in')
                ->whereNull('second_out')
                ->whereNull('overtime_in')
                ->whereNull('overtime_out')
                ->pluck('user_id')
                ->toArray();
            $this->data_kosong = implode(', ', $id_kosong);
        } else {
            $this->data_kosong = '';
        }




        // fill

        if ($this->is_noscan) {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.department_id', 'karyawans.jabatan_id'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->where('no_scan', 'No Scan')
                ->paginate($this->perpage);
        } elseif ($this->is_kosong) {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.department_id'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->whereNull('first_in')
                ->whereNull('first_out')
                ->whereNull('second_in')
                ->whereNull('second_out')
                ->whereNull('overtime_in')
                ->whereNull('overtime_out')
                ->paginate($this->perpage);
        } else {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.jabatan_id'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->join('jabatans', 'karyawans.jabatan_id', '=', 'jabatans.id')

                ->orderBy($this->columnName, $this->direction)
                // hilangin ini kalau ngaco            
                ->orderBy('user_id', 'asc')
                ->orderBy('date', 'asc')
                ->when($this->search == "", function ($query) {
                    $query
                        ->whereDate('date',  $this->tanggal);
                })
                ->when($this->search, function ($query) {
                    $query
                        // ->whereDate('date',  $this->tanggal);
                        // kedua ini diatur tergantung kebutuhan
                        ->whereMonth('date', $this->bulan)
                        ->whereYear('date', $this->tahun);
                })
                ->where(function ($query) {
                    $query->when($this->search, function ($subQuery) {
                        $subQuery
                            ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('user_id', trim($this->search))
                            // ->orWhere('departemen', 'LIKE', '%' . trim($this->search) . '%')
                            // ->orWhere('jabatan_id', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('nama_jabatan', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('placement_id', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
                        // ->whereMonth('date', $this->month)
                        // ->whereYear('date', $this->year);
                    });
                })
                ->paginate($this->perpage);
        }

        return view('livewire.yfpresensiindexwr', compact([
            'datas'
        ]));
    }
}
