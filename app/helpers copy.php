<?php

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\Lock;
use App\Models\User;
use App\Models\Company;
use App\Models\Jabatan;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Placement;
use App\Models\Requester;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Applicantfile;
use App\Models\Dashboarddata;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use App\Models\Personnelrequestform;
use App\Models\Timeoff;
use App\Models\Timeoffrequester;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

function isTimeoff($id_karyawan)
{
    if ($id_karyawan != null) {
        $data = Timeoffrequester::where('approve_by_1', $id_karyawan)->orWhere('approve_by_2', $id_karyawan)->first();
        if ($data) return true;
        else return false;
    }
}

function isRequester($id_karyawan)
{
    if ($id_karyawan != null) {
        $data = Requester::where('approve_by_1', $id_karyawan)->orWhere('approve_by_2', $id_karyawan)->orWhere('request_id', $id_karyawan)->first();

        if ($data) return true;
        else return false;
    }
}

function getPLacement($id_karyawan)
{
    if (
        $id_karyawan != null
    ) {
        $data = Karyawan::where('id_karyawan', $id_karyawan)->first();
        // dikasih if supaya 8000 yang gak terdaftar di karyawan bisa akses dan gak error
        if ($data) return $data->placement_id;
        else return $id_karyawan;
    }
}

function isResigned($id)
{
    $data = Karyawan::where('id_karyawan', $id)->whereIn('status_karyawan', ['Resigned', 'Blacklist'])->first();
    if ($data == null) return false;
    else return true;
}

function get_first_name($name)
{
    if ($name != '') {
        $arrName = explode(' ', $name);
        return $arrName[0];
    }
}

function hitung_pph21($gaji_bpjs, $ptkp, $jht, $jp, $jkk, $jkm, $kesehatan)
{
    if ($gaji_bpjs != '' &&  $ptkp != '') {
        $gaji_bpjs_max = 0;
        if ($gaji_bpjs >= 12000000) $gaji_bpjs_max = 12000000;
        else $gaji_bpjs_max = $gaji_bpjs;
        if ($jht) $jht_company = ($gaji_bpjs * 3.7) / 100;
        else $jht_company = 0;

        if ($jp) $jp_company = ($gaji_bpjs_max * 2) / 100;
        else $jp_company = 0;

        if ($jkk) $jkk_company = ($gaji_bpjs * 0.24) / 100;
        else $jkk_company = 0;

        if ($jkm) $jkm_company = ($gaji_bpjs * 0.3) / 100;
        else $jkm_company = 0;

        if ($kesehatan) $kesehatan_company = ($gaji_bpjs_max * 4) / 100;
        else $kesehatan_company = 0;


        $total_bpjs_company =
            // $gaji_bpjs + $jkk_company + $jkm_company + $kesehatan_company + $jp_company + $jht_company;
            $gaji_bpjs + $jkk_company + $jkm_company + $kesehatan_company;
        $ter = '';
        switch ($ptkp) {
            case 'TK0':
                $ter = 'A';
                break;
            case 'TK1':
                $ter = 'A';
                break;
            case 'TK2':
                $ter = 'B';
                break;
            case 'TK3':
                $ter = 'B';
                break;
            case 'K0':
                $ter = 'A';
                break;
            case 'K1':
                $ter = 'B';
                break;
            case 'K2':
                $ter = 'B';
                break;
            case 'K3':
                $ter = 'C';
                break;
        }

        $rate_pph21 = get_rate_ter_pph21($ptkp, $total_bpjs_company);
        $pph21 = ($total_bpjs_company * $rate_pph21) / 100;
    } else {
        $pph21 = 0;
    }
    return $pph21;
}

function is_perbulan()
{
    $is_perbulan = false;
    $data_karyawan = Karyawan::where('id_karyawan', auth()->user()->username)->where('metode_penggajian', 'Perbulan')->first();
    if ($data_karyawan != null) $is_perbulan = true;
    else $is_perbulan = false;
    return $is_perbulan;
}

function check_fail_job()
{
    $fail = DB::table('failed_jobs')->count();
    if ($fail > 0) return true;
    else return false;
}

function check_rebuild_done()
{
    $lock = Lock::find(1);
    if ($lock->rebuild_done == 1) return true;
    else return false;
}
function check_rebuilding()
{
    $lock = Lock::find(1);
    if ($lock->rebuild_done == 2) return true;
    else return false;
}

function check_for_new_approved_request()
{
    return Personnelrequestform::where('status', 'Approved')->count();
}
function check_for_new_applyingrequest()
{
    return Personnelrequestform::where('status', 'Applying')->count();
}
function check_for_new_Timeoff_request()
{
    return Timeoff::where('status', 'Confirmed')->count();
}
function check_for_menunggu_approval_Timeoff_request()
{
    return Timeoff::where('status', 'Menunggu Approval')->count();
}


function changeToAdmin($id)
{
    $check_user = Requester::where('request_id', $id)
        ->where('request_id', $id)
        ->orWhere('approve_by_1', $id)
        ->orWhere('approve_by_2', $id)->first();

    $check_user_time_off = Timeoffrequester::where('approve_by_1', $id)
        ->orWhere('approve_by_2', $id)->first();

    // dd($check_user == null);
    if ($check_user == null && $check_user_time_off == null) {
        $data = User::where('username', $id)->first();
        $data->role = 1;
        $data->save();
    }
}

function changeToRequest($id)
{

    if ($id != '') {
        $data = User::where('username', $id)->first();
        $data->role = 2;
        $data->save();
    }
}

function clear_dot($filename, $fileExtension)
{
    $lastDotPosition = strrpos($filename, '.');

    // Split the filename into two parts: before the last dot and after
    $beforeLastDot = substr($filename, 0, $lastDotPosition);
    $afterLastDot = substr($filename, $lastDotPosition);

    $data_arr = explode('.', $afterLastDot);
    $length = count($data_arr); // Count the elements in the array
    if ($length == 2)
        switch ($data_arr[1]) {
            case 'pdf':
                break;
            case 'jpg':
                break;
            case 'jpeg':
                break;
            case 'png':
                break;
            default:
                $afterLastDot = $data_arr[1] . '.' . $fileExtension;
                break;
        }

    // Replace all dots with underscores in the part before the last dot
    $beforeLastDot = str_replace('.', ' ', $beforeLastDot);

    // Concatenate the modified part with the unmodified last dot part
    if ($lastDotPosition) {
        $newFilename = $beforeLastDot . $afterLastDot;
    } else {

        $data_arr = explode('.', $filename);
        $length = count($data_arr); // Count the elements in the array
        if ($length == 1)
            $newFilename = $filename . '.' . $fileExtension;
    }



    return $newFilename;
}

