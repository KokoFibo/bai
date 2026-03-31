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

        if (!((auth()->user()->role <= 6 && auth()->user()->role > 0) && $desktop == false)) {
            $data = Dashboarddata::find(1);

            // $jumlah_total_karyawan = $data->jumlah_total_karyawan;
            // $jumlah_karyawan_pria = $data->jumlah_karyawan_pria;
            // $jumlah_karyawan_wanita = $data->jumlah_karyawan_wanita;

            $karyawan_baru_mtd =  $data->karyawan_baru_mtd;
            $karyawan_resigned_mtd = $data->karyawan_resigned_mtd;
            $karyawan_blacklist_mtd = $data->karyawan_blacklist_mtd;
            $karyawan_aktif_mtd = $data->karyawan_aktif_mtd;

            $jumlah_karyawan_baru_hari_ini = $data->jumlah_karyawan_baru_hari_ini;
            $jumlah_karyawan_Resigned_hari_ini = $data->jumlah_karyawan_Resigned_hari_ini;
            $jumlah_karyawan_blacklist_hari_ini = $data->jumlah_karyawan_blacklist_hari_ini;

            // Shift Pagi dan Shift Malam

        }


        switch (auth()->user()->role) {

            case 0:
                $role_name = 'BOD';
                break;
            case 1:
                $role_name = 'User';
                break;
            case 2:
                $role_name = 'Request';
                break;
            case 4:
                $role_name = 'Junior Admin';
                break;
            case 5:
                $role_name = 'Admin';
                break;
            case 6:
                $role_name = 'Senior Admin';
                break;
            case 7:
                $role_name = 'Super Admin';
                break;
            case 8:
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
            if (auth()->user()->role != 1 && auth()->user()->role != 4 && auth()->user()->role != 2) {
                $karyawan_baru_mtd =  $data->karyawan_baru_mtd;
                $karyawan_resigned_mtd = $data->karyawan_resigned_mtd;
                $karyawan_blacklist_mtd = $data->karyawan_blacklist_mtd;
                $karyawan_aktif_mtd = $data->karyawan_aktif_mtd;

                $jumlah_karyawan_baru_hari_ini = $data->jumlah_karyawan_baru_hari_ini;
                $jumlah_karyawan_Resigned_hari_ini = $data->jumlah_karyawan_Resigned_hari_ini;
                $jumlah_karyawan_blacklist_hari_ini = $data->jumlah_karyawan_blacklist_hari_ini;
                return view('dashboard', compact([
                    'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd',
                    'jumlah_karyawan_baru_hari_ini', 'jumlah_karyawan_Resigned_hari_ini', 'jumlah_karyawan_blacklist_hari_ini', 'belum_isi_etnis', 'belum_isi_kontak_darurat'
                ]));
            } else {
                return view('user_dashboard');
            }
        } else {
            $user->device = 1;
            $user->save();
            if (auth()->user()->role >= 7 || auth()->user()->role == 0) {
                $user->device = 1;
                $user->save();

                return view('dashboard', compact([
                    'karyawan_baru_mtd', 'karyawan_resigned_mtd', 'karyawan_blacklist_mtd', 'karyawan_aktif_mtd',
                    'jumlah_karyawan_baru_hari_ini', 'jumlah_karyawan_Resigned_hari_ini', 'jumlah_karyawan_blacklist_hari_ini', 'belum_isi_etnis', 'belum_isi_kontak_darurat'
                ]));
            }
            $user->device = 0;
            $user->save();
            // return view( 'dashboardMobile1' );
            $user_id = auth()->user()->username;

            // $month = 11;


            // $user_id = 1112;
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
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id));
                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id));
                    // if($d->shift == 'Malam' || is_jabatan_khusus($d->user_id)) {
                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan_id, get_placement($d->user_id));
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
