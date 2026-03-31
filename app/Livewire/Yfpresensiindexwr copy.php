<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use Livewire\Component;
use App\Models\Karyawan;
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


    public function mount()
    {
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
    }

    public function delete_no_scan($id)
    {
        $data = Yfrekappresensi::find($id);
        if ($data->no_scan == "") {
            $data->no_scan_history = null;
            $data->save();
            $this->dispatch('Success', message: 'No Scan History sudah di delete');
        } else {
            $this->dispatch('error', message: 'No Scan harus di bersihkan dulu');
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
        $this->dispatch('success', message: 'Data Presensi Sudah di Delete');
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
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    //evaluasi ini
                    // if ($d->karyawan->jabatan === 'Satpam') {
                    //     $jam_kerja = ($terlambat >= 6) ? 0.5 : $jam_kerja;
                    // }

                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan, $d->karyawan->placement);
                    if (is_sunday($d->date)) {
                        $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 * 2;
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
                                $tambahan_shift_malam = 2;
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
                    }
                    if ($d->karyawan->jabatan == 'Satpam' && is_sunday($d->date)) {
                        $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    }
                    if ($d->karyawan->jabatan == 'Satpam' && is_saturday($d->date)) {
                        $jam_lembur = 0;
                    }

                    if (is_sunday($d->date) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                        $jam_lembur = $jam_kerja;
                        $jam_kerja = 0;
                    }

                    // Jika hari libur nasional
                    if (
                        is_libur_nasional($d->date) && trim($d->karyawan->metode_penggajian) == 'Perjam' && !is_sunday($d->date)
                    ) {
                        $jam_kerja *= 2;
                        $jam_lembur *= 2;
                    }

                    $this->dataArr->push([
                        'tgl' => $tgl,
                        'jam_kerja' => $jam_kerja,
                        'terlambat' => $terlambat,
                        'jam_lembur' => $jam_lembur,
                        'tambahan_shift_malam' => $tambahan_shift_malam,
                        'is_sunday' => is_sunday($d->date)
                    ]);

                    $total_hari_kerja++;
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
                if (Carbon::parse($data->second_in)->betweenIncluded('11:00', '15:00')) {
                    $data->shift = 'Pagi';
                    // dd($data->shift, $is_saturday );
                }
            }
        }

        $data->save();
        $this->btnEdit = true;

        $this->dispatch('success', message: 'Data sudah di update');
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


        // $this->tanggal = date( 'Y-m-d', strtotime( $this->tanggal ) );

        if ($this->tanggal == null) {
            $lastDate = Yfrekappresensi::orderBy('date', 'desc')->first();
            if ($lastDate == null) {
                $this->tanggal = null;
            } else {
                $this->tanggal = Carbon::parse($lastDate->date)->format('Y-m-d');
            }
        }
        $totalHadir = Yfrekappresensi::query()
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalHadirPagi = Yfrekappresensi::where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $overallNoScan = Yfrekappresensi::where('no_scan', 'No Scan')->count();
        $totalNoScan = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalNoScanPagi = Yfrekappresensi::where('no_scan', 'No Scan')
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLate = Yfrekappresensi::where('late', '>', '0')
            ->where('date', '=', $this->tanggal)
            ->count();
        $totalLatePagi = Yfrekappresensi::where('late', '>', '0')
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $overtime = Yfrekappresensi::where('overtime_in', '!=', null)
            ->where('date', '=', $this->tanggal)
            ->count();
        $overtimePagi = Yfrekappresensi::where('overtime_in', '!=', null)
            ->where('shift', 'Pagi')
            ->where('date', '=', $this->tanggal)
            ->count();
        $absensiKosong =
            $data = Yfrekappresensi::where('first_in', null)
            ->where('first_out', null)
            ->where('second_in', null)
            ->where('second_out', null)
            ->where('overtime_in', null)
            ->where('overtime_out', null)
            ->count();
        if ($absensiKosong > 0) {
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




        // fil

        if ($this->is_noscan) {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.departemen'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->where('no_scan', 'No Scan')
                ->paginate($this->perpage);
        } elseif ($this->is_kosong) {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.departemen'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->whereNull('first_in')
                ->whereNull('first_out')
                ->whereNull('second_in')
                ->whereNull('second_out')
                ->whereNull('overtime_in')
                ->whereNull('overtime_out')
                ->paginate($this->perpage);
        } else {
            $datas = Yfrekappresensi::select(['yfrekappresensis.*', 'karyawans.nama', 'karyawans.departemen'])
                ->join('karyawans', 'yfrekappresensis.karyawan_id', '=', 'karyawans.id')
                ->when($this->location == 'Pabrik 1', function ($query) {
                    return $query->where('placement', 'YCME');
                })
                ->when($this->location == 'Pabrik 2', function ($query) {
                    return $query->where('placement', 'YEV');
                })
                ->when($this->location == 'Kantor', function ($query) {
                    return $query->whereIn('placement', ['YIG', 'YSM']);
                })
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
                        ->whereMonth('date', $this->bulan)
                        ->whereYear('date', $this->tahun);
                })
                // ->when($this->is_noscan, function ($query) {
                //     $query
                //         ->where('no_scan', 'No Scan');
                // })



                ->where(function ($query) {
                    $query->when($this->search, function ($subQuery) {
                        $subQuery
                            ->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('user_id', trim($this->search))
                            // ->orWhere('departemen', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('placement', 'LIKE', '%' . trim($this->search) . '%')
                            ->orWhere('shift', 'LIKE', '%' . trim($this->search) . '%');
                        // ->whereMonth('date', $this->month)
                        // ->whereYear('date', $this->year);
                    });
                })
                ->paginate($this->perpage);
        }
        // dd($datas[0]->user_id);
        // $this->is_noscan = false;

        return view('livewire.yfpresensiindexwr', compact([
            'datas', 'totalHadir', 'totalHadirPagi',
            'totalNoScan', 'totalNoScanPagi', 'totalLate', 'totalLatePagi', 'overallNoScan', 'overtime', 'overtimePagi', 'absensiKosong'
        ]));
    }
}
