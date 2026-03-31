<?php

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Bonuspotongan;
use App\Models\Jamkerjaid;
use App\Models\Liburnasional;
use App\Models\Lock;
use App\Models\Yfrekappresensi;
//ok 1

//Ori

function build_payroll_os_new($month, $year)
{
    // $lock = Lock::find(1);
    // $lock->rebuild_done = 2;
    // $lock->save();

    // $start = microtime(true);

    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)->whereYear('tanggal_mulai_hari_libur', $year)->orderBy('tanggal_mulai_hari_libur', 'asc')->get('tanggal_mulai_hari_libur');
    $total_n_hari_kerja = getTotalWorkingDays($year, $month);
    $startOfMonth = Carbon::parse($year . '-' . $month . '-01');
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    $cx = 0;
    // isi ini dengan false jika mau langsung
    $pass = true;
    delete_failed_jobs();


    $jumlah_libur_nasional = jumlah_libur_nasional($month, $year);

    $adaPresensi = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->count();

    if ($adaPresensi === 0) {
        clear_locks();
        return 0;
    }

    // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
    $last_data_date = Yfrekappresensi::query()
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->first();

    if ($pass) {
        Jamkerjaid::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();
        delete_failed_jobs();
    }

    // dd('ok1 sampai sini');

    $jumlah_jam_terlambat = null;
    $jumlah_menit_lembur = null;
    $dt_name = null;
    $dt_date = null;
    $dt_karyawan_id = null;
    $late = null;
    $late1 = null;
    $late2 = null;
    $late3 = null;
    $late4 = null;
    $late5 = null;

    $filterArray = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        // ->where('status')
        ->pluck('user_id')
        ->unique();

    if ($filterArray == null) {
        return 0;
    }



    // disini mulai prosesnya

    //     foreach ($filteredData as $data) {
    // proses ini yg lama1
    if ($pass) {


        // ambil data per user id
        // $n_noscan = 0;
        // $total_hari_kerja = 0;
        // $total_jam_kerja = 0;
        // $total_jam_lembur = 0;
        // $langsungLembur = 0;
        // $tambahan_shift_malam = 0;
        // $total_keterlambatan = 0;
        // $total_tambahan_shift_malam = 0;
        // $jam_kerja_libur = 0;
        //loop ini utk 1 user selama 22 hari
        // $get_placement = get_placement($dataId[0]->user_id);

        $hari_kerja = 0;
        $jam_kerja = 0;
        $jam_lembur = 0;
        $jumlah_jam_terlambat = 0;
        $tambahan_jam_shift_malam = 0;

        // $karyawans = Karyawan::whereNot('status_karyawan', 'Blacklist')->select('id_karyawan')->get();
        // Payroll::whereMonth('date', $month)
        //     ->whereYear('date', $year)
        //     ->delete();
        // foreach ($karyawans as $k) {
        //     $hari_kerja = 0;
        //     $jam_kerja = 0;
        //     $jam_lembur = 0;
        //     $jumlah_jam_terlambat = 0;
        //     $tambahan_jam_shift_malam = 0;
        //     // $presensi = Yfrekappresensi::with('karyawan:id,jabatan_id,status_karyawan,metode_penggajian,placement_id,tanggal_blacklist')
        //     $presensi = Yfrekappresensi::where('karyawan_id', $k->id_karyawan)
        //         ->where('date', '>=', $startOfMonth)
        //         ->where('date', '<=', $endOfMonth)
        //         ->orderBy('date', 'desc')
        //         ->get();
        //     foreach ($presensi as $p) {
        //         $hari_kerja = $hari_kerja + $p->total_hari_kerja;
        //         $jam_kerja = $jam_kerja + $p->total_jam_kerja;
        //         $jam_lembur = $jam_lembur + $p->total_jam_lembur;
        //         $jumlah_jam_terlambat = $jumlah_jam_terlambat + $p->late;
        //         if ($p->shift == 'Malam' && $p->total_jam_kerja >= 8) {
        //             $tambahan_jam_shift_malam++;
        //         }
        //     }
        //     // save data ke payroll
        //     Payroll::create([
        //         'hari_kerja' => $hari_kerja,
        //         'jam_kerja' => $jam_kerja,
        //         'jam_lembur' => $jam_lembur,
        //         'jumlah_jam_terlambat' => $jumlah_jam_terlambat,
        //         'tambahan_jam_shift_malam' => $tambahan_jam_shift_malam,
        //         'id_karyawan' => $k->id_karyawan,
        //         // 'date' => buatTanggal($d->date),
        //         // last_data_date => $last_data_date->date,
        //     ]);

        //     // jam_kerja_libur => $jam_kerja_libur,
        //     // total_noscan => $n_noscan,
        //     // karyawan_id => $d->karyawan->id,
        //     // date => buatTanggal($d->date),
        //     // last_data_date => $last_data_date->date,
        //     // created_at => now()->toDateTimeString(),
        //     // updated_at => now()->toDateTimeString(),
        // }
        // dd('stop1');

        // if ($n_noscan == 0) {
        //     $n_noscan = null;
        // }

        // $karyawanIds = Karyawan::whereNot('status_karyawan', 'Blacklist')
        //     ->pluck('id_karyawan'); // Ambil hanya ID karyawan




        // Hapus data lama sebelum insert ulang
        Payroll::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();
        // dd('deleted');
        // Query untuk mengambil data presensi + informasi karyawan (tanpa menggunakan eager loading `with()`)
        $presensiData = Yfrekappresensi::whereBetween('yfrekappresensis.date', [$startOfMonth, $endOfMonth])
            ->join('karyawans', 'karyawans.id_karyawan', '=', 'yfrekappresensis.user_id')
            ->whereNotNull('yfrekappresensis.user_id')
            ->selectRaw('
        yfrekappresensis.user_id,
        yfrekappresensis.date,
        SUM(yfrekappresensis.total_hari_kerja) as hari_kerja,
        SUM(yfrekappresensis.total_jam_kerja) as jam_kerja,
        SUM(yfrekappresensis.total_jam_lembur) as jam_lembur,
        SUM(yfrekappresensis.late) as jumlah_jam_terlambat,
        SUM(CASE WHEN yfrekappresensis.shift = "Malam" AND yfrekappresensis.total_jam_kerja >= 8 THEN 1 ELSE 0 END) as tambahan_jam_shift_malam,
        MAX(yfrekappresensis.date) as latest_date,
        karyawans.jabatan_id,
        karyawans.status_karyawan,
        karyawans.metode_penggajian,
        karyawans.placement_id,
        karyawans.nama,
        karyawans.company_id,
        karyawans.department_id,
        karyawans.nama_bank,
        karyawans.nomor_rekening,
        karyawans.gaji_pokok,
        karyawans.gaji_overtime,
        karyawans.gaji_bpjs,
        karyawans.potongan_JHT,
        karyawans.potongan_JP,
        karyawans.potongan_JKK,
        karyawans.potongan_JKM,
        karyawans.potongan_kesehatan,

        karyawans.tanggal_blacklist
    ')
            ->groupBy('yfrekappresensis.user_id', 'karyawans.jabatan_id', 'karyawans.status_karyawan', 'karyawans.metode_penggajian', 'karyawans.placement_id', 'karyawans.tanggal_blacklist')
            ->orderBy('yfrekappresensis.user_id')
            ->get();

        $payrollData = [];
        // dd("total_n_hari_kerja:", $total_n_hari_kerja);
        foreach ($presensiData as $data) {
            if ($data->status_karyawan != "Blacklist") {

                $subtotal = 0;
                if ($data->metode_penggajian == "Perjam") {
                    $subtotal = $data->jam_kerja * ($data->gaji_pokok / 198) + $data->jam_lembur * $data->gaji_overtime;
                } else {
                    $subtotal = $data->hari_kerja * ($data->gaji_pokok / $total_n_hari_kerja) + $data->jam_lembur * $data->gaji_overtime;
                }
                // dd($subtotal);


                $payrollData[] = [
                    'id_karyawan' => intval($data->user_id),
                    'hari_kerja' => $data->hari_kerja,
                    'jam_kerja' => $data->jam_kerja,
                    'jam_lembur' => $data->jam_lembur,
                    'jumlah_jam_terlambat' => $data->jumlah_jam_terlambat,
                    'tambahan_jam_shift_malam' => $data->tambahan_jam_shift_malam,
                    'date' => buatTanggal($data->date),

                    'created_at' => now(),
                    'updated_at' => now(),

                    // Data dari tabel Karyawan
                    'jabatan_id' => $data->jabatan_id,
                    'status_karyawan' => $data->status_karyawan,
                    'metode_penggajian' => $data->metode_penggajian,
                    'nama' => $data->nama,
                    'placement_id' => $data->placement_id,
                    'company_id' => $data->company_id,
                    'department_id' => $data->department_id,
                    'nama_bank' => $data->nama_bank,
                    'nomor_rekening' => $data->nomor_rekening,
                    'gaji_pokok' => $data->gaji_pokok,
                    'gaji_lembur' => $data->gaji_overtime,
                    'gaji_bpjs' => $data->gaji_bpjs,
                    'subtotal' => $subtotal,
                    'jkk' => $data->potongan_JKK,
                    'jkm' => $data->potongan_JKK,

                    // 'tanggal_blacklist' => $data->tanggal_blacklist
                ];
            }
        }

        // Batch insert untuk performa lebih cepat
        if (!empty($payrollData)) {
            Payroll::insert($payrollData);
        }

        Log::info('Proses payroll selesai.');
    }
    dd('Done');





    // if ($d->karyawan->status_karyawan != 'Blacklist') {
    //     $dataArr[] = [
    //         'user_id' => $data,
    //         'total_hari_kerja' => $total_hari_kerja,
    //         'jumlah_jam_kerja' => $total_jam_kerja,
    //         'jumlah_menit_lembur' => $total_jam_lembur,
    //         'jumlah_jam_terlambat' => $total_keterlambatan,
    //         'tambahan_jam_shift_malam' => $total_tambahan_shift_malam,
    //         'jam_kerja_libur' => $jam_kerja_libur,
    //         'total_noscan' => $n_noscan,
    //         'karyawan_id' => $d->karyawan->id,
    //         'date' => buatTanggal($d->date),
    //         'last_data_date' => $last_data_date->date,
    //         'created_at' => now()->toDateTimeString(),
    //         'updated_at' => now()->toDateTimeString(),
    //     ];
    // }










    // }

    // dd('sini');
    //     $chunks = array_chunk($dataArr, 100);
    //     foreach ($chunks as $chunk) {
    //         Jamkerjaid::insert($chunk);
    //     }
    // }

    // dd('first step done');
    // echo 'rekap done';

    // ok 2 perhitungan payroll
    $datas = Jamkerjaid::with('karyawan', 'yfrekappresensi')
        ->whereBetween('date', [Carbon::parse($year . '-' . $month . '-01'), Carbon::parse($year . '-' . $month . '-01')->endOfMonth()])
        ->get();

    if ($datas->isEmpty()) {
        return 0;
    }

    $subtotal = 0;
    $denda_noscan = 0;


    Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->delete();
    foreach ($datas as $data) {



        //   hitung BPJS

        if ($data->karyawan->potongan_JP == 1) {
            if ($data->karyawan->gaji_bpjs <= 10042300) {
                $jp = $data->karyawan->gaji_bpjs * 0.01;
            } else {
                $jp = 10042300 * 0.01;
            }
        } else {
            $jp = 0;
        }

        if ($data->karyawan->potongan_JHT == 1) {
            $jht = $data->karyawan->gaji_bpjs * 0.02;
        } else {
            $jht = 0;
        }

        if ($data->karyawan->potongan_kesehatan == 1) {
            $data_gaji_bpjs = 0;
            if ($data->karyawan->gaji_bpjs >= 12000000) $data_gaji_bpjs = 12000000;
            else $data_gaji_bpjs = $data->karyawan->gaji_bpjs;

            $kesehatan = $data_gaji_bpjs * 0.01;
        } else {
            $kesehatan = 0;
        }

        if ($data->karyawan->tanggungan >= 1) {
            $tanggungan = $data->karyawan->tanggungan * $data->karyawan->gaji_bpjs * 0.01;
        } else {
            $tanggungan = 0;
        }

        if ($data->karyawan->potongan_JKK == 1) {
            $jkk = 1;
        } else {
            $jkk = 0;
        }
        if ($data->karyawan->potongan_JKM == 1) {
            $jkm = 1;
        } else {
            $jkm = 0;
        }

        // end of bpjs

        $pajak = 0;

        // denda no scan

        if ($data->total_noscan > 3 && trim($data->karyawan->metode_penggajian) == 'Perjam') {
            $denda_noscan = ($data->total_noscan - 3) * ($data->karyawan->gaji_pokok / 198);
        } else {
            $denda_noscan = 0;
        }



        // denda lupa absen

        if ($data->total_noscan == null) {
            $denda_lupa_absen = 0;
        } else {
            if ($data->total_noscan <= 3) {
                $denda_lupa_absen = 0;
            } else {
                $denda_lupa_absen = ($data->total_noscan - 3) * ($data->karyawan->gaji_pokok / 198);
            }
        }


        // hapus ini jika sdh kelar
        // $denda_lupa_absen = 0;
        // $denda_noscan = 0;


        $total_bonus_dari_karyawan = 0;
        $total_potongan_dari_karyawan = 0;
        $gaji_libur = 0;

        $gaji_libur = ($data->jam_kerja_libur * ($data->karyawan->gaji_pokok / 198));

        $total_bonus_dari_karyawan = $data->karyawan->bonus + $data->karyawan->tunjangan_jabatan + $data->karyawan->tunjangan_bahasa + $data->karyawan->tunjangan_skill + $data->karyawan->tunjangan_lembur_sabtu + $data->karyawan->tunjangan_lama_kerja;
        $total_potongan_dari_karyawan = $data->karyawan->iuran_air + $data->karyawan->iuran_locker;
        $pajak = 0;
        $manfaat_libur = 0;
        // $beginning_date = new DateTime("$year-$month-01");
        $beginning_date = buat_tanggal($month, $year);
        // hehehe
        if ($data->karyawan->tanggal_bergabung >= $beginning_date  || $data->karyawan->status_karyawan == 'Resigned') {
            $manfaat_libur = manfaat_libur($month, $year, $libur, $data->user_id, $data->karyawan->tanggal_bergabung);
        } else {
            $manfaat_libur = $libur->count();
            $cx++;
        }




        // $status_resign = ($data->karyawan->status_karyawan == 'Resigned') && (check_resigned_validity($month, $year, $data->karyawan->tanggal_resigned));


        // if ($data->karyawan->status_karyawan == 'Resigned' && check_resigned_validity($month, $year, $data->karyawan->tanggal_resigned) && $data->karyawan->metode_penggajian == 'Perbulan' && $manfaat_libur > 0) {
        //     $manfaat_libur = manfaat_libur_resigned($month, $year, $libur, $data->user_id, $data->karyawan->tanggal_resigned);
        // }


        // if ($data->karyawan_id == '1026') {
        //     dd($manfaat_libur);
        // }

        //ggg

        // $total_n_hari_kerja = getTotalWorkingDays($year, $month);
        // $jumlah_libur_nasional = jumlah_libur_nasional($month, $year);
        // $max_hari_kerja = $total_n_hari_kerja - $jumlah_libur_nasional;
        // $gaji_potongan = $data->karyawan->gaji_pokok / 26;
        // $selisih_manfaat_libur = $jumlah_libur_nasional - $manfaat_libur;
        // $selisih_hari_kerja = $max_hari_kerja - $data->total_hari_kerja;
        // if ($selisih_hari_kerja < 0) $selisih_hari_kerja = 0;
        // if ($selisih_manfaat_libur < 0) $selisih_manfaat_libur = 0;

        // $gaji_karyawan_bulanan = $data->karyawan->gaji_pokok - ($gaji_potongan * ($selisih_manfaat_libur + $selisih_hari_kerja));
        // if ($data->user_id == 58) dd($selisih_manfaat_libur, $selisih_hari_kerja, $max_hari_kerja, $total_n_hari_kerja, $jumlah_libur_nasional, $manfaat_libur);
        // if ($data->total_hari_kerja >= 23) {
        //     $gaji_karyawan_bulanan = $data->karyawan->gaji_pokok - ($gaji_potongan * $selisih_manfaat_libur);
        // } else {
        //     $gaji_karyawan_bulanan = $data->karyawan->gaji_pokok - ($gaji_potongan * ($selisih_manfaat_libur + $selisih_hari_kerja));
        // }


        $gaji_karyawan_bulanan = ($data->karyawan->gaji_pokok / $total_n_hari_kerja) * ($data->total_hari_kerja + $manfaat_libur);




        if (trim($data->karyawan->metode_penggajian) == 'Perjam') {
            $subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
        } else {
            $subtotal = $gaji_karyawan_bulanan + $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
        }

        // if ($data->user_id == '1145') {
        //     dd($data->karyawan->gaji_pokok, $total_n_hari_kerja, $data->total_hari_kerja, $manfaat_libur, $data->jumlah_menit_lembur, $data->karyawan->gaji_overtime);
        // }

        $tambahan_shift_malam = $data->tambahan_jam_shift_malam * $data->karyawan->gaji_overtime;
        if ($data->karyawan->jabatan_id == 17) {
            $tambahan_shift_malam = $data->tambahan_jam_shift_malam * $data->karyawan->gaji_shift_malam_satpam;
        }

        $libur_nasional = 0;

        // $payrollArr[] = [
        //     'jp' => $jp,
        //     'jht' => $jht,
        //     'kesehatan' => $kesehatan,
        //     'tanggungan' => $tanggungan,
        //     'jkk' => $jkk,
        //     'jkm' => $jkm,
        //     'denda_lupa_absen' => $denda_lupa_absen,
        //     'gaji_libur' => $gaji_libur,

        //     'jamkerjaid_id' => $data->id,
        //     'nama' => $data->karyawan->nama,
        //     'id_karyawan' => $data->karyawan->id_karyawan,
        //     'jabatan' => $data->karyawan->jabatan_id,
        //     'company' => $data->karyawan->company,
        //     'placement' => $data->karyawan->placement,
        //     'departemen' => $data->karyawan->departemen,
        //     'status_karyawan' => $data->karyawan->status_karyawan,
        //     'metode_penggajian' => $data->karyawan->metode_penggajian,
        //     'nomor_rekening' => $data->karyawan->nomor_rekening,
        //     'nama_bank' => $data->karyawan->nama_bank,
        //     'gaji_pokok' => $data->karyawan->gaji_pokok,
        //     'gaji_lembur' => $data->karyawan->gaji_overtime,
        //     'gaji_bpjs' => $data->karyawan->gaji_bpjs,
        //     // oll
        //     'libur_nasional' => $libur_nasional,

        //     'jkk' => $data->karyawan->jkk,
        //     'jkm' => $data->karyawan->jkm,
        //     'hari_kerja' => $data->total_hari_kerja,
        //     'jam_kerja' => $data->jumlah_jam_kerja,
        //     'jam_lembur' => $data->jumlah_menit_lembur,
        //     'jumlah_jam_terlambat' => $data->jumlah_jam_terlambat,
        //     'total_noscan' => $data->total_noscan,
        //     'thr' => $data->karyawan->bonus,
        //     'tunjangan_jabatan' => $data->karyawan->tunjangan_jabatan,
        //     'tunjangan_bahasa' => $data->karyawan->tunjangan_bahasa,
        //     'tunjangan_skill' => $data->karyawan->tunjangan_skill,
        //     'tunjangan_lama_kerja' => $data->karyawan->tunjangan_lama_kerja,
        //     'tunjangan_lembur_sabtu' => $data->karyawan->tunjangan_lembur_sabtu,
        //     'iuran_air' => $data->karyawan->iuran_air,
        //     'iuran_locker' => $data->karyawan->iuran_locker,
        //     'tambahan_jam_shift_malam' => $data->tambahan_jam_shift_malam,
        //     'tambahan_shift_malam' => $tambahan_shift_malam,
        //     'subtotal' => $subtotal,
        //     'date' => buatTanggal($data->date),
        //     'total' => $subtotal + $gaji_libur + $total_bonus_dari_karyawan + $libur_nasional + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $tanggungan - $denda_lupa_absen,
        //     'created_at' => now()->toDateTimeString(),
        //     'updated_at' => now()->toDateTimeString(),
        // ];

        // total gaji lembur


        // hitung pph21
        // $pph21 = hitung_pph21($data->karyawan->gaji_bpjs, $data->karyawan->ptkp, $data->karyawan->potongan_JHT, $data->karyawan->potongan_JP, $data->karyawan->potongan_JKK, $data->karyawan->potongan_JKM, $data->karyawan->potongan_kesehatan);

        // oioi
        $total_gaji_lembur = $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
        $pph21 = hitung_pph21(
            $data->karyawan->gaji_bpjs,
            $data->karyawan->ptkp,
            $data->karyawan->potongan_JHT,
            $data->karyawan->potongan_JP,
            $data->karyawan->potongan_JKK,
            $data->karyawan->potongan_JKM,
            $data->karyawan->potongan_kesehatan,
            $total_gaji_lembur,
            $gaji_libur,
            0,
            $tambahan_shift_malam
        );
        //==================
        if ($data->karyawan->gaji_bpjs >= 12000000) {
            $gaji_bpjs_max = 12000000;
        } else {
            $gaji_bpjs_max = $data->karyawan->gaji_bpjs;
        }

        if (
            $data->karyawan->gaji_bpjs >= 10042300
        ) {
            $gaji_jp_max = 10042300;
        } else {
            $gaji_jp_max = $data->karyawan->gaji_bpjs;
        }
        if (
            $data->karyawan->potongan_kesehatan != 0
        ) {
            $kesehatan_company = ($gaji_bpjs_max * 4) / 100;
        } else {
            $kesehatan_company = 0;
        }

        if ($data->karyawan->potongan_JKK) {
            $jkk_company = ($data->karyawan->gaji_bpjs * 0.24) / 100;
        } else {
            $jkk_company = 0;
        }

        if ($data->karyawan->potongan_JKM) {
            $jkm_company = ($data->karyawan->gaji_bpjs * 0.3) / 100;
        } else {
            $jkm_company = 0;
        }

        // ====================
        $total_bpjs = $data->karyawan->gaji_bpjs +
            // $data->karyawan->ptkp +

            $jkk_company +
            $jkm_company +
            $kesehatan_company +
            $total_gaji_lembur +
            $gaji_libur +

            $tambahan_shift_malam;

        if ($data->karyawan->metode_penggajian == '') {
            dd('metode penggajian belum diisi', $data->karyawan->id_karyawan);
        }


        Payroll::create([
            'jp' => $jp,
            'jht' => $jht,
            'kesehatan' => $kesehatan,
            'tanggungan' => $tanggungan,
            'jkk' => $jkk,
            'jkm' => $jkm,
            'denda_lupa_absen' => $denda_lupa_absen,
            'gaji_libur' => $gaji_libur,

            'jamkerjaid_id' => $data->id,
            'nama' => $data->karyawan->nama,
            'id_karyawan' => $data->karyawan->id_karyawan,
            // 'jabatan' => nama_jabatan($data->karyawan->jabatan_id),
            // 'company' => nama_company($data->karyawan->company_id),
            // 'placement' => nama_placement($data->karyawan->placement_id),
            // 'departemen' => nama_department($data->karyawan->department_id),

            'jabatan_id' => $data->karyawan->jabatan_id,
            'company_id' => $data->karyawan->company_id,
            'placement_id' => $data->karyawan->placement_id,
            'department_id' => $data->karyawan->department_id,

            // 'departemen' => $data->karyawan->department->nama_department,
            'status_karyawan' => $data->karyawan->status_karyawan,
            'metode_penggajian' => $data->karyawan->metode_penggajian,
            'nomor_rekening' => $data->karyawan->nomor_rekening,
            'nama_bank' => $data->karyawan->nama_bank,
            'gaji_pokok' => $data->karyawan->gaji_pokok,
            'gaji_lembur' => $data->karyawan->gaji_overtime,
            'gaji_bpjs' => $data->karyawan->gaji_bpjs,
            'ptkp' => $data->karyawan->ptkp,
            // oll
            'libur_nasional' => $libur_nasional,

            // 'jkk' => $data->karyawan->jkk,
            // 'jkm' => $data->karyawan->jkm,
            'hari_kerja' => $data->total_hari_kerja,
            'jam_kerja' => $data->jumlah_jam_kerja,
            'jam_lembur' => $data->jumlah_menit_lembur,
            'jumlah_jam_terlambat' => $data->jumlah_jam_terlambat,
            'total_noscan' => $data->total_noscan,
            'thr' => $data->karyawan->bonus,
            'tunjangan_jabatan' => $data->karyawan->tunjangan_jabatan,
            'tunjangan_bahasa' => $data->karyawan->tunjangan_bahasa,
            'tunjangan_skill' => $data->karyawan->tunjangan_skill,
            'tunjangan_lama_kerja' => $data->karyawan->tunjangan_lama_kerja,
            'tunjangan_lembur_sabtu' => $data->karyawan->tunjangan_lembur_sabtu,
            'iuran_air' => $data->karyawan->iuran_air,
            'iuran_locker' => $data->karyawan->iuran_locker,
            'tambahan_jam_shift_malam' => $data->tambahan_jam_shift_malam,
            'tambahan_shift_malam' => $tambahan_shift_malam,
            'subtotal' => $subtotal,
            'date' => buatTanggal($data->date),
            'pph21' => $pph21,
            'total' => $subtotal + $gaji_libur + $total_bonus_dari_karyawan + $libur_nasional + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $tanggungan - $denda_lupa_absen - $pph21,
            'total_bpjs' => $total_bpjs,
            // 'created_at' => now()->toDateTimeString(),
            // 'updated_at' => now()->toDateTimeString()
        ]);
    }

    // $chunks = array_chunk($payrollArr, 100);
    // foreach ($chunks as $chunk) {
    //     Payroll::insert($chunk);
    // }

    // dd('payroll done');
    // ok 3
    // Bonus dan Potongan

    $bonus = 0;
    $potongaan = 0;
    $all_bonus = 0;
    $all_potongan = 0;
    $bonuspotongan = Bonuspotongan::whereMonth('tanggal', $month)
        ->whereYear('tanggal', $year)
        ->get();

    foreach ($bonuspotongan as $d) {
        $all_bonus = $d->uang_makan + $d->bonus_lain;
        $all_potongan = $d->baju_esd + $d->gelas + $d->sandal + $d->seragam + $d->sport_bra + $d->hijab_instan + $d->id_card_hilang + $d->masker_hijau + $d->potongan_lain;
        $id_payroll = Payroll::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('id_karyawan', $d->user_id)
            ->first();
        if ($id_payroll != null) {
            $payroll = Payroll::find($id_payroll->id);
            $payroll->bonus1x = $payroll->bonus1x + $all_bonus;
            $payroll->potongan1x = $payroll->potongan1x + $all_potongan;
            $payroll->total = $payroll->total + $all_bonus - $all_potongan;
            $payroll->save();
        }
    }

    // hitung ulang PPH21 utk karyawan bulanan yang ada bonus tambahan

    $karyawanWithBonus = Payroll::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->where('metode_penggajian', 'Perbulan')
        ->where('bonus1x', '>', 0)->get();

    foreach ($karyawanWithBonus as $kb) {

        $total_bpjs_company = 0;
        $total_bpjs_lama = $kb->total_bpjs;
        $total_bpjs_company = $total_bpjs_lama + $kb->bonus1x;

        $pph21_lama = $kb->pph21;
        $pph21simple = hitung_pph21_simple($total_bpjs_company, $kb->ptkp, $kb->gaji_bpjs);
        $total_lama = $kb->total;
        $kb->pph21 = $pph21simple;
        $kb->total = $total_lama + $pph21_lama - $pph21simple;
        $kb->total_bpjs = $total_bpjs_company;
        $kb->save();
        // if ($kb->id_karyawan == 101) {
        //     dd($pph21_lama - $pph21simple);
        // }
    }


    // ok 4
    // perhitungan untuk karyawan yg resign sebelum 3 bulan

    $data = Karyawan::where('tanggal_resigned', '!=', null)
        ->whereMonth('tanggal_resigned', $month)
        ->whereYear('tanggal_resigned', $year)
        ->get();

    foreach ($data as $d) {
        $lama_bekerja = lama_bekerja($d->tanggal_bergabung, $d->tanggal_resigned);
        if ($lama_bekerja <= 90) {
            $data_payrolls = Payroll::where('id_karyawan', $d->id_karyawan)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->first();

            // try {
            //     $data_payroll = Payroll::find($data_payrolls->id);
            // } catch (\Exception $e) {
            //     dd($e->getMessage(), $d->id_karyawan, $lama_bekerja);
            //     return $e->getMessage();
            // }

            if ($data_payrolls != null) {
                $data_payroll = Payroll::find($data_payrolls->id);
            } else {
                $data_payroll = null;
            }

            if ($data_payroll != null) {
                if (trim($data_payroll->metode_penggajian) == 'Perbulan') {
                    $data_payroll->denda_resigned = 3 * ($data_payroll->gaji_pokok / $total_n_hari_kerja);
                } else {
                    $data_payroll->denda_resigned = 24 * ($data_payroll->gaji_pokok / 198);
                }
                $data_payroll->total = $data_payroll->total - $data_payroll->denda_resigned;
                if ($data_payroll->total < 0) {
                    $data_payroll->total = 0;
                }
                $data_payroll->save();
            }
        }
    }

    // ok 5
    //  Zheng Guixin 1
    // Eddy Chan 2
    // Yang Xiwen 3
    // Rudy Chan 4
    // Yin kai 5
    // Li meilian 25
    // Wanto 6435
    // Chan Kai Wan 6


    $idArrTKA = [1, 3, 5, 25, 6];
    $idArrTionghoa = [4, 2, 6435]; // TKA hanya 3 orang
    $idKhusus = [4, 2, 6435, 1, 3, 5, 6, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 800, 900, 5576, 5693, 6566, 7511, 6576, 6577, 6578, 6579, 8127]; //TKA hanya 3 no didepan


    // foreach ($idKhusus as $id) {
    //     $data_id = Karyawan::where('id_karyawan', $id)->first();
    //     $data_karyawan = Karyawan::find($data_id->id);
    //     if ($data_karyawan->potongan_JP == 1) {
    //         if ($data_karyawan->gaji_bpjs <= 10042300) {
    //             $jp = $data_karyawan->gaji_bpjs * 0.01;
    //         } else {
    //             $jp = 10042300 * 0.01;
    //         }
    //     } else {
    //         $jp = 0;
    //     }

    //     if ($data_karyawan->potongan_JHT == 1) {
    //         $jht = $data_karyawan->gaji_bpjs * 0.02;
    //     } else {
    //         $jht = 0;
    //     }

    //     if ($data_karyawan->potongan_kesehatan == 1) {
    //         $data_gaji_bpjs = 0;
    //         if ($data_karyawan->gaji_bpjs >= 12000000) $data_gaji_bpjs = 12000000;
    //         else $data_gaji_bpjs = $data_karyawan->gaji_bpjs;
    //         $kesehatan = $data_gaji_bpjs * 0.01;
    //         $kesehatan_company = ($data_gaji_bpjs * 4) / 100;
    //     } else {
    //         $kesehatan = 0;
    //         $kesehatan_company = 0;
    //     }

    //     if ($data_karyawan->potongan_JKK == 1) {
    //         $jkk = 1;
    //     } else {
    //         $jkk = 0;
    //     }
    //     if ($data_karyawan->potongan_JKM == 1) {
    //         $jkm = 1;
    //     } else {
    //         $jkm = 0;
    //     }




    //     if ($data_karyawan->potongan_JKK) {
    //         $jkk_company = ($data_karyawan->gaji_bpjs * 0.24) / 100;
    //     } else {
    //         $jkk_company = 0;
    //     }

    //     if ($data_karyawan->potongan_JKM) {
    //         $jkm_company = ($data_karyawan->gaji_bpjs * 0.3) / 100;
    //     } else {
    //         $jkm_company = 0;
    //     }

    //     // hitung pph21
    //     $pph21 = hitung_pph21(
    //         $data_karyawan->gaji_bpjs,
    //         $data_karyawan->ptkp,
    //         $data_karyawan->potongan_JHT,
    //         $data_karyawan->potongan_JP,
    //         $data_karyawan->potongan_JKK,
    //         $data_karyawan->potongan_JKM,
    //         $data_karyawan->potongan_kesehatan,
    //         0,
    //         0,
    //         0,
    //         0
    //     );

    //     $total_bpjs = $data_karyawan->gaji_bpjs + $jkk_company + $jkm_company + $kesehatan_company;





    //     $is_exist = Payroll::where('id_karyawan', $id)->whereMonth('date', $month)
    //         ->whereYear('date', $year)->first();
    //     if ($is_exist) {
    //         $data = Payroll::find($is_exist->id);
    //         $data->jp = $jp;
    //         $data->jht = $jht;
    //         $data->kesehatan = $kesehatan;
    //         $data->nama = $data_karyawan->nama;
    //         $data->id_karyawan = $data_karyawan->id_karyawan;
    //         $data->jabatan = nama_jabatan($data_karyawan->jabatan_id);
    //         $data->company = nama_company($data_karyawan->company_id);
    //         $data->departemen = nama_department($data_karyawan->department_id);
    //         $data->placement = nama_placement($data_karyawan->placement_id);
    //         $data->status_karyawan = $data_karyawan->status_karyawan;
    //         $data->metode_penggajian = $data_karyawan->metode_penggajian;
    //         $data->nomor_rekening = $data_karyawan->nomor_rekening;
    //         $data->nama_bank = $data_karyawan->nama_bank;
    //         $data->gaji_pokok = $data_karyawan->gaji_pokok;
    //         $data->gaji_bpjs = $data_karyawan->gaji_bpjs;
    //         $data->ptkp = $data_karyawan->ptkp;
    //         $data->jkk = $jkk;
    //         $data->jkm = $jkm;
    //         $data->date = $year . '-' . $month . '-01';
    //         $data->pph21  = $pph21;
    //         $data->subtotal = $data_karyawan->gaji_pokok;
    //         $data->total = $data_karyawan->gaji_pokok - ($jp + $jht + $kesehatan) - $pph21;
    //         $data->total_bpjs = $total_bpjs;
    //         $data->save();
    //     } else {
    //         $data = new Payroll();
    //         $data->jp = $jp;
    //         $data->jht = $jht;
    //         $data->kesehatan = $kesehatan;
    //         $data->nama = $data_karyawan->nama;
    //         $data->id_karyawan = $data_karyawan->id_karyawan;
    //         $data->jabatan = nama_jabatan($data_karyawan->jabatan_id);

    //         $data->company = nama_company($data_karyawan->company_id);
    //         $data->departemen = nama_department($data_karyawan->department_id);

    //         $data->placement = nama_placement($data_karyawan->placement_id);
    //         $data->status_karyawan = $data_karyawan->status_karyawan;
    //         $data->metode_penggajian = $data_karyawan->metode_penggajian;
    //         $data->nomor_rekening = $data_karyawan->nomor_rekening;
    //         $data->nama_bank = $data_karyawan->nama_bank;
    //         $data->gaji_pokok = $data_karyawan->gaji_pokok;
    //         $data->gaji_bpjs = $data_karyawan->gaji_bpjs;
    //         $data->ptkp = $data_karyawan->ptkp;
    //         $data->jkk = $jkk;
    //         $data->jkm = $jkm;
    //         $data->date = $year . '-' . $month . '-01';
    //         $data->pph21  = $pph21;
    //         $data->subtotal = $data_karyawan->gaji_pokok;
    //         $data->total = $data_karyawan->gaji_pokok - ($jp + $jht + $kesehatan) - $pph21;
    //         $data->total_bpjs = $total_bpjs;

    //         $data->save();
    //     }
    // }





    // ok 6
    // Libur nasional dan resigned sebelum 3 bulan bekerja

    $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->sum('jumlah_hari_libur');

    $current_date = Jamkerjaid::orderBy('date', 'desc')->first();

    $lock = Lock::find(1);
    $lock->rebuild_done = 1;
    $lock->save();

    // $end = microtime(true);
    // $executionTime = $end - $start;
    // dd("Execution time: {$executionTime} seconds");

    return 1;
}
