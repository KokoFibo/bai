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

// faster

function build_payroll($month, $year)
{
    $start = microtime(true);

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
        ->exists();

    if (!$adaPresensi) {
        clear_locks();
        return 0;
    }

    if ($pass) {
        Jamkerjaid::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();
        delete_failed_jobs();
    }

    $startOfMonth = Carbon::create($year, $month)->startOfMonth();
    $endOfMonth = Carbon::create($year, $month)->endOfMonth();

    $filterArray = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->pluck('user_id')
        ->unique();

    if ($filterArray->isEmpty()) {
        return 0;
    }

    $dataArr = [];
    $lastDataDate = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->value('date');

    $userPresensiData = Yfrekappresensi::with('karyawan:id,jabatan_id,status_karyawan,metode_penggajian,placement_id,tanggal_blacklist')
        ->whereIn('user_id', $filterArray)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('user_id')
        ->orderBy('date')
        ->get()
        ->groupBy('user_id');

    foreach ($userPresensiData as $userId => $presensiData) {
        $totalHariKerja = 0;
        $totalJamKerja = 0;
        $totalJamLembur = 0;
        $totalKeterlambatan = 0;
        $totalTambahanShiftMalam = 0;
        $jamKerjaLibur = 0;
        $nNoScan = 0;

        $karyawan = $presensiData->first()->karyawan;
        $getPlacement = get_placement($userId);

        foreach ($presensiData as $data) {
            if ($data->no_scan === null) {
                $jamKerja = hitung_jam_kerja($data->first_in, $data->first_out, $data->second_in, $data->second_out, $data->late, $data->shift, $data->date, $karyawan->jabatan_id, $getPlacement);
                $terlambat = late_check_jam_kerja_only($data->first_in, $data->first_out, $data->second_in, $data->second_out, $data->shift, $data->date, $karyawan->jabatan_id, $getPlacement);
                $langsungLembur = langsungLembur($data->second_out, $data->date, $data->shift, $karyawan->jabatan_id, $getPlacement);

                $jamLembur = is_sunday($data->date) ? (hitungLembur($data->overtime_in, $data->overtime_out) / 60 * 2) + ($langsungLembur * 2) : hitungLembur($data->overtime_in, $data->overtime_out) / 60 + $langsungLembur;

                // if ($data->shift == 'Malam' && $jamKerja >= ($data->is_weekend ? 6 : 8)) {
                //     $totalTambahanShiftMalam++;
                // }

                if ($data->shift == 'Malam') {
                    if (is_saturday($data->date)) {
                        if ($jamKerja >= 6) {
                            $totalTambahanShiftMalam++;
                        }
                    } elseif (is_sunday($data->date)) {
                        if ($jamKerja >= 16) {
                            // $jam_lembur = $jam_lembur + 2;
                            $totalTambahanShiftMalam++;
                        }
                    } else {
                        if ($jamKerja >= 8) {
                            // $jam_lembur = $jam_lembur + 1;
                            $totalTambahanShiftMalam++;
                        }
                    }
                }

                if (is_sunday($data->date) || is_libur_nasional($data->date)) {
                    $jamKerjaLibur += $jamKerja;
                } else {
                    $totalHariKerja++;
                }

                $totalJamKerja += $jamKerja;
                $totalJamLembur += $jamLembur;
                $totalKeterlambatan += $terlambat;
            }

            if ($data->no_scan_history !== null) {
                $nNoScan++;
            }
        }

        $dataArr[] = [
            'user_id' => $userId,
            'total_hari_kerja' => $totalHariKerja,
            'jumlah_jam_kerja' => $totalJamKerja,
            'jumlah_menit_lembur' => $totalJamLembur,
            'jumlah_jam_terlambat' => $totalKeterlambatan,
            'tambahan_jam_shift_malam' => $totalTambahanShiftMalam,
            'jam_kerja_libur' => $jamKerjaLibur,
            'total_noscan' => $nNoScan,
            'karyawan_id' => $karyawan->id,
            'date' => buatTanggal($lastDataDate),
            'last_data_date' => $lastDataDate,

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    collect($dataArr)->chunk(100)->each(function ($chunk) {
        Jamkerjaid::insert($chunk->toArray());
    });





    // $end = microtime(true);
    // $executionTime = $end - $start;
    // dd("Execution time: {$executionTime} seconds");


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




        $gaji_karyawan_bulanan = ($data->karyawan->gaji_pokok / $total_n_hari_kerja) * ($data->total_hari_kerja + $manfaat_libur);




        if (trim($data->karyawan->metode_penggajian) == 'Perjam') {
            $subtotal = $data->jumlah_jam_kerja * ($data->karyawan->gaji_pokok / 198) + $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
        } else {
            $subtotal = $gaji_karyawan_bulanan + $data->jumlah_menit_lembur * $data->karyawan->gaji_overtime;
        }


        $tambahan_shift_malam = $data->tambahan_jam_shift_malam * $data->karyawan->gaji_overtime;
        if ($data->karyawan->jabatan_id == 17) {
            $tambahan_shift_malam = $data->tambahan_jam_shift_malam * $data->karyawan->gaji_shift_malam_satpam;
        }

        $libur_nasional = 0;

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

        ]);
    }

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

    // ok 6
    // Libur nasional dan resigned sebelum 3 bulan bekerja

    $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->sum('jumlah_hari_libur');

    $current_date = Jamkerjaid::orderBy('date', 'desc')->first();

    $lock = Lock::find(1);
    $lock->rebuild_done = 1;
    $lock->save();

    $end = microtime(true);
    $executionTime = $end - $start;
    // dd("Execution time: {$executionTime} seconds");

    return 1;
}
