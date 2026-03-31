<?php

namespace App\Http\Controllers;

use App\Models\Dashboarddata;
use App\Models\User;
use App\Models\Payroll;
use App\Models\Karyawan;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Spatie\Activitylog\Contracts\Activity;


$agent = new Agent();

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    public function index()
    {
        //   ini beneran di user mobile
        $year = now()->year;
        $month = now()->month;

        $agent = new Agent();
        $desktop = $agent->isDesktop();
        $user = User::find(auth()->user()->id);

        if (!((auth()->user()->role <= 3 && auth()->user()->role > 0) && $desktop == false)) {
            $data = Dashboarddata::find(1);

            // $jumlah_total_karyawan = $data->jumlah_total_karyawan;
            // $jumlah_karyawan_pria = $data->jumlah_karyawan_pria;
            // $jumlah_karyawan_wanita = $data->jumlah_karyawan_wanita;

            $jumlah_karyawan_baru_hari_ini = $data->jumlah_karyawan_baru_hari_ini;
            $jumlah_karyawan_Resigned_hari_ini = $data->jumlah_karyawan_Resigned_hari_ini;
            $jumlah_karyawan_blacklist_hari_ini = $data->jumlah_karyawan_blacklist_hari_ini;

            $karyawan_baru_mtd =  $data->karyawan_baru_mtd;
            $karyawan_resigned_mtd = $data->karyawan_resigned_mtd;
            $karyawan_blacklist_mtd = $data->karyawan_blacklist_mtd;
            $karyawan_aktif_mtd = $data->karyawan_aktif_mtd;

            // Jumlah Karyawan
            $jumlah_ASB = $data->jumlah_ASB;
            $jumlah_DPA = $data->jumlah_DPA;
            $jumlah_YCME = $data->jumlah_YCME;
            $jumlah_YEV = $data->jumlah_YEV;
            $jumlah_YIG = $data->jumlah_YIG;
            $jumlah_YSM = $data->jumlah_YSM;
            $jumlah_YAM = $data->jumlah_YAM;
            $jumlah_GAMA = $data->jumlah_GAMA;
            $jumlah_WAS = $data->jumlah_WAS;
            $jumlah_company = $data->jumlah_company;

            $jumlah_karyawanArr = [
                $jumlah_karyawan_pria, $jumlah_karyawan_wanita
            ];

            $jumlah_karyawan_labelArr = [
                'Pria 男', 'Wanita 女'
            ];



            $companyArr = [

                $jumlah_ASB,
                $jumlah_YCME,
                $jumlah_YEV,
                $jumlah_YSM,
                $jumlah_DPA,
                $jumlah_YIG,
                $jumlah_YAM,
                $jumlah_GAMA,
                $jumlah_WAS,

            ];
            $companyLabelArr = [
                'ASB', 'YCME', 'YEV',  'YSM', 'DPA', 'YIG', 'YAM', 'GAMA', 'WAS'
            ];

            // Department
            $department_BD = $data->department_BD;
            $department_Engineering = $data->department_Engineering;
            $department_EXIM = $data->department_EXIM;
            $department_Finance_Accounting = $data->department_Finance_Accounting;
            $department_GA = $data->department_GA;
            $department_Gudang = $data->department_Gudang;
            $department_HR = $data->department_HR;
            $department_Legal = $data->department_Legal;
            $department_Procurement = $data->department_Procurement;
            $department_Produksi = $data->department_Produksi;
            $department_Quality_Control = $data->department_Quality_Control;
            $department_Board_of_Director = $data->department_Board_of_Director;

            // Jabatan
            $jabatan_Admin = $data->jabatan_Admin;
            $jabatan_Asisten_Direktur = $data->jabatan_Asisten_Direktur;
            $jabatan_Asisten_Kepala = $data->jabatan_Asisten_Kepala;
            $jabatan_Asisten_Manager = $data->jabatan_Asisten_Manager;
            $jabatan_Asisten_Pengawas = $data->jabatan_Asisten_Pengawas;
            $jabatan_Asisten_Wakil_Presiden = $data->jabatan_Asisten_Wakil_Presiden;
            $jabatan_Design_grafis = $data->jabatan_Design_grafis;
            $jabatan_Director = $data->jabatan_Director;
            $jabatan_Kepala = $data->jabatan_Kepala;
            $jabatan_Manager = $data->jabatan_Manager;
            $jabatan_Pengawas = $data->jabatan_Pengawas;
            $jabatan_President = $data->jabatan_President;
            $jabatan_Senior_staff = $data->jabatan_Senior_staff;
            $jabatan_Staff = $data->jabatan_Staff;
            $jabatan_Supervisor = $data->jabatan_Supervisor;
            $jabatan_Vice_President = $data->jabatan_Vice_President;
            $jabatan_Satpam = $data->jabatan_Satpam;
            $jabatan_Koki = $data->jabatan_Koki;
            $jabatan_Dapur_Kantor = $data->jabatan_Dapur_Kantor;
            $jabatan_Dapur_Pabrik = $data->jabatan_Dapur_Pabrik;
            $jabatan_QC_Aging = $data->jabatan_QC_Aging;
            $jabatan_Driver = $data->jabatan_Driver;

            //    Kehadiran
            $countLatestHadir = $data->countLatestHadir;
            $latestDate = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->first();

            $dataCountLatestHadir = [$countLatestHadir, $jumlah_total_karyawan - $countLatestHadir];

            //  $average7Hari = ratarata (7);
            $average7Hari = [ratarata(7), $jumlah_total_karyawan - ratarata(7)];

            //  rata-rata 30 hari
            $average30Hari = [ratarata(30), $jumlah_total_karyawan - ratarata(30)];

            //  Presensi by Depertemen
            $bd = $data->bd;

            $engineering = $data->engineering;

            $exim = $data->exim;

            $finance_accounting = $data->finance_accounting;

            $ga = $data->ga;

            $gudang = $data->gudang;

            $hr = $data->hr;

            $legal = $data->legal;

            $procurement = $data->procurement;

            $produksi = $data->produksi;

            $quality_control = $data->quality_control;

            $total_presensi_by_departemen = $data->total_presensi_by_departemen;

            $presensi_by_departement_Arr = [
                $bd, $engineering, $exim, $finance_accounting, $ga, $gudang, $hr, $legal,
                $procurement, $produksi, $quality_control
            ];
            $presensi_by_departement_LabelArr = [
                'BD 业务拓展', 'Engineering 工程', 'EXIM 出口进口',  'Finance Accounting 财务会计', 'GA 综合行政', 'Gudang 仓库', 'HR 人力资源',
                'Legal 法务', 'Procurement 采购', 'Produksi 生产', 'Quality Control 质量控制'
            ];

            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
            $uniqueDates = Payroll::orderBy('date', 'asc')->distinct()->pluck('date');

            for ($i = 0; $i < $uniqueDates->count(); $i++) {
                $all = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->sum('total');
                $ASB = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'ASB')->sum('total');
                $DPA = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'DPA')->sum('total');
                $YCME = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'YCME')->sum('total');
                $YEV = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'YEV')->sum('total');
                $YIG = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'YIG')->sum('total');
                $YSM = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'YSM')->sum('total');
                $YAM = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'YAM')->sum('total');
                $GAMA = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'GAMA')->sum('total');
                $WAS = Payroll::where('date', $uniqueDates[$i])->whereIn('status_karyawan', $statuses)->where('company', 'WAS')->sum('total');
                $dataPayroll[] = [
                    'tgl' => month_year($uniqueDates[$i]),
                    'All' => $all,
                    'ASB' => $ASB,
                    'DPA' => $DPA,
                    'YCME' => $YCME,
                    'YEV' => $YEV,
                    'YIG' => $YIG,
                    'YSM' => $YSM,
                    'YAM' => $YAM,
                    'GAMA' => $GAMA,
                    'WAS' => $WAS
                ];
                $dataTgl[] = month_year($uniqueDates[$i]);
                $dataAll[] =  $all;
                $dataASB[] =  $ASB;
                $dataDPA[] =  $DPA;
                $dataYCME[] =  $YCME;
                $dataYEV[] =  $YEV;
                $dataYIG[] =  $YIG;
                $dataYSM[] =  $YSM;
                $dataYAM[] =  $YAM;
                $dataGAMA[] =  $GAMA;
                $dataWAS[] =  $WAS;
            }

            // Shift Pagi dan Shift Malam

            $shift_pagi = $data->shift_pagi;
            $shift_malam = $data->shift_malam;
            $uniqueDates = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->distinct()->pluck('date');
            $total = $shift_pagi + $shift_malam;
            if ($uniqueDates->isNotEmpty()) {
                if ($shift_pagi != null && $shift_malam != null) {
                    // $shiftPagiMalam = [round($shift_pagi / $total * 100, 1), round(100 - $shift_pagi / $total * 100, 1)];
                    $shiftPagiMalam = [round($shift_pagi / $uniqueDates->count()), round($shift_malam / $uniqueDates->count())];
                } else {
                    $shiftPagiMalam = 0;
                    $shiftPagi = 0;
                    $shiftMalam = 0;
                }
            } else {
                $shiftPagiMalam = 0;
                $shiftPagi = 0;
                $shiftMalam = 0;
            }
        }


        switch (auth()->user()->role) {
            case -1:
                $role_name = 'Junior Admin';
                break;
            case 0:
                $role_name = 'BOD';
                break;
            case 1:
                $role_name = 'User';
                break;
            case 2:
                $role_name = 'Admin';
                break;
            case 3:
                $role_name = 'Senior Admin';
                break;
            case 4:
                $role_name = 'Super Admin';
                break;
            case 5:
                $role_name = 'Developer';
                break;
        }
        activity()->log(auth()->user()->name . ', ' . $role_name . ', ID : ' . auth()->user()->username . ' Login');

        // $agent = new Agent();
        // $desktop = $agent->isDesktop();
        // $user = User::find(auth()->user()->id);
        // $user = 1112;

        $belum_isi_etnis = 0;
        $belum_isi_kontak_darurat = 0;
        $belum_isi_etnis = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->where('etnis', null)->count();
        $belum_isi_kontak_darurat = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->where('kontak_darurat', null)->count();
        // dd($isi_etnis);

        // kkk

        if ($desktop) {
            $user->device = 1;
            $user->save();
            if (auth()->user()->role != 1 && auth()->user()->role != -1) {
                return view('dashboard', compact([
                    'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita',  'jumlah_company', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV',
                    'jumlah_YIG', 'jumlah_YSM', 'jumlah_YAM', 'jumlah_GAMA', 'jumlah_WAS',
                    'department_BD', 'department_Engineering', 'department_EXIM', 'department_Finance_Accounting', 'department_GA', 'department_Gudang',
                    'department_HR', 'department_Legal', 'department_Procurement', 'department_Produksi', 'department_Quality_Control', 'department_Board_of_Director',
                    'jabatan_Admin', 'jabatan_Asisten_Direktur', 'jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager', 'jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
                    'jabatan_Design_grafis', 'jabatan_Director', 'jabatan_Kepala', 'jabatan_Manager', 'jabatan_Pengawas', 'jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
                    'jabatan_Supervisor', 'jabatan_Vice_President', 'jabatan_Satpam', 'jabatan_Koki', 'jabatan_Dapur_Kantor',
                    'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver',  'companyLabelArr', 'companyArr', 'jumlah_karyawan_labelArr', 'jumlah_karyawanArr',
                    'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd',
                    'countLatestHadir', 'latestDate', 'dataCountLatestHadir', 'average7Hari', 'average30Hari', 'dataPayroll', 'dataTgl', 'dataAll',
                    'dataASB', 'dataDPA', 'dataYCME', 'dataYEV', 'dataYAM', 'dataYIG', 'dataYSM', 'dataGAMA', 'dataWAS', 'latestDate', 'shiftPagiMalam',
                    'bd', 'engineering', 'exim', 'finance_accounting', 'ga', 'gudang', 'hr', 'legal',
                    'procurement', 'produksi', 'quality_control', 'total_presensi_by_departemen',
                    'presensi_by_departement_Arr', 'presensi_by_departement_LabelArr',
                    'jumlah_karyawan_baru_hari_ini', 'jumlah_karyawan_Resigned_hari_ini', 'jumlah_karyawan_blacklist_hari_ini', 'belum_isi_etnis', 'belum_isi_kontak_darurat'

                ]));
            } else {
                return view('user_dashboard');
            }
        } else {
            $user->device = 1;
            $user->save();
            if (auth()->user()->role >= 4 || auth()->user()->role == 0) {
                $user->device = 1;
                $user->save();

                return view('dashboard', compact([
                    'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita',  'jumlah_company', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV',
                    'jumlah_YIG', 'jumlah_YSM', 'jumlah_YAM',
                    'jumlah_GAMA', 'jumlah_WAS',
                    'department_BD', 'department_Engineering', 'department_EXIM', 'department_Finance_Accounting', 'department_GA', 'department_Gudang',
                    'department_HR', 'department_Legal', 'department_Procurement', 'department_Produksi', 'department_Quality_Control', 'department_Board_of_Director',
                    'jabatan_Admin', 'jabatan_Asisten_Direktur', 'jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager', 'jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
                    'jabatan_Design_grafis', 'jabatan_Director', 'jabatan_Kepala', 'jabatan_Manager', 'jabatan_Pengawas', 'jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
                    'jabatan_Supervisor', 'jabatan_Vice_President', 'jabatan_Satpam', 'jabatan_Koki', 'jabatan_Dapur_Kantor',
                    'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver',   'companyLabelArr', 'companyArr',
                    'jumlah_karyawan_labelArr', 'jumlah_karyawanArr',
                    'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd',
                    'countLatestHadir', 'latestDate', 'dataCountLatestHadir', 'average7Hari', 'average30Hari', 'dataPayroll', 'dataTgl', 'dataAll',
                    'dataASB', 'dataDPA', 'dataYCME', 'dataYEV', 'dataYAM', 'dataYIG', 'dataYSM',
                    'dataGAMA', 'dataWAS', 'latestDate', 'shiftPagiMalam',
                    'bd', 'engineering', 'exim', 'finance_accounting', 'ga', 'gudang', 'hr', 'legal',
                    'procurement', 'produksi', 'quality_control', 'total_presensi_by_departemen',
                    'presensi_by_departement_Arr', 'presensi_by_departement_LabelArr',
                    'jumlah_karyawan_baru_hari_ini', 'jumlah_karyawan_Resigned_hari_ini', 'jumlah_karyawan_blacklist_hari_ini',
                    'belum_isi_etnis', 'belum_isi_kontak_darurat'

                ]));
            }
            $user->device = 0;
            $user->save();
            // return view( 'dashboardMobile1' );
            $user_id = auth()->user()->username;

            // $user_id = 1112;
            $month = 11;
            // $total_hari_kerja = Yfrekappresensi::whereMonth('date', '=', 11)
            //     ->distinct('date')
            //     ->count();

            $total_hari_kerja = 0;


            $total_jam_kerja = 0;
            $total_jam_lembur = 0;
            $total_keterlambatan = 0;
            $langsungLembur = 0;

            $dataArr = [];
            $data = Yfrekappresensi::where('user_id', $user_id)
                ->orderBy('date', 'desc')
                ->get();

            foreach ($data as $d) {
                if ($d->no_scan == null) {
                    $tgl = tgl_doang($d->date);
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan, get_placement($d->user_id));
                    // if($d->shift == 'Malam' || is_jabatan_khusus($d->user_id)) {
                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan, get_placement($d->user_id));
                    // }
                    $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                    $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                    $total_jam_lembur = $total_jam_lembur + $jam_lembur;
                    $total_keterlambatan = $total_keterlambatan + $terlambat;

                    $dataArr[] = [
                        'tgl' => $tgl,
                        'jam_kerja' => $jam_kerja,
                        'terlambat' => $terlambat,
                        'jam_lembur' => $jam_lembur,
                    ];
                    $total_hari_kerja++;
                }
            }
            return  redirect('/usermobile');


            // return view('mobile')->with([
            //     'dataArr' => $dataArr,
            //     'total_hari_kerja' => $total_hari_kerja,
            //     'total_jam_kerja' => $total_jam_kerja,
            //     'total_jam_lembur' => $total_jam_lembur,
            //     'total_keterlambatan' => $total_keterlambatan,
            // ]);


        }
    }
}