function getName($id)
{
    $data = Karyawan::where('id_karyawan', $id)->first();
    if ($data != null)
        return  $data->nama;
    else return '';
}

function check_id_file_karyawan($id_file_karyawan)
{
    $data = Karyawan::where('id_file_karyawan', $id_file_karyawan)->first();
    if ($data != null) return $id_file_karyawan;
    else return 'false';
}

function check_di_user($email)
{
    $data = User::where('email', trim($email, ' '))->first();
    if ($data != null) return $data->username;
    else return '';
}
function check_di_karyawan($email)
{
    $data = Karyawan::where('email', trim($email, ' '))->first();
    if ($data != null) return $data->id_karyawan;
    else return false;
}

function nama_department($id)
{
    if ($id != null) {
        $data = Department::find($id);
        return $data->nama_department;
    }
}
function nama_company($id)
{
    if ($id != null) {
        $data = Company::find($id);
        return $data->company_name;
    }
}
function nama_placement($id)
{
    if ($id != null) {
        $data = Placement::find($id);
        return $data->placement_name;
    }
}
function nama_jabatan($id)
{
    if ($id != null) {
        $data = Jabatan::find($id);
        return $data->nama_jabatan;
    }
}

function get_rate_ter_pph21($ptkp, $total_bpjs_company)
{

    if ($ptkp != '' && $total_bpjs_company != 0) {
        switch ($ptkp) {
            case 'TK0':
                $ter = 'A';
                break;
            case 'TK1':
                $ter = 'A';
                break;
            case 'TK2':
                $ter = 'B';
                break;
            case 'TK3':
                $ter = 'B';
                break;
            case 'K0':
                $ter = 'A';
                break;
            case 'K1':
                $ter = 'B';
                break;
            case 'K2':
                $ter = 'B';
                break;
            case 'K3':
                $ter = 'C';
                break;
        }
        $data_ter = Ter::where('ter', $ter)->where('to', '>=', $total_bpjs_company)->orderBy('to', 'asc')->first();
        return $data_ter->rate;
    }
}

function nama_bulan($bulan)
{
    switch ($bulan) {
        case 1:
            $monthNama = 'Januari';
            break;
        case 2:
            $monthNama = 'Februari';
            break;
        case 3:
            $monthNama = 'Maret';
            break;
        case 4:
            $monthNama = 'April';
            break;
        case 5:
            $monthNama = 'Mei';
            break;
        case 6:
            $monthNama = 'Juni';
            break;
        case 7:
            $monthNama = 'Juli';
            break;
        case 8:
            $monthNama = 'Agustus';
            break;
        case 9:
            $monthNama = 'September';
            break;
        case 10:
            $monthNama = 'Oktober';
            break;
        case 11:
            $monthNama = 'November';
            break;
        case 12:
            $monthNama = 'Desember';
            break;
    }
    return $monthNama;
}

function getCauserName($id)
{
    if ($id != null) {
        $data = User::find($id);
        if ($data == null) return $id;
        else return $data->name;
    }
}

function getSubjectName($id)
{
    if ($id != null) {
        $data = Karyawan::find($id);
        if ($data == null) return $id;
        else return $data->nama;
    }
}


function check_storage($id_karyawan)
{
    $data = Applicantfile::where('id_karyawan', $id_karyawan)->first();
    if ($data == null) return false;
    else return true;
}

function buat_tanggal($month, $year)
{
    if ($month < 10) $month = '0' . $month;
    return $year . '-' . $month . '-01';
}

function check_resigned_validity($month, $year, $tgl_resigned)

{
    // Convert the resignation date to a timestamp
    $resigned_timestamp = strtotime($tgl_resigned);

    // Get the start and end timestamps for the specified month and year
    $start_date = strtotime("$year-$month-01");
    $end_date = strtotime("$year-$month-01 +1 month");

    $valid = $resigned_timestamp >= $start_date && $resigned_timestamp <= $end_date;
    return $valid;
}

function selisih_hari($date1, $date2)
{
    $date1_obj = Carbon::createFromFormat('Y-m-d', $date1);
    $date2_obj = Carbon::createFromFormat('Y-m-d', $date2);
    $difference = $date2_obj->diffInDays($date1_obj);
    return $difference;
}

function dateTimeFormat($tgl)
{
    return date('d-M-Y, H:i:s', strtotime($tgl));
}

function convertTgl($tanggal)
{
    return date('Y-m-d', strtotime($tanggal));
}

function getStatusColor($kode_status)
{

    $status_color = '';
    switch ($kode_status) {
        case 1:
            $status_color = 'text-bg-primary';
            break;
        case 2:
            $status_color = 'text-bg-secondary';
            break;
        case 3:
            $status_color = 'text-warning';
            break;
        case 4:
            $status_color = 'text-bg-danger';
            break;
        case 5:
            $status_color = 'text-bg-dark';
            break;
        case 6:
            $status_color = 'text-bg-warning';
            break;
        case 7:
            $status_color = 'text-bg-info';
            break;
        case 8:
            $status_color = 'text-bg-success';
            break;
    }
    return $status_color;
}
function getNamaStatus($kode_status)
{

    $nama_status = '';
    switch ($kode_status) {
        case 1:
            $nama_status = 'Melamar';
            break;
        case 2:
            $nama_status = 'Sedang Komunikasi';
            break;
        case 3:
            $nama_status = 'Psikotest';
            break;
        case 4:
            $nama_status = 'Interview';
            break;
        case 5:
            $nama_status = 'Ditolak';
            break;
        case 6:
            $nama_status = 'Cadangan';
            break;
        case 7:
            $nama_status = 'Onboarding';
            break;
        case 8:
            $nama_status = 'Diterima';
            break;
    }
    return $nama_status;
}


function getFilenameExtension($filename)
{
    try {
        $arrNamas = explode('.', $filename);
        return $arrNamas[1];
    } catch (\Exception $e) {
        // dd($filename->getClientOriginalExtension());
        return $e->getMessage();
    }
}

function getFilename($filename)
{
    $arrNamas = explode('/', $filename);
    return $arrNamas[2];
}

function getUrl($filename)
{
    if ($filename) {
        $url = Storage::url($filename);
        return $url;
    }
}

