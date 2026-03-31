<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Jabatan;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Placement;
use App\Models\Harikhusus;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;
use Google\Service\YouTube\ThirdPartyLinkStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;


class Newpresensi extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $tanggal;
    // public $sortField = 'user_id';
    // public $sortDirection = 'asc';
    public $sortField = 'no_scan_history';
    public $sortDirection = 'desc';
    public $search = '';
    public $placementFilter = '';
    public $jabatanFilter = '';
    public $noScan = 0;
    public $totalNoScan = 0;
    public $totalNoScanDaily  = 0;
    public $bulan, $tahun;
    public $editId;
    public $first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out;
    public $late_user_id, $shift, $date, $absensiKosong;
    public $is_kosong = false, $is_no_scan = false;
    public $rowsPerPage;
    public $showDetailModal = false;
    public $delete_no_scan_history = false, $is_delete = 0;


    // variable untuk menampilkan detail
    public $dataArr = [];
    public $total_hari_kerja;
    public $total_jam_kerja;
    public $total_jam_lembur;
    public $total_jam_kerja_libur;
    public $total_jam_lembur_libur;
    public $total_keterlambatan;
    public $total_tambahan_shift_malam;
    public $month, $year, $user_id, $name;
    public $is_presensi_locked, $is_sunday, $is_hari_libur_nasional, $is_friday;
    public $show_name, $show_id, $delete_id;
    public $total_hari_kerja_libur;

    public $placements, $jabatans, $bulan_terakhir;

    public function delete_no_scan()
    {
        // Yfrekappresensi::whereNot('no_scan_history', null)->delete();
        $data = Yfrekappresensi::WhereMonth('date', $this->month)->WhereYear('date', $this->year)->whereNot('no_scan_history', null)->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'No Scan History sudah di delete',
        );
    }

    public function delete_no_scan1()
    {
        $data = Yfrekappresensi::find($this->delete_id);
        // dd($this->delete_id, $data);
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
        $this->delete_no_scan_history = false;
    }



    public function reload_placement_jabatan()
    {
        $this->placements = DB::table('placements')
            ->select('placements.id', 'placements.placement_name')
            ->join('karyawans', 'karyawans.placement_id', '=', 'placements.id')
            ->join('yfrekappresensis', 'yfrekappresensis.user_id', '=', 'karyawans.id_karyawan')
            ->whereMonth('yfrekappresensis.date', $this->month)
            ->whereYear('yfrekappresensis.date', $this->year)
            ->groupBy('placements.id', 'placements.placement_name')
            ->orderBy('placements.placement_name')
            ->get();

        $this->jabatans = DB::table('jabatans')
            ->select('jabatans.id', 'jabatans.nama_jabatan')
            ->join('karyawans', 'karyawans.jabatan_id', '=', 'jabatans.id')
            ->join('yfrekappresensis', 'yfrekappresensis.user_id', '=', 'karyawans.id_karyawan')
            // Jika placementFilter == 'all', jangan filter placement_id
            ->when($this->placementFilter && $this->placementFilter !== '', function ($query) {
                $query->where('karyawans.placement_id', $this->placementFilter);
            })
            ->whereMonth('yfrekappresensis.date', $this->month)
            ->whereYear('yfrekappresensis.date', $this->year)
            ->groupBy('jabatans.id', 'jabatans.nama_jabatan')
            ->orderBy('jabatans.nama_jabatan')
            ->get();



        // Dropdown jabatan berdasarkan placement (tanpa kolom placement_id)
        // $this->jabatans = Jabatan::whereIn('id', function ($q) {
        //     $q->select('jabatan_id')
        //         ->from('karyawans')
        //         ->when($this->placementFilter, function ($sub) {
        //             $sub->where('placement_id', $this->placementFilter);
        //         })
        //         ->whereNotNull('jabatan_id');
        // })
        //     ->orderBy('nama_jabatan')
        //     ->get();
    }


    public function check_presensi_locked()
    {
        $data = Lock::find(1);
        return $data->presensi;
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
        $total_jam_kerja_libur = 0;
        $total_jam_lembur_libur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;
        $tambahan_shift_malam = 0;
        $total_tambahan_shift_malam = 0;
        $total_hari_kerja_libur = 0;

        $data = Yfrekappresensi::with('karyawan')->where('user_id', $user_id)
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'asc')
            ->get();
        //ok2
        if ($data != null) {
            foreach ($data as $d) {
                $tambahan_shift_malam = 0;
                if ($d->no_scan === null) {


                    // $tgl = tgl_doang($d->date);
                    $tgl = tgl_lengkap($d->date);

                    $jam_kerja = $d->total_jam_kerja;
                    $jam_lembur = $d->total_jam_lembur;
                    $jam_kerja_libur = $d->total_jam_kerja_libur;
                    $jam_lembur_libur = $d->total_jam_lembur_libur;
                    $hari_kerja_libur = $d->total_hari_kerja_libur;
                    $terlambat = $d->late;

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
                        // if (Carbon::parse($d->date)->isSunday() || is_libur_nasional($d->date)) {
                        // if (Carbon::parse($d->date)->isSunday()) {
                        $table_warning = true;
                    }

                    $this->dataArr->push([
                        'tgl' => $tgl,
                        'jam_kerja' => $jam_kerja,
                        'terlambat' => $terlambat,
                        'jam_lembur' => $jam_lembur,
                        'jam_kerja_libur' => $jam_kerja_libur,
                        'jam_lembur_libur' => $jam_lembur_libur,
                        'tambahan_shift_malam' => $tambahan_shift_malam,
                        'table_warning' => $table_warning,
                        'hari_kerja_libur' => $hari_kerja_libur,

                    ]);

                    $total_hari_kerja++;

                    // if ((is_sunday($d->date) || is_libur_nasional($d->date)) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                    //     $total_hari_kerja--;
                    // }

                    $total_jam_kerja += $jam_kerja;
                    $total_jam_lembur += $jam_lembur;
                    $total_jam_kerja_libur += $jam_kerja_libur;
                    $total_jam_lembur_libur += $jam_lembur_libur;
                    $total_keterlambatan += $terlambat;
                    $total_tambahan_shift_malam += $tambahan_shift_malam;
                    $total_hari_kerja_libur += $hari_kerja_libur;
                }
            }
            $this->total_hari_kerja = $total_hari_kerja;
            $this->total_jam_kerja = $total_jam_kerja;
            $this->total_jam_lembur = $total_jam_lembur;
            $this->total_jam_kerja_libur = $total_jam_kerja_libur;
            $this->total_jam_lembur_libur = $total_jam_lembur_libur;
            $this->total_keterlambatan = $total_keterlambatan;
            $this->total_tambahan_shift_malam = $total_tambahan_shift_malam;
            $this->total_hari_kerja_libur = $total_hari_kerja_libur;
        }
    }

    public function checkData()
    {
        $this->noScan = Yfrekappresensi::where('date', $this->tanggal)->where('no_scan', 'No Scan')->count();
        $this->totalNoScan = Yfrekappresensi::whereMonth('date', $this->bulan)->whereYear('date', $this->tahun)->where('no_scan', 'No Scan')->count();
        $this->totalNoScanDaily = Yfrekappresensi::where('date', $this->tanggal)->where('no_scan', 'No Scan')->count();
        $this->absensiKosong =  Yfrekappresensi::where('first_in', null)
            ->where('first_out', null)
            ->where('second_in', null)
            ->where('second_out', null)
            ->where('overtime_in', null)
            ->where('overtime_out', null)
            ->count();
    }


    public function check_no_scan()
    {
        $this->overallNoScan = Yfrekappresensi::where('no_scan', 'No Scan')
            ->whereMonth('date', Carbon::parse($this->tanggal)->month)
            ->whereYear('date', Carbon::parse($this->tanggal)->year)
            ->count();
    }

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

    public function filterKosong()
    {
        $this->is_kosong = true;
        $this->resetPage();
    }
    public function filterNoScan()
    {
        $this->is_no_scan = true;
        $this->resetPage();
    }

    public function edit($id)
    {
        $data = Yfrekappresensi::find($id);

        if ($data) {
            $this->editId = $data->id;
            $this->first_in = $data->first_in;
            $this->first_out = $data->first_out;
            $this->second_in = $data->second_in;
            $this->second_out = $data->second_out;
            $this->overtime_in = $data->overtime_in;
            $this->overtime_out = $data->overtime_out;

            $this->late_user_id = $data->user_id;
            $this->shift = $data->shift;
            $this->date = $data->date;
            // if ($data->no_scan_history == 'No Scan' && $data->no_scan == '') {
            if ($data->no_scan_history == 'No Scan') {
                $this->delete_no_scan_history = true;
            } else {
                $this->delete_no_scan_history = false;
            }

            $this->show_name = getName($data->user_id);
            $this->show_id = $data->user_id;
            $this->delete_id = $data->id;

            // 🔥 Tampilkan modal lewat event browser
            $this->dispatch('show-edit-modal');
        }
    }

    public function update1()
    {
        $data = Yfrekappresensi::find($this->editId);

        if ($data) {
            $data->update([
                'first_in' => $this->first_in,
                'first_out' => $this->first_out,
                'second_in' => $this->second_in,
                'second_out' => $this->second_out,
                'overtime_in' => $this->overtime_in,
                'overtime_out' => $this->overtime_out,
            ]);

            // 🔥 Tutup modal
            $this->dispatch('hide-edit-modal');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Presensi Sudah di Update',
            );
        }
    }
    public function closeModal()
    {
        $this->dispatch('hide-edit-modal');
    }


    public function update()
    {
        $data = Yfrekappresensi::find($this->editId);

        $is_hari_libur_nasional = is_libur_nasional($data->date);
        $is_sunday = is_sunday($data->date);
        $is_puasa = is_puasa($data->date);



        $data->first_in = $this->first_in;
        $data->first_out = $this->first_out;
        $data->second_in = $this->second_in;
        $data->second_out = $this->second_out;
        $data->overtime_in = $this->overtime_in;
        $data->overtime_out = $this->overtime_out;


        $data->no_scan = noScan($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->overtime_out);
        $data->late = late_check_detail($this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->shift, $this->date, $this->late_user_id);

        if ($is_puasa) {
            $dataKaryawan = Karyawan::where('id_karyawan', $data->user_id)->first();
            if ($dataKaryawan->jabatan_id == 17 || $dataKaryawan->jabatan_id == 18 || $dataKaryawan->jabatan_id == 19 || $dataKaryawan->jabatan_id == 20) $data->late = 0;
            // if ($data->user_id == 10196) dd($data->user_id, $dataKaryawan->jabatan_id, $late);
        }

        $data->late_history = $data->late;
        // dd($data->no_scan, $this->first_in, $this->first_out, $this->second_in, $this->second_out, $this->overtime_in, $this->overtime_out);

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
        $data->total_hari_kerja_libur = 0;
        $data->total_jam_kerja_libur = 0;
        $data->total_jam_lembur_libur = 0;

        if (isset($hasil['jam_kerja']) && $hasil['jam_kerja'] >= 1) {
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
            } else if (is_sunday($data->date) || is_libur_nasional($data->date)) {
                if ($data->total_jam_kerja >= 8) {
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

        $this->checkData();
        $berhasil = 1;
        if ($this->is_delete) {
            $berhasil = 3;
            if ($data->no_scan == "") {
                $data->no_scan_history = null;
                $berhasil = 2;
            }
        }

        // if ($dataKaryawan->metode_penggajian == "Perbulan") {
        //     if ($data->total_jam_kerja > 0) {
        //         $data->total_hari_kerja = 1;
        //     }
        // }

        if ($is_hari_libur_nasional || $is_sunday) {
            $data->total_hari_kerja_libur = 0;
            $data->total_hari_kerja = 0;
            $data->total_jam_kerja_libur = $data->total_jam_kerja * 2;
            $data->total_jam_lembur_libur = $data->total_jam_lembur * 2;
            $data->total_jam_kerja = 0;
            $data->total_jam_lembur = 0;
        }



        $data->save();
        $this->delete_no_scan_history = false;
        $this->is_delete = false;
        $this->dispatch('hide-edit-modal');
        if ($berhasil == 1) {
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Presensi Sudah di Update',
            );
        } elseif ($berhasil == 2) {
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Presensi & No Scan History Sudah di Update',
            );
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'No Scan History GAGAL di delete',
            );
        }

        // Tutup modal
        // $this->dispatch(
        //     'message',
        //     type: 'success',
        //     title: 'Data Presensi Sudah di Update',
        // );;
    }


    public function mount()
    {
        $lastdate = Yfrekappresensi::orderBy('date', 'desc')->first();
        $this->tanggal = $lastdate->date;
        $tgl = Carbon::parse($this->tanggal);
        $this->bulan = $tgl->month;
        $this->tahun = $tgl->year;
        $this->month = $tgl->month;
        $this->year = $tgl->year;

        $this->rowsPerPage = 10;
        // $this->is_sunday = Carbon::parse($date)->isSunday()
        $this->is_sunday = $tgl->isSunday();
        $this->is_hari_libur_nasional = is_libur_nasional($tgl);
        $this->is_friday = is_friday($tgl);
        $this->bulan_terakhir = $this->month;
        $this->reload_placement_jabatan();
    }

    public function delete($id)
    {
        Yfrekappresensi::find($id)->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Presensi Sudah di Delete',
        );
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function prevDate()
    {
        // $this->month = $this->tanggal->month;
        // $this->year = $this->tanggal->year;
        $this->tanggal = \Carbon\Carbon::parse($this->tanggal)
            ->subDay()
            ->toDateString();

        $this->bulan = Carbon::parse($this->tanggal)->month;
        $this->tahun = Carbon::parse($this->tanggal)->year;
        $this->month = Carbon::parse($this->tanggal)->month;
        $this->year = Carbon::parse($this->tanggal)->year;

        $this->is_sunday = Carbon::parse($this->tanggal)->isSunday();
        $this->is_hari_libur_nasional = is_libur_nasional($this->tanggal);
        $this->is_friday = is_friday($this->tanggal);

        if ($this->bulan_terakhir != $this->month) {
            $this->bulan_terakhir = $this->month;
            $this->reload_placement_jabatan();
        }

        $this->resetPage();
    }

    public function nextDate()
    {
        $this->tanggal = \Carbon\Carbon::parse($this->tanggal)
            ->addDay()
            ->toDateString();
        // $this->month = $this->tanggal->month;
        // $this->year = $this->tanggal->year;

        $this->bulan = Carbon::parse($this->tanggal)->month;
        $this->tahun = Carbon::parse($this->tanggal)->year;
        $this->month = Carbon::parse($this->tanggal)->month;
        $this->year = Carbon::parse($this->tanggal)->year;

        $this->is_sunday = Carbon::parse($this->tanggal)->isSunday();
        $this->is_hari_libur_nasional = is_libur_nasional($this->tanggal);
        $this->is_friday = is_friday($this->tanggal);
        if ($this->bulan_terakhir != $this->month) {
            $this->bulan_terakhir = $this->month;
            $this->reload_placement_jabatan();
        }
        $this->resetPage();
    }


    public function updatedTanggal()
    {
        $tanggal = Carbon::parse($this->tanggal);
        $this->bulan = $tanggal->month;
        $this->tahun = $tanggal->year;
        $this->month = $tanggal->month;
        $this->year = $tanggal->year;

        $this->is_sunday = Carbon::parse($this->tanggal)->isSunday();
        $this->is_hari_libur_nasional = is_libur_nasional($this->tanggal);
        $this->is_friday = is_friday($this->tanggal);
        if ($this->bulan_terakhir != $this->month) {
            $this->bulan_terakhir = $this->month;
            $this->reload_placement_jabatan();
        }
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPlacementFilter()
    {
        $this->jabatanFilter = ''; // reset jabatan jika placement berubah
        $this->resetPage();
    }

    public function updatingJabatanFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $bulan = date('m', strtotime($this->tanggal));
        $tahun = date('Y', strtotime($this->tanggal));
        if (! $this->is_kosong) {
            $query = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
                ->select(
                    'yfrekappresensis.*',
                    'karyawans.nama',
                    'karyawans.metode_penggajian',
                    'karyawans.placement_id',
                    'karyawans.jabatan_id'
                );

            if (empty($this->search)) {
                $query->where('yfrekappresensis.date', $this->tanggal);
            } else {
                $query->whereMonth('yfrekappresensis.date', date('m', strtotime($this->tanggal)))
                    ->whereYear('yfrekappresensis.date', date('Y', strtotime($this->tanggal)));
            }
            // ->whereMonth('yfrekappresensis.date', $this->tanggal)
            // ->whereYear('yfrekappresensis.date', $this->tanggal);



            // ->where('yfrekappresensis.date', $this->tanggal);

            // 🔍 Filter search
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('karyawans.nama', 'like', '%' . $this->search . '%')
                        ->orWhere('yfrekappresensis.user_id', 'like', '%' . $this->search . '%');
                    // ->whereMonth(
                    //     'yfrekappresensis.date',
                    //     date('m', strtotime($this->tanggal))
                    // )
                    // ->whereYear('yfrekappresensis.date', date('Y', strtotime($this->tanggal)));
                });
            }

            // 🏢 Filter placement
            if (!empty($this->placementFilter)) {
                $query->where('karyawans.placement_id', $this->placementFilter);
            }

            // 👔 Filter jabatan
            if (!empty($this->jabatanFilter)) {
                $query->where('karyawans.jabatan_id', $this->jabatanFilter);
            }

            $query->orderBy($this->sortField, $this->sortDirection);
            $datas = $query->paginate($this->rowsPerPage);
        } else {
            $datas = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
                ->select(
                    'yfrekappresensis.*',
                    'karyawans.nama',
                    'karyawans.metode_penggajian',
                    'karyawans.placement_id',
                    'karyawans.jabatan_id'
                )
                ->whereMonth('yfrekappresensis.date', Carbon::parse($this->tanggal)->month)
                ->whereYear('yfrekappresensis.date', Carbon::parse($this->tanggal)->year)

                ->whereNull('yfrekappresensis.first_in')
                ->whereNull('yfrekappresensis.first_out')
                ->whereNull('yfrekappresensis.second_in')
                ->whereNull('yfrekappresensis.second_out')
                ->whereNull('yfrekappresensis.overtime_in')
                ->whereNull('yfrekappresensis.overtime_out')
                ->paginate($this->rowsPerPage);
            // $this->is_kosong = false;
        }
        if ($this->is_no_scan) {
            $datas = Yfrekappresensi::join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
                ->select(
                    'yfrekappresensis.*',
                    'karyawans.nama',
                    'karyawans.metode_penggajian',
                    'karyawans.placement_id',
                    'karyawans.jabatan_id'
                )
                ->whereMonth('yfrekappresensis.date', Carbon::parse($this->tanggal)->month)
                ->whereYear('yfrekappresensis.date', Carbon::parse($this->tanggal)->year)
                ->where('no_scan', 'No Scan')
                ->paginate($this->rowsPerPage);
            // $this->is_no_scan = false;
        }
        $this->is_presensi_locked = $this->check_presensi_locked();
        $this->checkData();
        return view('livewire.newpresensi', [
            'datas' => $datas,

        ]);
    }
}
