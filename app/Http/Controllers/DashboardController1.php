<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Jamkerjaid;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{

    public function index()
    {
        dd('index dashboard ');
        $year = now()->year;
        $month = now()->month;
        $jumlah_total_karyawan = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_karyawan_pria = Karyawan::where('gender', 'Laki-laki')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_karyawan_wanita = Karyawan::where('gender', 'Perempuan')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

        $jumlah_karyawan_baru_hari_ini = Karyawan::where('tanggal_bergabung', today())->count();
        $jumlah_karyawan_Resigned_hari_ini = Karyawan::where('tanggal_resigned', today())->count();
        $jumlah_karyawan_blacklist_hari_ini = Karyawan::where('tanggal_blacklist', today())->count();

        $karyawan_baru_mtd = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->whereMonth('tanggal_bergabung', $month)
            ->whereYear('tanggal_bergabung', $year)
            ->count();

        $karyawan_resigned_mtd = Karyawan::where('status_karyawan', 'Resigned')
            ->whereMonth('tanggal_resigned', $month)
            ->whereYear('tanggal_resigned', $year)
            ->count();

        $karyawan_blacklist_mtd = Karyawan::where('status_karyawan', 'Blacklist')
            ->whereMonth('tanggal_blacklist', $month)
            ->whereYear('tanggal_blacklist', $year)
            ->count();

        $karyawan_aktif_mtd = Payroll::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->count();


        // Jumlah Karyawan

        $jumlah_ASB = Karyawan::where('company', 'ASB')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_DPA = Karyawan::where('company', 'DPA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_YCME = Karyawan::where('company', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_YEV = Karyawan::where('company', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_YIG = Karyawan::where('company', 'YIG')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_YSM = Karyawan::where('company', 'YSM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_YAM = Karyawan::where('company', 'YAM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_Pabrik_1 = Karyawan::where('placement', 'YCME')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_Pabrik_2 = Karyawan::where('placement', 'YEV')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_Kantor = Karyawan::whereIn('placement', ['YSM', 'YIG'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jumlah_placement =  $jumlah_Pabrik_1 + $jumlah_Pabrik_2 + $jumlah_Kantor;
        $jumlah_company =  $jumlah_ASB + $jumlah_DPA + $jumlah_YCME + $jumlah_YEV + $jumlah_YIG +  $jumlah_YSM +  $jumlah_YAM;

        $jumlah_karyawanArr = [
            $jumlah_karyawan_pria, $jumlah_karyawan_wanita
        ];

        $jumlah_karyawan_labelArr = [
            'Pria 男', 'Wanita 女'
        ];


        $placementArr = [
            $jumlah_Pabrik_1, $jumlah_Pabrik_2,  $jumlah_Kantor
        ];
        $placementLabelArr = [
            'Pabrik 1 工厂1', 'Pabrik 2 工厂2',  'Kantor 办公室'
        ];



        $companyArr = [

            $jumlah_ASB,
            $jumlah_YCME,
            $jumlah_YEV,
            $jumlah_YSM,
            $jumlah_DPA,
            $jumlah_YIG,
            $jumlah_YAM
        ];
        $companyLabelArr = [
            'ASB', 'YCME', 'YEV',  'YSM', 'DPA', 'YIG', 'YAM'
        ];



        // Department
        $department_BD = Karyawan::where('departemen', 'BD')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Engineering = Karyawan::where('departemen', 'Engineering')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_EXIM = Karyawan::where('departemen', 'EXIM')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Finance_Accounting = Karyawan::where('departemen', 'Finance Accounting')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_GA = Karyawan::where('departemen', 'GA')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Gudang = Karyawan::where('departemen', 'Gudang')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_HR = Karyawan::where('departemen', 'HR')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Legal = Karyawan::where('departemen', 'Legal')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Procurement = Karyawan::where('departemen', 'Procurement')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Produksi = Karyawan::where('departemen', 'Produksi')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Quality_Control = Karyawan::where('departemen', 'Quality Control')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $department_Board_of_Director = Karyawan::where('departemen', 'Board of Director')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

        // Jabatan
        $jabatan_Admin = Karyawan::where('jabatan', 'Admin')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Asisten_Direktur = Karyawan::where('jabatan', 'Asisten Direktur')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Asisten_Kepala = Karyawan::where('jabatan', 'Asisten Kepala')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Asisten_Manager = Karyawan::where('jabatan', 'Asisten Manager')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Asisten_Pengawas = Karyawan::where('jabatan', 'Asisten Pengawas')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Asisten_Wakil_Presiden = Karyawan::where('jabatan', 'Asisten Wakil Presiden')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Design_grafis = Karyawan::where('jabatan', 'Design grafis')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Director = Karyawan::where('jabatan', 'Director')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Kepala = Karyawan::where('jabatan', 'Kepala')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Manager = Karyawan::where('jabatan', 'Manager')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Pengawas = Karyawan::where('jabatan', 'Pengawas')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_President = Karyawan::where('jabatan', 'President')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Senior_staff = Karyawan::where('jabatan', 'Senior staff')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Staff = Karyawan::where('jabatan', 'Staff')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Supervisor = Karyawan::where('jabatan', 'Supervisor')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Vice_President = Karyawan::where('jabatan', 'Vice President')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Satpam = Karyawan::where('jabatan', 'Satpam')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Koki = Karyawan::where('jabatan', 'Koki')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Dapur_Kantor = Karyawan::where('jabatan', 'Dapur Kantor')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Dapur_Pabrik = Karyawan::where('jabatan', 'Dapur Pabrik')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_QC_Aging = Karyawan::where('jabatan', 'QC Aging')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();
        $jabatan_Driver = Karyawan::where('jabatan', 'Driver')->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->count();

        //    Kehadiran
        $countLatestHadir = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->count();
        $latestDate = Yfrekappresensi::where('date', Yfrekappresensi::max('date'))->first();

        $dataCountLatestHadir = [$countLatestHadir, $jumlah_total_karyawan - $countLatestHadir];

        //  rata-rata 7 hari
        $average7Hari = [ratarata(7), $jumlah_total_karyawan - ratarata(7)];

        //  rata-rata 30 hari
        $average30Hari = [ratarata(30), $jumlah_total_karyawan - ratarata(30)];

        //  Presensi by Depertemen
        $bd = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'BD')
            // ->where('date', Yfrekappresensi::max('date'))->count();
            ->where('date', Yfrekappresensi::max('date'))->count();


        $engineering = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Engineering')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $exim = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'EXIM')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $finance_accounting = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Finance Accounting')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $ga = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'GA')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $gudang = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Gudang')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $hr = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'HR')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $legal = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Legal')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $procurement = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Procurement')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $produksi = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Produksi')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $quality_control = Karyawan::join('yfrekappresensis', 'karyawans.id', '=', 'yfrekappresensis.karyawan_id')
            ->select('karyawans.*', 'yfrekappresensis.*')
            ->where('departemen', 'Quality Control')
            ->where('date', Yfrekappresensi::max('date'))->count();

        $total_presensi_by_departemen = $bd + $engineering + $exim + $finance_accounting + $ga + $gudang + $hr + $legal +
            $procurement + $produksi + $quality_control;

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
            $dataPayroll[] = [
                'tgl' => month_year($uniqueDates[$i]),
                'All' => $all,
                'ASB' => $ASB,
                'DPA' => $DPA,
                'YCME' => $YCME,
                'YEV' => $YEV,
                'YIG' => $YIG,
                'YSM' => $YSM,
                'YAM' => $YAM
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
        }

        // Shift Pagi dan Shift Malam


        $shift_pagi = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->where('shift', 'Pagi')->count();
        $shift_malam = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->where('shift', 'Malam')->count();
        $uniqueDates = Yfrekappresensi::whereMonth('date', now()->month)->whereYear('date', now()->year)->distinct()->pluck('date');
        $total = $shift_pagi + $shift_malam;

        if ($shift_pagi != null && $shift_malam != null) {
            // $shiftPagiMalam = [round($shift_pagi / $total * 100, 1), round(100 - $shift_pagi / $total * 100, 1)];
            $shiftPagiMalam = [round($shift_pagi / $uniqueDates->count()), round($shift_malam / $uniqueDates->count())];
        } else {
            $shiftPagiMalam = 0;
            $shiftPagi = 0;
            $shiftMalam = 0;
        }


        // return view('dashboard', compact(['jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita']));
        return view('dashboard', compact([
            'jumlah_total_karyawan', 'jumlah_karyawan_pria', 'jumlah_karyawan_wanita', 'jumlah_placement', 'jumlah_company', 'jumlah_ASB', 'jumlah_DPA', 'jumlah_YCME', 'jumlah_YEV',
            'jumlah_YIG', 'jumlah_YSM', 'jumlah_YAM', 'jumlah_Kantor', 'jumlah_Pabrik_1', 'jumlah_Pabrik_2',
            'department_BD', 'department_Engineering', 'department_EXIM', 'department_Finance_Accounting', 'department_GA', 'department_Gudang',
            'department_HR', 'department_Legal', 'department_Procurement', 'department_Produksi', 'department_Quality_Control', 'department_Board_of_Director',
            'jabatan_Admin', 'jabatan_Asisten_Direktur', 'jabatan_Asisten_Kepala', 'jabatan_Asisten_Manager', 'jabatan_Asisten_Pengawas', 'jabatan_Asisten_Wakil_Presiden',
            'jabatan_Design_grafis', 'jabatan_Director', 'jabatan_Kepala', 'jabatan_Manager', 'jabatan_Pengawas', 'jabatan_President', 'jabatan_Senior_staff', 'jabatan_Staff',
            'jabatan_Supervisor', 'jabatan_Vice_President', 'jabatan_Satpam', 'jabatan_Koki', 'jabatan_Dapur_Kantor',
            'jabatan_Dapur_Pabrik', 'jabatan_QC_Aging', 'jabatan_Driver', 'placementArr', 'placementLabelArr', 'companyLabelArr', 'companyArr', 'jumlah_karyawan_labelArr', 'jumlah_karyawanArr',
            'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd',
            'countLatestHadir', 'latestDate', 'dataCountLatestHadir', 'average7Hari', 'average30Hari', 'dataPayroll', 'dataTgl', 'dataAll',
            'dataASB', 'dataDPA', 'dataYCME', 'dataYEV', 'dataYIG', 'dataYSM', 'dataYAM', 'latestDate', 'shiftPagiMalam',
            'bd', 'engineering', 'exim', 'finance_accounting', 'ga', 'gudang', 'hr', 'legal',
            'procurement', 'produksi', 'quality_control', 'total_presensi_by_departemen', 'presensi_by_departement_Arr', 'presensi_by_departement_LabelArr',
            'jumlah_karyawan_baru_hari_ini', 'jumlah_karyawan_Resigned_hari_ini', 'jumlah_karyawan_blacklist_hari_ini'

        ]));
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function makan()
    {

        return 'Makan';
    }


    public function mobile()
    {
        // ini hanya bisa di test di desktop tidak berlaku di user mobile
        $user_id = 1112;
        $month = 11;

        $total_hari_kerja = 0;
        $total_jam_kerja = 0;
        $total_jam_lembur = 0;
        $total_keterlambatan = 0;
        $langsungLembur = 0;

        $data = Yfrekappresensi::where('user_id', $user_id)->orderBy('date', 'desc')->simplePaginate(5);
        $data1 = Yfrekappresensi::where('user_id', $user_id)->get();

        foreach ($data1 as $d) {
            if ($d->no_scan == null) {
                $tgl = tgl_doang($d->date);
                $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan);
                $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan);
                $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan);
                $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                $total_jam_lembur = $total_jam_lembur + $jam_lembur;
                $total_keterlambatan = $total_keterlambatan + $terlambat;

                $total_hari_kerja++;
            }
        }

        return  redirect('/usermobile');
    }
}