function showImage($id)
{
    $data = Applicantfile::findOrFail($id);
    if ($data != null) {


        $fileId = $data->filename;

        // $assetPath = Storage::disk('google')->url($fileId);
        $url = Storage::url($fileId);

        // $url = Storage::disk('google')->temporaryUrl(
        //     $fileId,
        //     now()->addMinutes(5)
        // );
        // $files = Storage::disk('google')->files();
        // dd($files);

        return $url;
    }
}

function makeApplicationId($nama, $date)
{
    if ($nama != '' && $date != '') {

        $nama = trim($nama);
        $arrNamas = explode(' ', $nama);
        $arrDates = explode('-', $date);
        $nama_sambung = '';
        $date_sambung = '';
        foreach ($arrNamas as $arrNama) {
            $nama_sambung .= $arrNama . '_';
        }
        $nama_sambung = rtrim($nama_sambung, '_');

        foreach ($arrDates as $arrDate) {
            $date_sambung .= $arrDate . '_';
        }
        $date_sambung = rtrim($date_sambung, '_');

        return $nama_sambung . '_' . $date_sambung;
    }
}

function bedaMenit($startTime, $endTime)
{
    $startTime = Carbon::createFromFormat('H:i:s', $startTime);
    $endTime = Carbon::createFromFormat('H:i:s', $endTime);
    $diffInMinutes = $endTime->diffInMinutes($startTime);
    return $diffInMinutes;
}

function sambungKata($words)
{
    $arrDepts = explode(' ', $words);
    $department = '';
    foreach ($arrDepts as $arrDept) {
        $department .= $arrDept . '_';
    }
    $department = rtrim($department, '_');
    return $department;
}

function shortJam($jam)
{
    if ($jam != null) {
        $arrJam = explode(':', $jam);
        return $arrJam[0] . ':' . $arrJam[1];
    }
}

// function manfaat_libur($month, $year, $libur, $user_id, $tgl_bergabung)
// {
//     $data = Yfrekappresensi::where('user_id', $user_id)->whereMonth('date', $month)->whereYear('date', $year)->orderBy('date', 'asc')->first();
//     $tgl_mulai_kerja = Carbon::parse($data->date)->day;
//     dd($tgl_mulai_kerja);
//     $manfaat_libur = 0;
//     foreach ($libur as $l) {
//         $tgl_libur = Carbon::parse($l->tanggal_mulai_hari_libur)->day;
//         if ($tgl_libur == 1)  $manfaat_libur++;
//         if (
//             $tgl_mulai_kerja < $tgl_libur || $tgl_bergabung
//             < $tgl_libur
//         ) $manfaat_libur++;
//     }


//     return $manfaat_libur;
// }

function manfaat_libur($month, $year, $libur, $user_id, $tgl_bergabung)
{
    $data_awal = Yfrekappresensi::where('user_id', $user_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'asc')
        ->first();
    $data_akhir = Yfrekappresensi::where('user_id', $user_id)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->first();

    // Check if $data is null
    if (!$data_awal) {
        return 0; // No data found, so no benefit from holidays
    }

    $tgl_mulai_kerja = $data_awal->date;
    $tgl_akhir_kerja = $data_akhir->date;


    $manfaat_libur = 0;
    // dd($libur->count());
    $is_tgl_1 = false;
    foreach ($libur as $l) {
        $tgl_libur = $l->tanggal_mulai_hari_libur;

        // Check if the holiday falls on the first day of the month
        $tgl_libur_obj = Carbon::parse($tgl_libur);

        if ($tgl_mulai_kerja <= $tgl_libur && $tgl_akhir_kerja >= $tgl_libur) {
            $manfaat_libur++;
        }

        if ($tgl_libur_obj->day == 1) {
            $is_tgl_1 = true;
        }


        // Check if the holiday falls after the start date of work or joining date
    }
    $is_karyawan_lama = false;
    // $beginning_date = new DateTime("$year-$month-01");

    $beginning_date = buat_tanggal($month, $year);
    $is_karyawan_lama = $tgl_bergabung < $beginning_date;

    if (($is_tgl_1 && $manfaat_libur != 0 && $is_karyawan_lama) || ($is_tgl_1 && $manfaat_libur == 0 && $is_karyawan_lama)) {
        $manfaat_libur++;
    }



    // if ($user_id == 7415) {
    //     dd($manfaat_libur, $is_karyawan_lama);
    // }

    // if ($user_id == '7503') dd($is_karyawan_lama, $manfaat_libur);

    // $beginning_date = new DateTime("$year-$month-01");
    // if (($libur->count() == 1) && ($tgl_libur_obj->day == 1) && ($tgl_bergabung < $beginning_date)) {
    //     $manfaat_libur = 0;
    //     dd('hanya 1 tanggal libur dan tanggal 1');
    // } else {
    //     dd('hanya 1 tanggal libur dan tanggal 1');
    //     $manfaat_libur++;
    // }


    //   && $tgl_bergabung < $tgl_libur

    return $manfaat_libur;
}

function manfaat_libur_resigned($month, $year, $libur, $user_id, $tanggal_resigned)
{

    // $data = Karyawan::where('id_karyawan', $user_id)
    //     ->where('metode_penggajian', 'Perbulan')
    //     ->whereMonth('tanggal_resigned', $month)
    //     ->whereYear('tanggal_resigned', $year)
    //     ->first();
    $manfaat_libur_resigned = 0;

    $tgl_resigned = Carbon::parse($tanggal_resigned);
    foreach ($libur as $l) {
        $tgl_libur = $l->tanggal_mulai_hari_libur;
        // Check if the holiday falls on the first day of the month
        $tgl_libur_obj = Carbon::parse($tgl_libur);
        // $tgl_libur = Carbon::parse($l->tanggal_mulai_hari_libur)->day;
        if ($tgl_libur == 1 && $tanggal_resigned < $tgl_libur) $manfaat_libur_resigned++;

        if ($tgl_resigned > $tgl_libur) $manfaat_libur_resigned++;
    }
    // dd($user_id, $manfaat_libur_resigned);
    // if ($user_id == '1145') dd($user_id, $manfaat_libur_resigned);
    return $manfaat_libur_resigned;
}

function check_absensi_kosong()
{
    $absensiKosong =
        $data = Yfrekappresensi::where('first_in', null)
        ->where('first_out', null)
        ->where('second_in', null)
        ->where('second_out', null)
        ->where('overtime_in', null)
        ->where('overtime_out', null)
        ->count();
    return $absensiKosong;
}

