<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\User;
use App\Models\Employee;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\Department;
use App\Models\Harikhusus;
use App\Models\Jamkerjaid;
use App\Models\Yfpresensi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class YfpresensiController extends Controller
{
    public function compare(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);




        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();
        $tgl = trim(explode('~', $importedData->getCell('A2')->getValue())[1]);
        $tgl1 = trim(explode('~', $importedData->getCell('A2')->getValue())[0]);
        $tgl2 = trim(explode(':', $tgl1)[1]);
        if ($tgl != $tgl2) {
            clear_locks();
            return back()->with('error', 'Gagal Upload Tanggal harus dihari yang sama');
        }
        $user_id = '';
        $name = '';
        $department = '';
        $late = null;
        $no_scan = null;
        $tgl_delete = null;

        // check Tanggal apakah ada yang sama
        $tgl_sama = DB::table('yfrekappresensis')
            ->where('date', $tgl)
            ->get('user_id');

        // check apakah tgl fresh

        $freshDate = DB::table('yfrekappresensis')
            ->where('date', $tgl)->first();
        if ($freshDate != null) {
            $cx = 0;
            $datasama = [];
            for ($i = 5; $i <= $row_limit; $i++) {

                if ($importedData->getCell('A' . $i)->getValue() != '') {

                    $user_id = $importedData->getCell('A' . $i)->getValue();
                    $time = $importedData->getCell('D' . $i)->getValue();

                    if ($tgl_sama->isNotEmpty()) {

                        foreach ($tgl_sama as $data) {
                            $cx++;
                            if ($user_id == $data->user_id && $time != '') {
                                $datasama[] = [
                                    'user_id' => $user_id,
                                ];
                            }
                        }
                    }
                }
            }
            if (count($datasama) > 0) {
                // Generate formatted user IDs
                $formattedIds = [];
                foreach ($datasama as $d) {
                    $formattedIds[] = $d['user_id'];
                }
                $msg = 'Data tidak bisa diupload karena terdapat user id yang sama: ' . implode(', ', $formattedIds);

                return back()->with('error', $msg);
            }
        }

        return back()->with('success', 'Tidak ada data duplikat');
    }

    public function deleteByPabrik(Request $request)
    {
        dd('masih ada bugs karena jika hapus maka akan kehapus semua');
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();

        $tgl = trim(explode('~', $importedData->getCell('A2')->getValue())[1]);
        $tgl1 = trim(explode('~', $importedData->getCell('A2')->getValue())[0]);
        $tgl2 = trim(explode(':', $tgl1)[1]);
        if ($tgl != $tgl2) {
            clear_locks();
            return back()->with('error', 'Gagal Upload Tanggal harus dihari yang sama');
        }
        $user_id = '';
        $name = '';
        $department = '';
        $late = null;
        $no_scan = null;
        $tgl_delete = null;

        // check Tanggal apakah ada yang sama
        $tgl_sama = DB::table('yfrekappresensis')
            ->where('date', $tgl)
            ->get('user_id');



        for ($i = 5; $i <= $row_limit; $i++) {

            if ($importedData->getCell('A' . $i)->getValue() != '') {
                $user_id = $importedData->getCell('A' . $i)->getValue();
                $name = $importedData->getCell('B' . $i)->getValue();
                if ($tgl_sama->isNotEmpty()) {
                    foreach ($tgl_sama as $data) {
                        if ($user_id == $data->user_id) {
                            clear_locks();
                            return back()->with('error', 'Data presensi in belum pernah di upload');
                        }
                    }
                }
            }

            if ($importedData->getCell('D' . $i)->getValue() != '') {
                $time = date('H:i', strtotime($importedData->getCell('D' . $i)->getValue()));
                if (strpos($importedData->getCell('D' . $i)->getValue(), '+') !== false) {
                    $str = str_replace('+', '', $importedData->getCell('D' . $i)->getValue());
                    $time = date('H:i', strtotime($str));
                }



                //  pakai Chunk
                $Yfpresensidata[] = [
                    'user_id' => $user_id,
                    // 'name' => $name,
                    'date' => $tgl,
                    'time' => $time,
                    'day_number' => date('w', strtotime($tgl)),
                ];
            }
        }

        $unique = collect($Yfpresensidata)->unique('user_id');
        $cx = 0;
        foreach ($unique as $d) {
            try {
                $data = Yfrekappresensi::where('user_id', $d['user_id'])->where('date', $d['date'])->delete();
                $cx++;
            } catch (\Exception $e) {
                // Handle the exception, e.g., log it or notify someone
                // For example:
                dd($e->getMessage());
            }
        }
        // dd('Data Deleted: ', $cx);
        return back()->with('error', 'Data Deleted: ' . $cx);
    }

    public function generateUsers()
    {

        $karyawan = Karyawan::get(['nama', 'email', 'id_karyawan', 'tanggal_lahir']);
        // $userArray = [];
        foreach ($karyawan as $item) {

            User::create([
                'name' => titleCase($item->nama),
                'email' =>  trim($item->email),
                'username' => trim($item->id_karyawan),
                'role' => 1,
                'remember_token' => Str::random(10),
                'password' => Hash::make(generatePassword($item->tanggal_lahir)),
            ]);
        }

        dd('Done');
    }

    public function deleteJamKerja()
    {
        Jamkerjaid::query()->truncate();
        return back()->with('success', 'Data Jam Kerja telah berhasil di delete');
    }

    public function deleteNoScan()
    {
        Yfrekappresensi::where('no_scan', 'No Scan')->delete();
        return back()->with('success', 'Data No scan telah berhasil di delete');
    }

    public function deletepresensi_no_use()
    {
        Yfpresensi::query()->truncate();
        Yfrekappresensi::query()->truncate();
        // Presensi::query()->truncate();
        return back()->with('success', 'Data Presensi telah berhasil di delete');
    }

    public function index()
    {
        return view('yfpresensi.index');
    }

    public function clear_upload_lock()
    {
        $lock = Lock::find(1);
        $lock->upload = false;
        $lock->save();
    }

    public function check_store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();

        $tgl = trim(explode('~', $importedData->getCell('A2')->getValue())[1]);
        $tgl1 = trim(explode('~', $importedData->getCell('A2')->getValue())[0]);
        $tgl2 = trim(explode(':', $tgl1)[1]);
        if ($tgl != $tgl2) {
            $this->clear_upload_lock();
            return back()->with('error', 'Gagal Upload Tanggal harus dihari yang sama');
        }
        $hari_khusus = Harikhusus::where('date', $tgl)->first();
        if ($hari_khusus) {
            $this->khusus_store($request);
        } else {
            // dd('bukan hari khusus');
            $this->store($request);
        }
    }

    public function store(Request $request)
    {
        $lock = Lock::find(1);
        if ($lock->upload) {
            // $this->clear_upload_lock();
            return back()->with('error', 'Mohon dicoba sebentar lagi ya');
        } else {
            $lock->upload = true;
            $lock->save();
        }
        Yfpresensi::query()->truncate();

        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();

        $tgl = trim(explode('~', $importedData->getCell('A2')->getValue())[1]);
        $tgl1 = trim(explode('~', $importedData->getCell('A2')->getValue())[0]);
        $tgl2 = trim(explode(':', $tgl1)[1]);
        // dd($tgl, $tgl1, $tgl2);
        if ($tgl != $tgl2) {
            $this->clear_upload_lock();
            return back()->with('error', 'Gagal Upload Tanggal harus dihari yang sama');
        }
        $user_id = '';
        $name = '';
        $department = '';
        $late = null;
        $no_scan = null;
        $tgl_delete = null;

        // check Tanggal apakah ada yang sama
        $tgl_sama = DB::table('yfrekappresensis')
            ->where('date', $tgl)
            ->get('user_id');

        // Apakah ada ID Duplikat
        $freshDate = DB::table('yfrekappresensis')
            ->where('date', $tgl)->first();
        if ($freshDate != null) {
            $cx = 0;
            $datasama = [];
            for ($i = 5; $i <= $row_limit; $i++) {

                if ($importedData->getCell('A' . $i)->getValue() != '') {

                    $user_id = $importedData->getCell('A' . $i)->getValue();
                    $time = $importedData->getCell('D' . $i)->getValue();


                    if ($tgl_sama->isNotEmpty()) {

                        foreach ($tgl_sama as $data) {
                            $cx++;
                            if ($user_id == $data->user_id && $time != '') {
                                // if ($user_id == $data->user_id) {
                                $datasama[] = [
                                    'user_id' => $user_id,
                                ];
                            }
                        }
                    }
                }
            }
            if (count($datasama) > 0) {
                // Generate formatted user IDs
                $formattedIds = [];
                foreach ($datasama as $d) {
                    $formattedIds[] = $d['user_id'];
                }
                if (count($formattedIds) > 50) {

                    $msg = 'Data Presensi ini sudah di pernah di upload';
                } else {
                    $msg = 'Data tidak bisa diupload karena terdapat user id yang sama: ' . implode(', ', $formattedIds);
                }
                $this->clear_upload_lock();
                return back()->with('error', $msg);
            }
        }

        for ($i = 5; $i <= $row_limit; $i++) {

            if ($importedData->getCell('A' . $i)->getValue() != '') {
                $user_id = $importedData->getCell('A' . $i)->getValue();
                $name = $importedData->getCell('B' . $i)->getValue();
                $time = $importedData->getCell('D' . $i)->getValue();


                if ($tgl_sama->isNotEmpty()) {
                    foreach ($tgl_sama as $data) {
                        if ($user_id == $data->user_id && $time != '') {
                            $this->clear_upload_lock();

                            return back()->with('error', 'Gagal Upload, User ID kembar : ' . $data->user_id);
                        }
                    }
                }
            }

            if ($importedData->getCell('D' . $i)->getValue() != '') {
                $time = date('H:i', strtotime($importedData->getCell('D' . $i)->getValue()));
                if (strpos($importedData->getCell('D' . $i)->getValue(), '+') !== false) {
                    $str = str_replace('+', '', $importedData->getCell('D' . $i)->getValue());
                    $time = date('H:i', strtotime($str));
                }

                // pakai Chunk
                $Yfpresensidata[] = [
                    'user_id' => $user_id,

                    'date' => $tgl,
                    'time' => $time,
                    'day_number' => date('w', strtotime($tgl)),
                ];
            }
        }
        // dd($Yfpresensidata[0]);
        try {
            foreach (array_chunk($Yfpresensidata, 200) as $item) {
                Yfpresensi::insert($item);
            }
        } catch (\Exception $e) {
            $this->clear_upload_lock();

            return back()->with('error', 'Gagal Upload Format tanggal tidak sesuai');
        }


        $data_yfpresensi = Yfpresensi::select('user_id')->get();

        foreach ($data_yfpresensi as $dyf) {
            $finddata = Karyawan::where('id_karyawan', $dyf->user_id)->exists();
            if (!$finddata) {
                Yfpresensi::where('user_id', $dyf->user_id)->delete();
            }
        }


        $jumlahKaryawanHadir = DB::table('yfpresensis')
            ->distinct('user_id')
            ->count('user_id');

        $karyawanHadir = DB::table('yfpresensis')
            ->select('user_id', 'date')
            ->distinct()
            ->get();

        $is_sunday = is_sunday($tgl);
        $is_saturday = is_saturday($tgl);
        $is_friday = is_friday($tgl);
        $is_hari_libur_nasional = is_libur_nasional($tgl);
        foreach ($karyawanHadir as $kh) {
            $tgl_delete = $kh->date;
            $user_id = $kh->user_id;
            // $name = $kh->name;
            $tgl = $kh->date;
            // dd('tgl', $tgl);

            $first_in = null;
            $first_out = null;
            $second_in = null;
            $second_out = null;
            $overtime_in = null;
            $overtime_out = null;
            $late = null;
            $no_scan = null;
            $shift = '';
            $hari_khusus = cek_hari_khusus($tgl);


            // ini mulai masukin data perID
            $tablePresensi = DB::table('yfpresensis')
                ->where('user_id', $kh->user_id)
                ->get();
            $is_saturday = is_saturday($kh->date);
            // Batasanm Puasa

            // ok2 selama puasa jam kerja sabtu disamakan dengan hari biasa khusus utk YCME
            //plk
            // if (is_puasa($kh->date) && get_placement($kh->user_id) == 'YCME') {
            if (is_puasa($kh->date)) {
                if ($is_saturday) {
                    // JIKA HARI SABTU kkk
                    if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '13:00')) {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Malam';
                    }
                    if ($shift == 'Pagi') {
                        // SHIFT PAGI
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:30')) {
                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('12:31', '14:00')) {
                                if ($second_in == '') $second_in = $tp->time;
                                // perubahan second_out dan overtime_in yg tidak terdeteksi, untuk jam kerja sabtu
                                // } elseif (Carbon::parse($tp->time)->betweenIncluded('14:01', '17:30')) {
                                // $second_out = $tp->time;

                                // } elseif (Carbon::parse($tp->time)->betweenIncluded('14:01', '16:59') && $second_out == null) {
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('14:01', '17:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '18:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }
                    if ($shift == 'Malam') {
                        foreach ($tablePresensi as $tp) {
                            switch ($tp->time) {
                                case Carbon::parse($tp->time)->betweenIncluded('15:00', '20:00'):
                                    if ($first_in == '') $first_in = $tp->time;
                                    break;
                                case Carbon::parse($tp->time)->betweenIncluded('17:01', '19:30'):
                                    if ($first_out == null) {
                                        $first_out = $tp->time;
                                    } else {
                                        $second_in = $tp->time;
                                    }
                                    break;
                                case Carbon::parse($tp->time)->betweenIncluded('17:30', '19:30'):
                                    if ($second_in == '') $second_in = $tp->time;
                                    break;

                                default:
                                    $second_out = $tp->time;
                                    break;
                            }
                        }
                    }
                    if ($shift == 'Pagi') {
                        if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                            $second_out = $overtime_in;
                            $overtime_in = null;
                        }
                        if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                            $second_out = $overtime_out;
                            $overtime_out = null;
                        }
                    }
                } else {
                    // JIKA BUKAN HARI SABTU
                    if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '15:00')) {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Malam';
                    }

                    if ($shift == 'Pagi') {
                        // SHIFT PAGI
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:30')) {
                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('12:31', '15:00')) {
                                $second_in = $tp->time;
                                // } elseif (Carbon::parse($tp->time)->betweenIncluded('15:01', '17:59') && $second_out == null) {
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('15:01', '18:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '18:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }
                    // shift malam
                    if ($shift == 'Malam') {
                        // SHIFT Malam
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('17:30', '22:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('01:30', '04:30')) {

                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('03:31', '04:30')) {
                                if ($second_in == '') $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('04:01', '05:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '04:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }

                    // if ($shift == 'Pagi') {
                    if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                        $second_out = $overtime_in;
                        $overtime_in = null;
                    }
                    if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                        $second_out = $overtime_out;
                        $overtime_out = null;
                    }




                    // }
                }
            } else {
                if ($is_saturday) {
                    // JIKA HARI SABTU kkk
                    if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '13:00')) {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Malam';
                    }

                    if ($shift == 'Pagi') {
                        // SHIFT PAGI
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:30')) {
                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                                // if ($flag == 0) {
                                // $first_out = $tp->time;
                                // if (Carbon::parse($tp->time)->betweenIncluded('10:01', '11:59')) {
                                // $flag = 1;
                                // } else {
                                // $flag = 2;
                                // }
                                // }
                                // // ook
                                // if ($flag == 1) {
                                // $second_in = $tp->time;
                                // }
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('12:31', '14:00')) {
                                if ($second_in == '') $second_in = $tp->time;
                                // perubahan second_out dan overtime_in yg tidak terdeteksi, untuk jam kerja sabtu
                                // } elseif (Carbon::parse($tp->time)->betweenIncluded('14:01', '17:30')) {
                                // $second_out = $tp->time;

                            } elseif (Carbon::parse($tp->time)->betweenIncluded('15:01', '17:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '18:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }
                    if ($shift == 'Malam') {
                        foreach ($tablePresensi as $tp) {
                            switch ($tp->time) {
                                case Carbon::parse($tp->time)->betweenIncluded('15:00', '20:00'):
                                    if ($first_in == '') $first_in = $tp->time;
                                    break;
                                case Carbon::parse($tp->time)->betweenIncluded('20:01', '21:30'):
                                    if ($first_out == null) {
                                        $first_out = $tp->time;
                                    } else {
                                        $second_in = $tp->time;
                                    }
                                    break;
                                case Carbon::parse($tp->time)->betweenIncluded('21:31', '23:59'):
                                    if ($second_in == '') $second_in = $tp->time;
                                    break;

                                default:
                                    $second_out = $tp->time;
                                    break;
                            }
                        }
                    }
                    if ($shift == 'Pagi') {
                        if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                            $second_out = $overtime_in;
                            $overtime_in = null;
                        }
                        if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                            $second_out = $overtime_out;
                            $overtime_out = null;
                        }
                    }
                } else {
                    // JIKA BUKAN HARI SABTU
                    if (Carbon::parse($tablePresensi[0]->time)->betweenIncluded('05:30', '15:00')) {
                        $shift = 'Pagi';
                    } else {
                        $shift = 'Malam';
                    }

                    if ($shift == 'Pagi') {
                        // SHIFT PAGI
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('05:30', '10:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('10:01', '12:30')) {
                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('12:31', '15:00')) {
                                $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('15:01', '17:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '18:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }
                    // shift malam
                    if ($shift == 'Malam') {
                        // SHIFT Malam
                        $flag = 0;
                        foreach ($tablePresensi as $tp) {
                            if (Carbon::parse($tp->time)->betweenIncluded('17:30', '22:00')) {
                                if ($first_in == '') $first_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('22:01', '23:59') || Carbon::parse($tp->time)->betweenIncluded('00:00', '00:30')) {

                                if ($first_out == '') $first_out = $tp->time;
                                else $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('00:31', '03:00')) {
                                if ($second_in == '') $second_in = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded('03:01', '05:59') && $second_out == null) {
                                $second_out = $tp->time;
                            } elseif (Carbon::parse($tp->time)->betweenIncluded(shortJam($second_out), '06:59') && $second_out != null) {
                                $overtime_in = $tp->time;
                            } else {
                                $overtime_out = $tp->time;
                            }
                        }
                    }

                    // if ($shift == 'Pagi') {
                    if ($second_out == null && $overtime_out == null && $overtime_in != null) {
                        $second_out = $overtime_in;
                        $overtime_in = null;
                    }
                    if ($second_out == null && $overtime_in == null && $overtime_out != null) {
                        $second_out = $overtime_out;
                        $overtime_out = null;
                    }




                    // }
                }
            }
            // Batasana Akhir Puasa

            $no_scan = noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out);
            $late = late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl, $kh->user_id);
            $dataKaryawan = Karyawan::where('id_karyawan', $user_id)->first();
            if ($dataKaryawan == null) {
                $id_karyawan = 'kosong';
            } else {
                $id_karyawan = $dataKaryawan->id;
            }
            // pakai code dibawah ini, jika masih banyak second yang masuk ke malam hari second_in code
            // if(Carbon::parse( $second_in )->betweenIncluded( '11:01', '14:00' )) $shift = 'Pagi';
            // ook
            if ($no_scan != null) $late = null;
            // lanjutkan
            $hasil = saveDetail($user_id, $first_in, $first_out, $second_in, $second_out, $late, $shift, $tgl, $dataKaryawan->jabatan_id, $no_scan, $dataKaryawan->placement_id, $overtime_in, $overtime_out);
            // dd($hasil['jam_kerja']);

            $total_hari_kerja = 0;
            $total_jam_kerja = 0;
            $total_jam_lembur = 0;
            $jam_kerja_libur = 0;
            $total_hari_kerja_libur = 0;
            $total_jam_lembur_libur = 0;

            if (isset($hasil['jam_kerja']) && $hasil['jam_kerja'] >= 1) {
                $total_hari_kerja = 1;
            }

            if (isset($hasil['jam_kerja'])) {
                $total_jam_kerja = $hasil['jam_kerja'];
            }
            if (isset($hasil['jam_lembur'])) {
                $total_jam_lembur = $hasil['jam_lembur'];
            }

            // if ($kh->date == '2025-05-30') $late = null;

            $setengah_hari = (
                ($first_in === null && $first_out !== null) ||
                ($second_in === null && $second_out === null)
            );

            if ($hari_khusus) {
                if ($kh->date === $hari_khusus->date && !$setengah_hari) {
                    $late = 0;
                }
            }


            if ($shift == 'Malam') {

                if ($is_saturday) {
                    if ($total_jam_kerja >= 6) {
                        // $jam_lembur = $jam_lembur + 1;
                        $tambahan_shift_malam = 1;
                    }
                } else if ($is_sunday || $is_hari_libur_nasional) {
                    if ($total_jam_kerja >= 8) {
                        // $jam_lembur = $jam_lembur + 2;
                        $tambahan_shift_malam = 1;
                    }
                } else {
                    if ($total_jam_kerja >= 8) {
                        // $jam_lembur = $jam_lembur + 1;
                        $tambahan_shift_malam = 1;
                    }
                }
            } else {
                $tambahan_shift_malam = 0;
            }

            // khusus untuk security kode jabatan 17
            if ($dataKaryawan->jabatan_id == 17 && $shift == 'Malam') {
                if ($is_sunday || $is_hari_libur_nasional) {
                    $total_jam_kerja = min($total_jam_kerja, 16);
                } elseif ($is_saturday) {
                    $total_jam_kerja = min($total_jam_kerja, 6);
                } else {
                    $total_jam_kerja = min($total_jam_kerja, 8);
                }
            }


            if ($is_hari_libur_nasional || $is_sunday) {
                $total_hari_kerja_libur = 0;
                $total_hari_kerja = 0;
                $jam_kerja_libur = $total_jam_kerja * 2;
                $total_jam_lembur_libur = $total_jam_lembur * 2;
                $total_jam_kerja = 0;
                $total_jam_lembur = 0;
            }
            // dd($tgl);
            Yfrekappresensi::create([
                'shift_malam' => $tambahan_shift_malam ?? 0,
                'user_id' => $user_id,
                'karyawan_id' => $id_karyawan,
                // 'name' => $name,
                'date' => $tgl,
                'first_in' => $first_in,
                'first_out' => $first_out,
                'second_in' => $second_in,
                'second_out' => $second_out,
                'overtime_in' => $overtime_in,
                'overtime_out' => $overtime_out,
                'total_jam_kerja' => $total_jam_kerja,
                'total_hari_kerja' => $total_hari_kerja,
                'total_jam_lembur' => $total_jam_lembur,
                'total_jam_kerja_libur' => $jam_kerja_libur,

                'total_hari_kerja_libur' => $total_hari_kerja_libur,
                'total_jam_lembur_libur' => $total_jam_lembur_libur,

                'shift' => $shift,
                'late' => $late,
                'no_scan' => $no_scan,
                'no_scan_history' => $no_scan,
                'late_history' => $late,
            ]);
        }


        Yfpresensi::query()->truncate();
        $this->clear_upload_lock();

        return back()->with('info', 'Berhasil Import : ' . $jumlahKaryawanHadir . ' data');
    }
}
