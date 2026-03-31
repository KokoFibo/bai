<?php

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Karyawan;
use App\Models\Bonuspotongan;
use App\Models\Jamkerjaid;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
//ok 1

function build_payroll($month, $year)
{
    $libur = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)->whereYear('tanggal_mulai_hari_libur', $year)->orderBy('tanggal_mulai_hari_libur', 'asc')->get('tanggal_mulai_hari_libur');
    $total_n_hari_kerja = getTotalWorkingDays($year, $month);
    $startOfMonth = Carbon::parse($year . '-' . $month . '-01');
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    $cx = 0;
    $pass = true;

    $jumlah_libur_nasional = jumlah_libur_nasional($month, $year);

    // $jamKerjaKosong = Jamkerjaid::count();
    $adaPresensi = Yfrekappresensi::whereMonth('date', $month)
        ->whereYear('date', $year)
        ->count();
    // if ($jamKerjaKosong == null || $adaPresensi == null) {
    if ($adaPresensi == null) {
        return 0;
        clear_locks();
        // $dispatch('error', message: 'Data Presensi Masih Kosong');
    }

    // AMBIL DATA TERAKHIR DARI REKAP PRESENSI PADA BULAN YBS
    $last_data_date = Yfrekappresensi::query()
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date', 'desc')
        ->first();
    //     delete jamkerjaid yg akan di build
    if ($pass) {
        Jamkerjaid::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();
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

    // $filteredData = Jamkerjaid::with(['karyawan' => ['id_karyawan', 'jabatan', 'placement']])
    //     ->whereMonth('date', $month)
    //     ->whereYear('date', $year)
    //     ->get();

    // disini mulai prosesnya

    //     foreach ($filteredData as $data) {
    // proses ini yg lama1
    if ($pass) {
        foreach ($filterArray as $data) {
            // $dataId = Yfrekappresensi::with('karyawan:id,jabatan,status_karyawan,metode_penggajian')

            //     ->where('user_id', $data)
            //     ->whereBetween('date', [Carbon::parse($year . '-' . $month . '-01'), Carbon::parse($year . '-' . $month . '-01')->endOfMonth()])
            //     ->orderBy('date', 'desc')
            //     ->get();



            $dataId = Yfrekappresensi::with('karyawan:id,jabatan,status_karyawan,metode_penggajian,placement,tanggal_blacklist')
                ->where('user_id', $data)
                ->where('date', '>=', $startOfMonth)
                ->where('date', '<=', $endOfMonth)
                ->orderBy('date', 'desc')
                ->get();

            // ambil data per user id
            $n_noscan = 0;
            $total_hari_kerja = 0;
            $total_jam_kerja = 0;
            $total_jam_lembur = 0;
            $langsungLembur = 0;
            $tambahan_shift_malam = 0;
            $total_keterlambatan = 0;
            $total_tambahan_shift_malam = 0;
            $jam_kerja_libur = 0;
            //loop ini utk 1 user selama 22 hari
            $get_placement = get_placement($dataId[0]->user_id);
            foreach ($dataId as $d) {
                if ($d->no_scan === null) {
                    $jam_lembur = 0;
                    $tambahan_shift_malam = 0;
                    $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, $get_placement);
                    $terlambat = late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan, $get_placement);
                    $langsungLembur = langsungLembur($d->second_out, $d->date, $d->shift, $d->karyawan->jabatan, $get_placement);

                    if (is_sunday($d->date)) {
                        $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 * 2
                            + $langsungLembur * 2;
                    } else {
                        $jam_lembur = hitungLembur($d->overtime_in, $d->overtime_out) / 60 + $langsungLembur;
                    }

                    if ($d->shift == 'Malam') {
                        if (is_saturday($d->date)) {
                            if ($jam_kerja >= 6) {
                                $tambahan_shift_malam = 1;
                            }
                        } elseif (is_sunday($d->date)) {
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
                    if ($jam_lembur >= 9 && is_sunday($d->date) == false && $d->karyawan->jabatan != 'Driver') {
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
                        $jam_kerja = hitung_jam_kerja($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->late, $d->shift, $d->date, $d->karyawan->jabatan, $get_placement);
                    }

                    if ($d->karyawan->jabatan == 'Satpam' && is_saturday($d->date)) {
                        // $jam_lembur = 0;
                    }



                    // Jika hari libur nasional

                    if ($d->karyawan->jabatan != 'Translator') {
                        if (
                            is_libur_nasional($d->date) &&  !is_sunday($d->date)

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

                    // khusus placement YAM yev yev... tgl 2024-04-07 dan 2024-04-09  
                    // $rule1 = ($d->date == '2024-04-07' || $d->date == '2024-04-09') &&  (substr($d->karyawan->placement, 0, 3) == "YEV" || $d->karyawan->placement == 'YAM');
                    // if ($rule1) {
                    //     $jam_kerja /= 2;
                    //     $jam_lembur /= 2;
                    // }


                    $total_hari_kerja++;

                    if ((is_sunday($d->date) || is_libur_nasional($d->date)) && trim($d->karyawan->metode_penggajian) == 'Perbulan') {
                        $total_hari_kerja--;
                        $jam_kerja_libur += $jam_kerja;
                    }


                    $total_jam_kerja = $total_jam_kerja + $jam_kerja;
                    $total_jam_lembur = $total_jam_lembur + $jam_lembur;
                    $total_keterlambatan = $total_keterlambatan + $terlambat;
                    $total_tambahan_shift_malam = $total_tambahan_shift_malam + $tambahan_shift_malam;
                }
                if ($d->no_scan_history != null) {
                    $n_noscan = $n_noscan + 1;
                }
            }
            if ($n_noscan == 0) {
                $n_noscan = null;
            }




            // $blacklist_condition = $d->karyawan && $d->karyawan->status_karyawan != 'Blacklist'
            //     || Carbon::parse($d->karyawan->tanggal_blacklist)->month > Carbon::parse($d->date)->month;










            if ($d->karyawan->status_karyawan != 'Blacklist') {
                $dataArr[] = [
                    'user_id' => $data,
                    'total_hari_kerja' => $total_hari_kerja,
                    'jumlah_jam_kerja' => $total_jam_kerja,
                    'jumlah_menit_lembur' => $total_jam_lembur,
                    'jumlah_jam_terlambat' => $total_keterlambatan,
                    'tambahan_jam_shift_malam' => $total_tambahan_shift_malam,
                    'jam_kerja_libur' => $jam_kerja_libur,


                    'total_noscan' => $n_noscan,
                    'karyawan_id' => $d->karyawan->id,
                    'date' => buatTanggal($d->date),
                    'last_data_date' => $last_data_date->date,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
            // else if (Carbon::parse($d->karyawan->tanggal_blacklist)->month > Carbon::parse($d->date)->month) {
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
        }

        // dd('sini');
        $chunks = array_chunk($dataArr, 100);
        foreach ($chunks as $chunk) {
            Jamkerjaid::insert($chunk);
        }
    }


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
            $kesehatan = $data->karyawan->gaji_bpjs * 0.01;
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
        if ($data->karyawan->jabatan == 'Satpam') {
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
        //     'jabatan' => $data->karyawan->jabatan,
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
            'jabatan' => $data->karyawan->jabatan,
            'company' => $data->karyawan->company,
            'placement' => $data->karyawan->placement,
            'departemen' => $data->karyawan->departemen,
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

            'jkk' => $data->karyawan->jkk,
            'jkm' => $data->karyawan->jkm,
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
            'total' => $subtotal + $gaji_libur + $total_bonus_dari_karyawan + $libur_nasional + $tambahan_shift_malam - $total_potongan_dari_karyawan - $pajak - $jp - $jht - $kesehatan - $tanggungan - $denda_lupa_absen,
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
                    $data_payroll->denda_resigned = 3 * ($data_payroll->gaji_pokok / 26);
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
    $idKhusus = [4, 2, 6435, 1, 3, 5, 6, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 800, 900, 5576, 5693, 6566, 7511]; //TKA hanya 3 no didepan

    // foreach ($idArrTKA as $id) {
    //     $data_id = Karyawan::where('id_karyawan', $id)->first();
    //     $data_karyawan = Karyawan::find($data_id->id);
    //     //ook
    //     $is_exist = Payroll::where('id_karyawan', $id)
    //         ->whereMonth('date', $month)
    //         ->whereYear('date', $year)
    //         ->first();
    //     if ($is_exist) {
    //         dd($is_exist->id);
    //         $data = Payroll::find($is_exist->id);
    //         $data->nama = $data_karyawan->nama;
    //         $data->id_karyawan = $data_karyawan->id_karyawan;
    //         $data->jabatan = $data_karyawan->jabatan;
    //         $data->company = $data_karyawan->company;
    //         $data->placement = $data_karyawan->placement;
    //         $data->status_karyawan = $data_karyawan->status_karyawan;
    //         $data->metode_penggajian = $data_karyawan->metode_penggajian;
    //         $data->nomor_rekening = $data_karyawan->nomor_rekening;
    //         $data->nama_bank = $data_karyawan->nama_bank;
    //         $data->gaji_pokok = $data_karyawan->gaji_pokok;

    //         $data->date = $year . '-' . $month . '-01';
    //         $data->total = $data_karyawan->gaji_pokok;

    //         $data->save();
    //     } else {
    //         $data = new Payroll();
    //         $data->nama = $data_karyawan->nama;
    //         $data->id_karyawan = $data_karyawan->id_karyawan;
    //         $data->jabatan = $data_karyawan->jabatan;
    //         $data->company = $data_karyawan->company;
    //         $data->placement = $data_karyawan->placement;
    //         $data->status_karyawan = $data_karyawan->status_karyawan;
    //         $data->metode_penggajian = $data_karyawan->metode_penggajian;
    //         $data->nomor_rekening = $data_karyawan->nomor_rekening;
    //         $data->nama_bank = $data_karyawan->nama_bank;
    //         $data->gaji_pokok = $data_karyawan->gaji_pokok;

    //         $data->date = $year . '-' . $month . '-01';
    //         $data->total = $data_karyawan->gaji_pokok;
    //         $data->save();
    //     }
    // }
    // foreach ($idArrTionghoa as $id) {
    foreach ($idKhusus as $id) {
        $data_id = Karyawan::where('id_karyawan', $id)->first();
        $data_karyawan = Karyawan::find($data_id->id);
        if ($data_karyawan->potongan_JP == 1) {
            if ($data_karyawan->gaji_bpjs <= 10042300) {
                $jp = $data_karyawan->gaji_bpjs * 0.01;
            } else {
                $jp = 10042300 * 0.01;
            }
        } else {
            $jp = 0;
        }

        if ($data_karyawan->potongan_JHT == 1) {
            $jht = $data_karyawan->gaji_bpjs * 0.02;
        } else {
            $jht = 0;
        }
        if ($data_karyawan->potongan_kesehatan == 1) {
            $kesehatan = $data_karyawan->gaji_bpjs * 0.01;
        } else {
            $kesehatan = 0;
        }
        $is_exist = Payroll::where('id_karyawan', $id)->whereMonth('date', $month)
            ->whereYear('date', $year)->first();
        if ($is_exist) {
            $data = Payroll::find($is_exist->id);
            $data->jp = $jp;
            $data->jht = $jht;
            $data->kesehatan = $kesehatan;
            $data->nama = $data_karyawan->nama;
            $data->id_karyawan = $data_karyawan->id_karyawan;
            $data->jabatan = $data_karyawan->jabatan;
            $data->company = $data_karyawan->company;
            $data->placement = $data_karyawan->placement;
            $data->status_karyawan = $data_karyawan->status_karyawan;
            $data->metode_penggajian = $data_karyawan->metode_penggajian;
            $data->nomor_rekening = $data_karyawan->nomor_rekening;
            $data->nama_bank = $data_karyawan->nama_bank;
            $data->gaji_pokok = $data_karyawan->gaji_pokok;
            $data->gaji_bpjs = $data_karyawan->gaji_bpjs;
            $data->ptkp = $data_karyawan->ptkp;
            $data->jkk = $data_karyawan->jkk;
            $data->jkm = $data_karyawan->jkm;
            $data->date = $year . '-' . $month . '-01';
            $data->total = $data_karyawan->gaji_pokok - ($jp + $jht + $kesehatan);
            $data->save();
        } else {
            $data = new Payroll();
            $data->jp = $jp;
            $data->jht = $jht;
            $data->kesehatan = $kesehatan;
            $data->nama = $data_karyawan->nama;
            $data->id_karyawan = $data_karyawan->id_karyawan;
            $data->jabatan = $data_karyawan->jabatan;
            $data->company = $data_karyawan->company;
            $data->placement = $data_karyawan->placement;
            $data->status_karyawan = $data_karyawan->status_karyawan;
            $data->metode_penggajian = $data_karyawan->metode_penggajian;
            $data->nomor_rekening = $data_karyawan->nomor_rekening;
            $data->nama_bank = $data_karyawan->nama_bank;
            $data->gaji_pokok = $data_karyawan->gaji_pokok;
            $data->gaji_bpjs = $data_karyawan->gaji_bpjs;
            $data->ptkp = $data_karyawan->ptkp;
            $data->jkk = $data_karyawan->jkk;
            $data->jkm = $data_karyawan->jkm;
            $data->date = $year . '-' . $month . '-01';
            $data->total = $data_karyawan->gaji_pokok - ($jp + $jht + $kesehatan);
            $data->save();
        }
    }
    // ok 6
    // Libur nasional dan resigned sebelum 3 bulan kerja

    $jumlah_libur_nasional = Liburnasional::whereMonth('tanggal_mulai_hari_libur', $month)
        ->whereYear('tanggal_mulai_hari_libur', $year)
        ->sum('jumlah_hari_libur');

    $current_date = Jamkerjaid::orderBy('date', 'desc')->first();

    return 1;
}