function check_id_presensi()
{
    $karyawanHadir = DB::table('yfpresensis')
        ->select('user_id', 'date')
        ->distinct()
        ->get();

    $no_id = [];
    foreach ($karyawanHadir as $k) {
        $data = User::where('username', trim($k->user_id))->first();

        if ($data == null) {
            $no_id[] = $k->user_id; // Use [] to append to the array instead of overwriting it
        }
    }

    return $no_id;
}

function get_placement($id)
{
    $data = Karyawan::where('id_karyawan', $id)->first();
    if ($data == null) {
        dd('ID tidak diketemukan : ' . $id);
    } else {

        return $data->placement_id;
    }
}
function getTotalWorkingDays($year, $month)
{
    $totalDays = Carbon::createFromDate($year, $month, 1)->daysInMonth;
    $workingDays = 0;

    for ($day = 1; $day <= $totalDays; $day++) {
        $date = Carbon::createFromDate($year, $month, $day);
        // Check if it's not Sunday (0 represents Sunday)
        if ($date->dayOfWeek != Carbon::SUNDAY) {
            $workingDays++;
        }
    }
    return $workingDays;
}

function is_puasa($tgl)
{
    // Start date dan end date = tanggal mulai dan akhir puasa
    $start_date = '2024-03-12';
    $end_date = '2024-04-09';
    // Jika ada tanggal spesial lainnya, tambahkan di array $special_dates
    $special_dates = ['2024-04-20', '2024-04-27'];
    if ($tgl >= $start_date && $tgl <= $end_date) return true;
    else if (in_array($tgl, $special_dates)) return true;
    return false;
}



function is_libur_nasional($tanggal)
{
    $data = Liburnasional::where('tanggal_mulai_hari_libur', $tanggal)->first();
    if ($data != null) return true;
    return false;
}

function is_halfday($first_in, $first_out, $second_in, $second_out)
{
    if ($first_in != null  && $first_out != null && $second_in == null && $second_out == null) {
        return 1;
    } else if ($first_in == null  && $first_out == null && $second_in != null && $second_out != null) {
        return 2;
    } else {
        return 0;
    }
}

function jumlah_libur_nasional($month, $year)
{
    return Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->sum('jumlah_hari_libur');
}

function countWorkingDays($month, $year, $ignore)
{
    // $ignore = 0-6 = sunday - saturday, ex. countDays(2024, 1, array(0,6))-> exclude saturday and sunday
    $count = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (in_array(date("w", $counter), $ignore) == false) {
            $count++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return $count;
}


function acakPassword($nama)
{
    $arrNama = explode(' ', $nama);
    return Hash::make($arrNama[0] . '_out_' . $arrNama[0]);
}
function acakEmail($nama, $id)
{
    $arrNama = explode(' ', $nama);

    return $arrNama[0]  . '_' . $arrNama[0] . '_' . $id . '@' . $arrNama[0] . '.'
        .     'out';
}
function is_data_locked()
{
    $data = Lock::find(1);
    if ($data->data == 1) {
        return true;
    } else {
        return false;
    }
}

function adjustSalary()
{
    $ninetyDaysAgo = Carbon::now()->subDays(90);
    $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
    $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
    $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
    $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
    $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);

    // 90 <= 119
    $data = Karyawan::where('tanggal_bergabung', '<=', $ninetyDaysAgo)->where('tanggal_bergabung', '>', $hundredTwentyDaysAgo)
        ->where('gaji_pokok', '<', 2100000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('department_id', [3, 5])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2100000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }



    // 120 < 149
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredTwentyDaysAgo)->where('tanggal_bergabung', '>', $hundredFiftyDaysAgo)
        ->where('gaji_pokok', '<', 2200000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('department_id', [3, 5])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2200000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }


    // 150 < 179
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredFiftyDaysAgo)->where('tanggal_bergabung', '>', $hundredEigtyDaysAgo)
        ->where('gaji_pokok', '<', 2300000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('department_id', [3, 5])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2300000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }

    // 180 < 209
    $data = Karyawan::where('tanggal_bergabung', '<=', $hundredEigtyDaysAgo)->where('tanggal_bergabung', '>', $twoHundredTenDaysAgo)
        ->where('gaji_pokok', '<', 2400000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('department_id', [3, 5])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2400000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }

    // 210 < 240
    $data = Karyawan::where('tanggal_bergabung', '<=', $twoHundredTenDaysAgo)
        // ->where('tanggal_bergabung', '>', $twoHundredFortyDaysAgo)
        ->where('gaji_pokok', '<', 2500000)
        ->whereNot('gaji_pokok', 0)
        ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
        ->whereNotIn('department_id', [3, 5])
        ->orderBy('tanggal_bergabung', 'desc')
        ->get();
    $gaji_rekomendasi = 2500000;
    if ($data != null) {
        foreach ($data as $d) {
            $d = Karyawan::find($d->id);
            $d->gaji_pokok = $gaji_rekomendasi;
            $d->save();
        }
    }
}

function role_name($role)
{
    switch ($role) {
        case 0:
            $roleName = "Board of Director";
            break;
        case 1:
            $roleName = "User";
            break;
        case 2:
            $roleName = "Request";
            break;
        case 4:
            $roleName = "Junior Admin";
            break;
        case 5:
            $roleName = "Admin";
            break;
        case 6:
            $roleName = "Senior Admin";
            break;
        case 7:
            $roleName = "Super Admin";
            break;
    }
    return $roleName;
}

function ratarata($ndays)
{
    $events = 0;
    $avg = 0;
    $i = 0;
    $cx = 0;
    while ($cx < $ndays) {
        $today = Carbon::today();
        $events = Yfrekappresensi::whereDate('date',  $today->subDays($i + 1))->count();
        if ($events != 0) {
            $avg += $events;
            $data[] = $events;
            $cx++;
        }
        $i++;
    }
    return round($avg / $ndays);
}

function is_40_days($month, $year)
{
    $tgl = $year . '-' . $month . '-01';

    $fortyDaysAgo = Carbon::now()->subDays(40);
    $yourDate = Carbon::parse($tgl);
    if ($yourDate->lessThan($fortyDaysAgo)) {
        // Date is more than 35 days ago
        // Your logic here
        // echo 'Date is more than 35 days ago.';
        return true;
    } else {
        // Date is 35 days ago or less
        // Your logic here
        // echo 'Date is 35 days ago or less.';
        return false;
    }
}

function lama_bekerja($tgl_mulai_kerja, $tgl_resigned)
{
    $tgl_mulai_kerja = Carbon::parse($tgl_mulai_kerja);
    $tgl_resigned = Carbon::parse($tgl_resigned);
    $days = $tgl_resigned->diffinDays($tgl_mulai_kerja);
    return $days;
}

function JumlahHariCuti($user_id, $tanggal_resigned, $month, $year)
{
    $dataResignedArr = [];
    $cutiArr = [];
    $data = Karyawan::where('id_karyawan', $user_id)
        ->where('tanggal_resigned', '!=', null)
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->first();

    $haricuti = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->orderBy('tanggal_mulai_hari_libur', 'asc')
        ->get();

    foreach ($haricuti as $h) {
        if ($h->jumlah_hari_libur == 1) {
            $cutiArr[] = [
                'tgl' => $h->tanggal_mulai_hari_libur,
            ];
        } else {
            $i = 0;
            for ($i = 0; $i < $h->jumlah_hari_libur; $i++) {
                $cutiArr[] = [
                    'tgl' => Carbon::parse($h->tanggal_mulai_hari_libur)
                        ->addDay($i)
                        ->format('Y-m-d'),
                ];
            }
        }
    }

    // foreach ($data as $d) {
    $startDate = Carbon::parse($data->tanggal_bergabung);
    $endDate = Carbon::parse($tanggal_resigned);
    $days = $endDate->diffinDays($startDate);
    $cuti = 0;

    foreach ($cutiArr as $c) {
        $resigned = Carbon::createFromFormat('Y-m-d', $tanggal_resigned);
        $libur = Carbon::createFromFormat('Y-m-d', $c['tgl']);
        if ($resigned->gt($libur)) {
            $cuti++;
        }
    }

    return $cuti;
}

function jumlah_hari_resign($tanggal_bergabung, $tanggal_resigned)
{
    $bergabung = Carbon::parse($tanggal_bergabung);
    $resigned = Carbon::parse($tanggal_resigned);
    if ($tanggal_resigned == null) {
        return null;
    } else {
        return $resigned->diffInDays($tanggal_bergabung);
    }
}

function lama_resign($tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist)
{
    // dd($tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist);
    $bergabung = Carbon::parse($tanggal_bergabung);
    $resigned = Carbon::parse($tanggal_resigned);
    $blacklist = Carbon::parse($tanggal_blacklist);

    if ($tanggal_resigned == null && $tanggal_blacklist != null) {
        return $blacklist->diffInDays($tanggal_bergabung);
    } elseif ($tanggal_resigned != null && $tanggal_blacklist == null) {
        return $resigned->diffInDays($tanggal_bergabung);
    } else {
        return null;
    }
}

function convert_numeric($number)
{
    $number = trim($number, "Rp\u{A0}");
    $arrNumber = explode('.', $number);
    $numberString = '';
    for ($i = 0; $i < count($arrNumber); $i++) {
        $numberString = $numberString . $arrNumber[$i];
    }
    return (int) $numberString;
}

function month_year($tgl)
{
    $date = Carbon::createFromFormat('Y-m-d', $tgl);
    $monthName = $date->format('F');
    $year = $date->format('Y');
    return $monthName . ' ' . $year;
}

function check_bulan($tgl, $bulan, $tahun)
{
    $arrTgl = explode('-', $tgl);
    if ($arrTgl[0] == $tahun && $arrTgl[1] == $bulan) {
        return true;
    } else {
        return false;
    }
}

function nama_file_excel($nama_file, $month, $year)
{
    $arrNamaFile = explode('.', $nama_file);
    return $arrNamaFile[0] . '_' . monthName($month) . '_' . $year . '.' . $arrNamaFile[1];
}

function ada_tambahan($id)
{
    $data = Tambahan::where('user_id', $id)->first();
    if ($data == null) {
        return false;
    } else {
        return true;
    }
}

function monthName($tgl)
{
    if ($tgl < 1 || $tgl > 12) {
        $tgl = now()->month;
    }
    switch ($tgl) {
        case 1:
            $monthNama = 'Januari';
            break;
        case 2:
            $monthNama = 'Februari';
            break;
        case 3:
            $monthNama = 'Maret';
            break;
        case 4:
            $monthNama = 'April';
            break;
        case 5:
            $monthNama = 'Mei';
            break;
        case 6:
            $monthNama = 'Juni';
            break;
        case 7:
            $monthNama = 'Juli';
            break;
        case 8:
            $monthNama = 'Agustus';
            break;
        case 9:
            $monthNama = 'September';
            break;
        case 10:
            $monthNama = 'Oktober';
            break;
        case 11:
            $monthNama = 'November';
            break;
        case 12:
            $monthNama = 'Desember';
            break;
    }
    return $monthNama;
}

function absen_kosong($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in == '' && $first_out == '' && $second_in == '' && $second_out == '' && $overtime_in == '' && $overtime_out == '') {
        // if($first_in == null && $first_out ==  null && $second_in ==  null && $second_out ==  null && $overtime_in ==  null && $overtime_out == null) {
        return true;
    } else {
        return false;
    }
}

function is_sunday($tgl)
{
    if ($tgl) {
        return Carbon::parse($tgl)->isSunday();
    }
}

function clear_locks()
{
    $lock = Lock::find(1);
    $lock->upload = 0;
    $lock->build = 0;
    $lock->payroll = 0;
    $lock->save();
}
function langsungLembur($second_out, $tgl, $shift, $jabatan, $placement_id)
{

    // betulin
    if ($second_out != null) {
        $t2 = strtotime($second_out);
        if (!is_saturday($tgl) && $shift == 'Pagi' && $t2 < strtotime('04:00:00')) {
            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
            $diff = $diff + 7;
            return $diff;
        }
    }
    if (is_puasa($tgl) && $placement_id == 6) {
        if ($second_out != null) {
            $lembur = 0;
            $t2 = strtotime($second_out);
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {

                    if (is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:30:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $t2 = strtotime($second_out);
                            // sini

                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {
                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                            } else {
                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }

                    // kl
                } else {

                    if (is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi

                    if (is_saturday($tgl)) {
                        if ($t2 < strtotime('15:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('17:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    //Shift Malam

                    if (is_saturday($tgl)) {
                        if ($t2 < strtotime('03:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('03:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }
            return $diff;
        } else {
            return $lembur = 0;
        }
    } else {
        if ($second_out != null) {

            $lembur = 0;

            $t2 = strtotime($second_out);

            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if (is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:30:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {


                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if (is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (is_saturday($tgl)) {
                        if ($t2 < strtotime('15:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('17:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if (is_saturday($tgl)) {
                        if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }

            return $diff;
        } else {
            return $lembur = 0;
        }
    }
}

function tgl_doang($tgl)
{
    $dt = Carbon::parse($tgl);
    return $dt->day;
}

function hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    if (is_puasa($tgl) && $placement_id == 6) {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    } else {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    }



    // lolo
    if (is_sunday($tgl)) {

        // $t1 = strtotime($first_in);
        // $t2 = strtotime($second_out);
        // $t1 = strtotime(pembulatanJamOvertimeIn($first_in));
        // $t2 = strtotime(pembulatanJamOvertimeOut($second_out));



        // $diff = gmdate('H:i:s', $t2 - $t1);

        // $diff = explode(':', $diff);
        // $jam = (int) $diff[0];
        // $menit = (int) $diff[1];

        // if ($menit >= 45) {
        //     $jam = $jam + 1;
        // } elseif ($menit < 45 && $menit > 15) {
        //     $jam = $jam + 0.5;
        // } else {
        //     $jam;
        // }
        // $jam_kerja = $jam * 2;
        $jam_kerja *= 2;
    }
    if ($jabatan == 17 && is_sunday($tgl) == false) {
        $jam_kerja = 12;
        // $jam_kerja = $jam_kerja - $total_late;
    }

    return $jam_kerja;
}

function karyawan_allow_edit($id, $role)
{
    $data = Karyawan::find($id);
    if ($role < 3 && $data->gaji_pokok > 4500000) {
        return 0;
    } else {
        return 1;
    }
}

function checkNonRegisterUser()
{
    $rekap = Yfrekappresensi::distinct('user_id')->get('user_id');
    $array = [];
    foreach ($rekap as $r) {
        $karyawan = Karyawan::where('id_karyawan', $r->user_id)->first();
        if ($karyawan === null) {
            $array[] = [
                'Karyawan_id' => $r->user_id,
            ];
        }
    }
    return $array;
}

function lamaBekerja($tgl)
{
    $date = Carbon::parse($tgl);
    $now = Carbon::now();
    $diff = $date->diffIndays($now);
    $tahun = floor($diff / 365);
    if ($diff < 30) {
        return $diff . ' Hari';
    }
    if ($tahun < 1) {
        $month = floor($diff / 30);
        return (int) $month . ' Bulan';
    }
    $month = floor(($diff % ($tahun * 365)) / 30);
    return (int) $tahun . ' Tahun ' . (int) $month . ' Bulan';
}

function isDesktop()
{
    if (auth()->user()->device == 1) {
        return 1;
    } else {
        return 0;
    }
}

function namaDiAside($nama)
{
    if ($nama != null) {
        $arrJam = explode(' ', $nama);
        if (count($arrJam) == 1) {
            return $arrJam[0];
        } else {
            return $arrJam[0] . ' ' . $arrJam[1];
        }
    } else {
        return 'No Name';
    }
}

function generatePassword($tgl)
{
    if ($tgl != null) {
        $arrJam = explode('-', fixTanggal($tgl));
        $year = substr($arrJam[0], 2);
        return $arrJam[2] . $arrJam[1] . $year;
    }
}

function fixTanggal($tgl)
{
    if ($tgl != null) {
        $arrJam = explode('-', $tgl);
        if ((int) $arrJam[1] < 10) {
            $month = '0' . (int) $arrJam[1];
        } else {
            $month = $arrJam[1];
        }
        if ((int) $arrJam[2] < 10) {
            $date = '0' . (int) $arrJam[2];
        } else {
            $date = $arrJam[2];
        }

        return $arrJam[0] . '-' . $month . '-' . $date;
    }
}

function monthYear($tgl)
{
    $month = Carbon::parse($tgl)->format('F');
    $year = Carbon::parse($tgl)->format('Y');
    return $month . ' ' . $year;
}

function getBulan($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[1];
}

function addZeroToMonth($tgl)
{
    if ($tgl != null) {
        if ($tgl < 10) {
            return '0' . $tgl;
        } else {
            return $tgl;
        }
    }
}

function getTahun($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[0];
}

function buatTanggal($tgl)
{
    $arrJam = explode('-', $tgl);
    return $arrJam[0] . '-' . $arrJam[1] . '-01';
}

function pembulatanJamOvertimeIn($jam)
{
    // try {

    $arrJam = explode(':', $jam);
    if ((int) $arrJam[1] <= 3) {
        $tambahJam = (int) $arrJam[0];
        if ($tambahJam < 10) {
            $strJam = '0' . strval($tambahJam) . ':';
        } else {
            $strJam = strval($tambahJam) . ':';
        }
        return $strJam . '00:00';
    } elseif ((int) $arrJam[1] <= 33) {
        if ((int) $arrJam[0] < 10) {
            return $menit = '0' . $arrJam[0] . ':30:00';
        } else {
            return $menit = $arrJam[0] . ':30:00';
        }
    } else {
        $tambahJam = (int) $arrJam[0] + 1;
        if ($tambahJam < 10) {
            $strJam = '0' . strval($tambahJam) . ':';
        } else {
            $strJam = strval($tambahJam) . ':';
        }
        return $strJam . '00:00';
    }
    // } catch (\Exception $e) {
    //     return $e->getMessage();
    // }
}

function pembulatanJamOvertimeOut($jam)
{
    $arrJam = explode(':', $jam);
    try {
        // if (!$arrJam[1]) dd($jam);

        if ((int) $arrJam[1] >= 30) {
            if ((int) $arrJam[0] < 10) {
                return $menit = '0' . (int) $arrJam[0] . ':30:00';
            } else {
                return $menit = $arrJam[0] . ':30:00';
            }
        } else {
            if ((int) $arrJam[0] < 10) {
                return $menit = '0' . (int) $arrJam[0] . ':00:00';
            } else {
                return $menit = $arrJam[0] . ':00:00';
            }
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}

function hitungLembur($overtime_in, $overtime_out)
{
    if ($overtime_in != '' || $overtime_out != '') {
        $t1 = strtotime(pembulatanJamOvertimeIn($overtime_in));
        $t2 = strtotime(pembulatanJamOvertimeOut($overtime_out));

        $diff = gmdate('H:i:s', $t2 - $t1);
        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];
        // if ( $menit<30 ) {
        //     $menit = 0;
        // } else {
        //     $menit = 30;
        // }
        $totalMenit = $jam * 60 + $menit;

        return $totalMenit;
    } else {
        return 0;
    }
}

function fixTrimTime($data)
{
    return $data . ':00';
}

function trimTime($data)
{
    return Str::substr($data, 0, 5);
}

function late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id)
{
    $late_1 = 0;
    $late_2 = 0;
    $late_3 = 0;
    $late_4 = 0;
    $late1 = checkFirstInLate($first_in, $shift, $tgl, $placement_id);
    $late2 = checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id);
    $late3 = checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan, $placement_id);
    $late4 = checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id);

    // if (is_sunday($tgl) && (trim($jabatan) == 'Driver' || trim($jabatan) == 'Koki' || trim($jabatan) == 'Dapur Kantor' || trim($jabatan) == 'Dapur Pabrik')) {
    //     return 0;
    // } else {
    //     return $late1 + $late2 + $late3 + $late4;
    // }

    return $late1 + $late2 + $late3 + $late4;
}

function late_check_jam_lembur_only($overtime_in, $shift, $date)
{
    return checkOvertimeInLate($overtime_in, $shift, $date);
}

function is_jabatan_khusus($jabatan)
{
    // $jabatan = Karyawan::where('id_karyawan', $id)->first();
    switch ($jabatan) {
        case 17:
            $jabatan_khusus = 1;
            break;
        case 18:
            $jabatan_khusus = 1;
            break;
        case 19:
            $jabatan_khusus = 1;
            break;
        case 20:
            $jabatan_khusus = 1;
            break;
        case 21:
            $jabatan_khusus = 1;
            break;
        case 22:
            $jabatan_khusus = 1;
            break;

        default:
            $jabatan_khusus = 0;
    }
    return $jabatan_khusus;
}

function late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl, $id)
{
    // koko
    // $late = null;
    // $late1 = null;
    // $late2 = null;
    // $late3 = null;
    // $late4 = null;
    // ffff

    try {
        $data_jabatan = Karyawan::where('id_karyawan', $id)->first();
        $jabatan = $data_jabatan->jabatan_id;
        $jabatan_khusus = is_jabatan_khusus($jabatan);
    } catch (\Exception $e) {
        dd('ID karyawan tidak ada dalam database = ', $id);
        return $e->getMessage();
    }

    $late5 = null;

    // if(($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)){
    if (($second_in === '' && $second_out === '') || ($first_in === '' && $first_out === '')) {
        // $data->late = 1;
        // dd($data->late, $data->user_id);
        return $late = 1;
    }

    if (checkFirstInLate($first_in, $shift, $tgl, get_placement($id))) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late1 = 1;
    }
    if (checkFirstOutLate($first_out, $shift, $tgl, $jabatan_khusus, get_placement($id))) {
        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
    }
    if (checkSecondOutLate($second_out, $shift, $tgl, $jabatan, get_placement($id))) {
        //  return $late = $late + 1;
        // if ($jabatan_khusus != '1') {
        //     return $late = 1;
        // }
        return $late = 1;

        // return $late = 1;
        // $late3 = 1;
    }

    // if ( checkOvertimeInLate( $overtime_in, $shift, $tgl ) ) {
    //     return $late = 1;
    // }
    if (checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan_khusus, get_placement($id))) {
        // return $late = $late + 1 ;

        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
        // $late5 = 1;
    }

    if ($second_in == null && $second_out == null) {
        return $late = 1;
    }
    if ($first_in == null && $first_out == null) {
        return $late = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;
}

// ook

function hoursToMinutes($jam)
{
    $arrJam = explode(':', $jam);
    $minJam = (int) $arrJam[0] * 60;
    $min = (int) $arrJam[1];
    return $minJam + $min;
}

function checkFirstInLate($check_in, $shift, $tgl, $placement_id)
{
    // rubah angka ini utk bulan puasa
    $test = $placement_id;

    $jam_mulai_pagi = '08:03';
    $strtime_pagi = '08:03:00';
    $perJam = 60;
    $late = null;
    if (is_puasa($tgl) && $placement_id == 6) {
        if ($check_in != null) {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (Carbon::parse($check_in)->betweenIncluded('05:30', $jam_mulai_pagi)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_pagi);
                    $t2 = strtotime($check_in);
                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                    if ($late <= 5 && $late > 3.5) {
                        if (is_friday($tgl)) {
                            $late = 3.5;
                        } else {
                            $late = 4;
                        }
                    } elseif ($late > 5) {
                        if (is_friday($tgl)) {
                            $late = $late - 1.5;
                        } else {
                            $late = $late - 1;
                        }
                    }
                }
            } else {

                if (Carbon::parse($check_in)->betweenIncluded('16:00', '20:03')) {
                    $late = null;
                } else {
                    $t1 = strtotime('20:03:00');
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            }
        }
    } else {
        if ($check_in != null) {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (Carbon::parse($check_in)->betweenIncluded('05:30', $jam_mulai_pagi)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_pagi);
                    $t2 = strtotime($check_in);
                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                    if ($late <= 5 && $late > 3.5) {
                        if (is_friday($tgl)) {
                            $late = 3.5;
                        } else {
                            $late = 4;
                        }
                    } elseif ($late > 5) {
                        if (is_friday($tgl)) {
                            $late = $late - 1.5;
                        } else {
                            $late = $late - 1;
                        }
                    }
                }
            } else {
                if (is_saturday($tgl)) {
                    if (Carbon::parse($check_in)->betweenIncluded('14:00', '17:03')) {
                        $late = null;
                    } else {
                        $t1 = strtotime('17:03:00');
                        $t2 = strtotime($check_in);

                        $diff = gmdate('H:i:s', $t2 - $t1);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    }
                } else {
                    if (Carbon::parse($check_in)->betweenIncluded('16:00', '20:03')) {
                        $late = null;
                    } else {
                        $t1 = strtotime('20:03:00');
                        $t2 = strtotime($check_in);

                        $diff = gmdate('H:i:s', $t2 - $t1);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    }
                }
            }
        }
    }

    return $late;
}

function checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    $late = null;
    if (is_puasa($tgl) && $placement_id == 6) {

        if ($second_out != null) {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (is_saturday($tgl)) {
                    if (Carbon::parse($second_out)->betweenIncluded('12:00', '14:59')) {
                        $t1 = strtotime('15:00:00');
                        $t2 = strtotime($second_out);
                        //kkk
                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {
                    if (Carbon::parse($second_out)->betweenIncluded('12:00', '16:59')) {
                        $t1 = strtotime('17:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else if (Carbon::parse($second_out)->betweenIncluded('09:00', '11:59')) {
                        $t1 = strtotime('12:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam + 4);
                    } else {
                        $late = null;
                    }
                }
            } else {
                if (is_saturday($tgl)) {
                    // if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59') ) {
                    if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                        $t1 = strtotime('00:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {
                    if (Carbon::parse($second_out)->betweenIncluded('00:00', '04:59')) {
                        $t1 = strtotime('05:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);

                        // ook
                    } elseif (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                        $t1 = strtotime('23:59:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam) + 4;
                    } else {
                        $late = null;
                    }
                }
            }
        }
    } else {
        if ($second_out != null) {
            if ($shift == 'Pagi') {
                // Shift Pagi
                if (is_saturday($tgl)) {
                    if (Carbon::parse($second_out)->betweenIncluded('12:00', '14:59')) {
                        $t1 = strtotime('15:00:00');
                        $t2 = strtotime($second_out);
                        //kkk
                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {
                    if (Carbon::parse($second_out)->betweenIncluded('12:00', '16:59')) {
                        $t1 = strtotime('17:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else if (Carbon::parse($second_out)->betweenIncluded('09:00', '11:59')) {
                        $t1 = strtotime('12:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam + 4);
                    } else {
                        $late = null;
                    }
                }
            } else {
                if (is_saturday($tgl)) {
                    // if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59') ) {
                    if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                        $t1 = strtotime('00:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {
                    if (Carbon::parse($second_out)->betweenIncluded('00:00', '04:59')) {
                        $t1 = strtotime('05:00:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);

                        // ook
                    } elseif (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59')) {
                        $t1 = strtotime('23:59:00');
                        $t2 = strtotime($second_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam) + 4;
                    } else {
                        $late = null;
                    }
                }
            }
        }
    }


    // if (is_sunday($tgl)) {
    //     return 0;
    // } else {
    //     return $late;
    // }

    return $late;
}

function checkOvertimeInLate($overtime_in, $shift, $tgl)
{
    $persetengahJam = 30;
    $late = null;
    if ($overtime_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($overtime_in)->betweenIncluded('12:00', '18:33')) {
                $late = null;
            } else {
                $t1 = strtotime('18:33:00');
                $t2 = strtotime($overtime_in);

                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $persetengahJam);
            }
        }
    }
    return $late;
}

function checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id)
{
    //ok
    $perJam = 60;
    $late = null;

    if (is_puasa($tgl) && $placement_id == 6) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {

                    if (Carbon::parse($first_out)->betweenIncluded('01:00', '03:00')) {
                        $t1 = strtotime('03:00:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else { // shift malam
                    if (is_saturday($tgl)) {
                        if (Carbon::parse($first_out)->betweenIncluded('17:01', '20:29')) {
                            $t1 = strtotime('20:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    } else {
                        if (Carbon::parse($first_out)->betweenIncluded('20:00', '23:29')) {
                            $t1 = strtotime('23:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    }
                }
            }
        }
    }
    return $late;
}

function checkSecondInLate($second_in, $shift, $firstOut, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    $late = null;

    if (is_puasa($tgl) && $placement_id == 6) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('13:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                        // if ($shift == 'Pagi') {
                        //     if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('13:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // } else {

                        //     if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('01:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // }
                    }
                } else { // shift malam
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('20:00', '23:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('00:00', '00:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('01:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '00:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('00:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('23:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('01:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                        //jika first out null
                        // if ($shift == 'Pagi') {
                        //     if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('13:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // } else {

                        //     if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('01:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // }
                    }

                    // if (Carbon::parse($second_in)->betweenIncluded('03:00', '04:03')) {
                    //     $late = null;
                    // } else {
                    //     $t1 = strtotime('04:03:00');
                    //     $t2 = strtotime($second_in);

                    //     $diff = gmdate('H:i:s', $t2 - $t1);
                    //     $late = ceil(hoursToMinutes($diff) / $perJam);
                    // }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if ($shift == 'Pagi') {
                            if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if (is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                } else { //shift Malam
                    if (is_saturday($tgl)) { //ini ya 
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('17:00', '20:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('21:00', '22:00')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('17:00', '21:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('21:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('21:00', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        } else {
                            //jika first out null


                            if (Carbon::parse($second_in)->betweenIncluded('20:30', '22:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('22:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        }
                    } else {
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('20:00', '23:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('00:00', '00:59')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }

                            // Shift Pagi ggg
                            if (is_friday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);
                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if ($groupIstirahat == 1) {
                                    if (Carbon::parse($second_in)->betweenIncluded('20:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '00:33')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('00:33:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } elseif ($groupIstirahat == 2) {
                                    if (Carbon::parse($second_in)->betweenIncluded('23:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('01:03:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } else {
                                    $late = null;
                                }
                            }
                        } else {
                            //jika first out null

                            if (is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $late;
}


function noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null && (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null))) {
        return 'No Scan';
    }
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null) {
        return null;
    }
    if (($first_in == null) & ($first_out != null) || ($first_in != null) & ($first_out == null)) {
        return 'No Scan';
    }
    if (($second_in == null) & ($second_out != null) || ($second_in != null) & ($second_out == null)) {
        return 'No Scan';
    }
    // if (( $second_in == null ) && ( $second_out == null )) {
    //     return 'No Scan';
    // }
    // if ( ( $first_in == null ) && ( $first_out == null ) ) {
    //     return 'No Scan';
    // }

    if (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null)) {
        return 'No Scan';
    }
}

function titleCase($data)
{
    // $data1 =  Str::of( $data )->trim( '/' );
    return Str::of($data)
        ->trim('/')
        ->title();
}

function getLastIdKaryawan()
{
    return DB::table('karyawans')->max('id_karyawan');
}

function getNextIdKaryawan()
{
    return getLastIdKaryawan() + 1;
}

function format_tgl($tgl)
{
    if ($tgl) {
        return date('d-M-Y', strtotime($tgl));
    }
}

function format_tgl_hari($tgl)
{
    if ($tgl) {
        return date('D, d-M-Y', strtotime($tgl));
    }
}

function format_jam($jam)
{
    if ($jam) {
        return Carbon::createFromFormat('H:i:s', $jam)->format('H:i');
    }
}

function is_friday($tgl)
{
    if ($tgl) {
        return Carbon::parse($tgl)->isFriday();
    }
}

function is_saturday($tgl)
{
    if ($tgl) {
        // if ( Carbon::parse( $tgl )->isSaturday() ) {
        //     return true;
        // } else {
        //     return false;
        // }
        return Carbon::parse($tgl)->isSaturday();
    }
}

function sp_recal_presensi()
{
    if (Schema::hasTable('table_name')) {
        // Do something if exists
    }
}
