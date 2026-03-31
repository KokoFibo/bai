<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;
use App\Models\Yfrekappresensi;
use App\Models\Karyawan;
use App\Models\Liburnasional;




function quickRebuildOptimized(int $month, int $year)
{
    DB::transaction(function () use ($month, $year) {

        $payrollDate = Carbon::create($year, $month, 1)->format('Y-m-d');

        /**
         * =================================================
         * 1. HAPUS PAYROLL BULAN & TAHUN TERKAIT
         * =================================================
         */
        DB::table('payrolls')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->delete();

        /**
         * =================================================
         * 2. DATA PENUNJANG
         * =================================================
         */
        $total_n_hari_kerja = getTotalWorkingDays($year, $month);
        $jumlah_libur_nasional = jumlah_libur_nasional($month, $year);

        /**
         * =================================================
         * 3. AGREGASI PRESENSI (+ NOSCAN + LATE + SHIFT MALAM)
         * =================================================
         */
        $presensi = DB::table('yfrekappresensis as y')
            ->selectRaw("
y.user_id,
SUM(y.total_jam_kerja) AS total_jam_kerja,
SUM(y.total_hari_kerja) AS total_hari_kerja,
SUM(y.total_jam_lembur) AS total_jam_lembur,
SUM(y.total_jam_kerja_libur) AS total_jam_kerja_libur,
SUM(y.total_hari_kerja_libur) AS total_hari_kerja_libur,
SUM(y.total_jam_lembur_libur) AS total_jam_lembur_libur,
SUM(
CASE
WHEN y.no_scan_history = 'No Scan' THEN 1
ELSE 0
END
) AS total_noscan,
SUM(y.late) AS jumlah_jam_terlambat,
SUM(y.shift_malam) AS tambahan_jam_shift_malam
")
            ->whereMonth('y.date', $month)
            ->whereYear('y.date', $year)
            ->groupBy('y.user_id');

        /**
         * =================================================
         * 4. JOIN + HITUNG SUBTOTAL (TIDAK DIUBAH)
         * =================================================
         */
        $rows = DB::table('karyawans as k')
            ->joinSub($presensi, 'p', fn($j) => $j->on('p.user_id', '=', 'k.id_karyawan'))
            ->leftJoin('liburnasionals as l', function ($join) use ($month, $year) {
                $join->whereMonth('l.tanggal_mulai_hari_libur', $month)
                    ->whereYear('l.tanggal_mulai_hari_libur', $year);
            })
            ->where('k.status_karyawan', '!=', 'Blacklist')
            ->groupBy('k.id_karyawan')
            ->selectRaw("
k.*,

p.total_jam_kerja,
p.total_hari_kerja,
p.total_jam_lembur,
p.total_jam_kerja_libur,
p.total_hari_kerja_libur,
p.total_jam_lembur_libur,
p.total_noscan,
p.jumlah_jam_terlambat,
p.tambahan_jam_shift_malam,

-- HITUNG JUMLAH LIBUR NASIONAL VALID PER KARYAWAN
COALESCE(SUM(
CASE
WHEN k.metode_penggajian != 'Perbulan' THEN 0
WHEN k.tanggal_bergabung > l.tanggal_mulai_hari_libur THEN 0
ELSE l.jumlah_hari_libur
END
), 0) AS jumlah_libur_nasional_valid,

CASE
WHEN k.etnis = 'China' THEN k.gaji_pokok

WHEN k.metode_penggajian = 'Perjam' THEN
(p.total_jam_kerja * (k.gaji_pokok / 198))
+ (p.total_jam_lembur * k.gaji_overtime)
+ (p.total_jam_lembur_libur * k.gaji_overtime)
+ (p.total_jam_kerja_libur * (k.gaji_pokok / 198))

ELSE
(k.gaji_pokok / ?)
* (p.total_hari_kerja +
COALESCE(SUM(
CASE
WHEN k.tanggal_bergabung > l.tanggal_mulai_hari_libur THEN 0
ELSE l.jumlah_hari_libur
END
), 0)
)
+ (p.total_jam_lembur * k.gaji_overtime)
+ (p.total_jam_lembur_libur * k.gaji_overtime)
+ (p.total_jam_kerja_libur * (k.gaji_pokok / 198))
END AS subtotal
", [$total_n_hari_kerja])
            ->get();


        /**
         * =================================================
         * 5. BUILD PAYROLL (HANYA TAMBAH FIELD BARU)
         * =================================================
         */
        $insert = [];

        foreach ($rows as $k) {

            $tambahan_shift_malam =
                $k->tambahan_jam_shift_malam * $k->gaji_overtime;

            if ((int) $k->jabatan_id === 17) {
                $tambahan_shift_malam =
                    $k->tambahan_jam_shift_malam * $k->gaji_shift_malam_satpam;
            }

            $lemburan = 0;
            $lemburan = ($k->total_jam_lembur + $k->total_jam_lembur_libur) * $k->gaji_overtime;



            $gl = round($k->total_jam_kerja_libur * ($k->gaji_pokok / 198), 1);

            $gbi = $k->subtotal - $lemburan - $gl;

            if ($k->etnis == 'China') {
                $gbi = $k->gaji_pokok;
                $k->total_hari_kerja = (float) $total_n_hari_kerja - $jumlah_libur_nasional;
                $gl = 0;
            }

            $insert[] = [
                'jamkerjaid_id' => 0,
                'id_karyawan' => $k->id_karyawan,
                'nama' => $k->nama,

                'company_id' => $k->company_id,
                'placement_id' => $k->placement_id,
                'department_id' => $k->department_id,
                'jabatan_id' => $k->jabatan_id,

                'nama_bank' => $k->nama_bank,
                'nomor_rekening' => $k->nomor_rekening,
                'metode_penggajian' => $k->metode_penggajian,

                'hari_kerja' => (float) $k->total_hari_kerja,
                'jam_kerja' => (float) $k->total_jam_kerja,
                'jam_lembur' => (float) $k->total_jam_lembur,

                'hari_kerja_libur' => (float) $k->total_hari_kerja_libur,
                'jam_kerja_libur' => (float) $k->total_jam_kerja_libur,
                'jam_lembur_libur' => (float) $k->total_jam_lembur_libur,

                'libur_nasional' => $jumlah_libur_nasional,

                'jumlah_jam_terlambat' => (float) ($k->jumlah_jam_terlambat ?? 0),
                'tambahan_jam_shift_malam' => (float) ($k->tambahan_jam_shift_malam ?? 0),
                'tambahan_shift_malam' => round($tambahan_shift_malam, 0),

                'iuran_air' => (int) $k->iuran_air,
                'iuran_locker' => (int) $k->iuran_locker,

                'gaji_pokok' => (int) $k->gaji_pokok,
                'gaji_lembur' => (int) $k->gaji_overtime,
                // 'gaji_libur' => round($k->total_jam_kerja_libur * ($k->gaji_pokok / 198), 1),
                'gaji_libur' => $gl,
                'gaji_bpjs' =>  $k->gaji_bpjs,
                'gaji_bulan_ini' =>  $gbi,


                'tunjangan_jabatan' => (int) $k->tunjangan_jabatan,
                'tunjangan_bahasa' => (int) $k->tunjangan_bahasa,
                'tunjangan_skill' => (int) $k->tunjangan_skill,
                'subtotal' => round($k->subtotal, 1),
                'total' => round($k->subtotal + $tambahan_shift_malam - $k->iuran_air - $k->iuran_locker, 1),

                'total_noscan' => (int) ($k->total_noscan ?? 0),

                'status_karyawan' => $k->status_karyawan,
                'tanggungan' => $k->tanggungan ?? 0,
                'ptkp' => $k->ptkp ?? null,

                'pph21' => 0,
                'total_bpjs' => 0,
                'pajak' => 0,

                'denda_lupa_absen' => 0,
                'denda_resigned' => 0,

                'date' => $payrollDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        /**
         * =================================================
         * 6. INSERT PAYROLL
         * =================================================
         */
        collect($insert)
            ->chunk(300)
            ->each(fn($c) => DB::table('payrolls')->insert($c->toArray()));

        /**
         * =================================================
         * 6A. INSERT CHINA TANPA PRESENSI
         * =================================================
         */
        $chinaTanpaPresensi = DB::table('karyawans as k')
            ->where('k.etnis', 'China')
            ->where('k.status_karyawan', '!=', 'Blacklist')
            ->where(function ($q) use ($month, $year) {
                $q->where('k.status_karyawan', '!=', 'Resigned')
                    ->orWhereNot(function ($q2) use ($month, $year) {
                        $q2->where('k.status_karyawan', 'Resigned')
                            ->whereDate(
                                'k.tanggal_resigned',
                                '<',
                                Carbon::create($year, $month, 1)->startOfMonth()
                            );
                    });
            })
            ->whereNotIn('k.id_karyawan', function ($q) use ($month, $year) {
                $q->select('user_id')
                    ->from('yfrekappresensis')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year);
            })
            ->get();

        $insertChina = [];

        foreach ($chinaTanpaPresensi as $k) {
            $insertChina[] = [
                'jamkerjaid_id' => 0,
                'id_karyawan' => $k->id_karyawan,
                'nama' => $k->nama,

                'company_id' => $k->company_id,
                'placement_id' => $k->placement_id,
                'department_id' => $k->department_id,
                'jabatan_id' => $k->jabatan_id,

                'nama_bank' => $k->nama_bank,
                'nomor_rekening' => $k->nomor_rekening,
                'metode_penggajian' => $k->metode_penggajian,

                'hari_kerja' => (float) $total_n_hari_kerja - $jumlah_libur_nasional,
                'jam_kerja' => 0,
                'jam_lembur' => 0,

                'hari_kerja_libur' => 0,
                'jam_kerja_libur' => 0,
                'jam_lembur_libur' => 0,

                'libur_nasional' => $jumlah_libur_nasional,

                'jumlah_jam_terlambat' => 0,
                'tambahan_jam_shift_malam' => 0,
                'tambahan_shift_malam' => 0,

                'iuran_air' => (int) $k->iuran_air,
                'iuran_locker' => (int) $k->iuran_locker,

                'gaji_pokok' => (int) $k->gaji_pokok,
                'gaji_lembur' => (int) $k->gaji_overtime,
                'gaji_libur' => 0,
                'gaji_bpjs' =>  $k->gaji_bpjs,
                'gaji_bulan_ini' =>  $k->gaji_pokok,


                'subtotal' => (int) $k->gaji_pokok,
                'total' => (int) ($k->gaji_pokok - $k->iuran_air - $k->iuran_locker),

                'total_noscan' => 0,

                'status_karyawan' => $k->status_karyawan,
                'tanggungan' => $k->tanggungan ?? 0,
                'ptkp' => $k->ptkp ?? null,

                'pph21' => 0,
                'total_bpjs' => 0,
                'pajak' => 0,

                'denda_lupa_absen' => 0,
                'denda_resigned' => 0,

                'date' => $payrollDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        collect($insertChina)
            ->chunk(300)
            ->each(fn($c) => DB::table('payrolls')->insert($c->toArray()));



        /**
         * =================================================
         * 7. HITUNG DENDA NOSCAN & RESIGNED (TIDAK DIUBAH)
         * =================================================
         */
        DB::update("
UPDATE payrolls p
JOIN karyawans k ON k.id_karyawan = p.id_karyawan
SET
p.denda_lupa_absen =
CASE
WHEN k.etnis = 'China' THEN 0
WHEN p.total_noscan <= 3 THEN 0
    ELSE (p.total_noscan - 3) * (p.gaji_pokok / 198)
    END,

    p.denda_resigned=CASE
    WHEN k.tanggal_resigned IS NULL THEN 0
    WHEN DATEDIFF(k.tanggal_resigned, k.tanggal_bergabung)> 90 THEN 0
    WHEN TRIM(p.metode_penggajian) = 'Perbulan'
    THEN 3 * (p.gaji_pokok / ?)
    ELSE
    24 * (p.gaji_pokok / 198)
    END,

    p.total =
    p.total
    - (
    CASE
    WHEN k.etnis = 'China' THEN 0
    WHEN p.total_noscan <= 3 THEN 0
        ELSE (p.total_noscan - 3) * (p.gaji_pokok / 198)
        END
        +
        CASE
        WHEN k.tanggal_resigned IS NULL THEN 0
        WHEN DATEDIFF(k.tanggal_resigned, k.tanggal_bergabung)> 90 THEN 0
        WHEN TRIM(p.metode_penggajian) = 'Perbulan'
        THEN 3 * (p.gaji_pokok / ?)
        ELSE
        24 * (p.gaji_pokok / 198)
        END
        )
        WHERE
        MONTH(p.date) = ?
        AND YEAR(p.date) = ?
        ", [
            $total_n_hari_kerja,
            $total_n_hari_kerja,
            $month,
            $year
        ]);

        /**
         * =================================================
         * 8. BONUS & POTONGAN 1X (BONUSPOTONGANS)
         * =================================================
         */
        DB::statement("
        UPDATE payrolls p
        JOIN bonuspotongans b ON b.user_id = p.id_karyawan
        SET
        p.bonus1x =
        IFNULL(b.uang_makan, 0)
        + IFNULL(b.bonus_lain, 0),

        p.thr = IFNULL(b.thr, 0),

        p.potongan1x =
        IFNULL(b.baju_esd, 0)
        + IFNULL(b.gelas, 0)
        + IFNULL(b.sandal, 0)
        + IFNULL(b.seragam, 0)
        + IFNULL(b.sport_bra, 0)
        + IFNULL(b.hijab_instan, 0)
        + IFNULL(b.id_card_hilang, 0)
        + IFNULL(b.masker_hijau, 0)
        + IFNULL(b.potongan_lain, 0),

        p.total =
        p.total
        + IFNULL(b.uang_makan, 0)
        + IFNULL(b.bonus_lain, 0)
        + IFNULL(b.thr, 0)
        - (
        IFNULL(b.baju_esd, 0)
        + IFNULL(b.gelas, 0)
        + IFNULL(b.sandal, 0)
        + IFNULL(b.seragam, 0)
        + IFNULL(b.sport_bra, 0)
        + IFNULL(b.hijab_instan, 0)
        + IFNULL(b.id_card_hilang, 0)
        + IFNULL(b.masker_hijau, 0)
        + IFNULL(b.potongan_lain, 0)
        )

        WHERE
        MONTH(p.date) = ?
        AND YEAR(p.date) = ?
        AND MONTH(b.tanggal) = ?
        AND YEAR(b.tanggal) = ?
        ", [$month, $year, $month, $year]);


        /**
         * =================================================
         * 9. HITUNG BPJS & PPH21
         * =================================================
         */
        $payrolls = DB::table('payrolls as p')
            ->join('karyawans as k', 'k.id_karyawan', '=', 'p.id_karyawan')
            ->whereMonth('p.date', $month)
            ->whereYear('p.date', $year)
            ->select(
                'p.*',
                'k.gaji_bpjs',
                'k.potongan_JP',
                'k.potongan_JHT',
                'k.potongan_kesehatan',
                'k.potongan_JKK',
                'k.potongan_JKM',
                'k.tunjangan_jabatan',
                'k.tunjangan_bahasa',
                // 'k.tunjangan_housing',
                'k.company_id'
            )
            ->get();

        foreach ($payrolls as $p) {

            // China bebas PPH21 & BPJS
            // if ($p->etnis === 'China') {
            //     continue;
            // }

            $data = (object)[
                'karyawan' => $p
            ];



            $total_gaji_lembur1 = $p->gaji_lembur * ($p->jam_lembur + $p->jam_lembur_libur);
            $hasil = hitungBPJSdanPPH21(
                $data,
                $p->gaji_bulan_ini,
                $total_gaji_lembur1 ?? 0,
                $p->gaji_libur ?? 0,
                $p->tambahan_shift_malam ?? 0,
                $p->bonus1x ?? 0,
                $p->thr ?? 0,
            );



            if ($p->tanggungan >= 1) {
                $tanggungan = $p->tanggungan * $p->gaji_bpjs * 0.01;
            } else {
                $tanggungan = 0;
            }
            $other_deduction = $p->potongan1x + $p->denda_lupa_absen + $p->denda_resigned + $tanggungan + $p->iuran_air + $p->iuran_locker;

            // if ($p->tanggungan > 10) {
            //     dd($p->id_karyawan, $other_deduction, $p->tanggungan);
            // }
            $prf = $hasil['prf_salary'] - $other_deduction - $hasil['bpjs_employee'] - $hasil['pph21'];
            if ($prf < 0) $prf = 0;

            $core_cash = $p->gaji_bulan_ini - $hasil['gaji_bpjs_adjust'];
            $total_bpjs = 0;
            $total_bpjs = $hasil['total_bpjs'] + $p->bonus1x + $p->thr - $p->potongan1x;
            DB::table('payrolls')
                ->where('id_karyawan', $p->id_karyawan)
                ->where('date', $p->date)
                ->update([
                    'jp' => $hasil['jp'],
                    'jht' => $hasil['jht'],
                    'kesehatan' => $hasil['kesehatan'],
                    'tanggungan' => $hasil['tanggungan'],
                    'jkk' => $hasil['jkk'],
                    'jkm' => $hasil['jkm'],

                    'bpjs_employee' => $hasil['bpjs_employee'],
                    // 'jkk_company' => $hasil['jkk_company'],
                    // 'jkm_company' => $hasil['jkm_company'],

                    'bpjs_adjustment' => $hasil['gaji_bpjs_adjust'],
                    'prf_salary' => $hasil['prf_salary'],
                    'other_deduction' => $other_deduction,
                    'prf' => $prf,
                    'core_cash' => $core_cash,


                    'pph21' => $hasil['pph21'],
                    // 'total_bpjs' => $hasil['total_bpjs'],
                    'total_bpjs' => $total_bpjs,
                    // 'pajak' => $hasil['pph21'],
                    'total' => DB::raw("total - {$hasil['pph21']} - {$hasil['bpjs_employee']} - {$hasil['tanggungan']}"),
                    // 'total' => DB::raw("total - {$hasil['pph21']}"),
                    'updated_at' => now(),
                ]);
        }
    });
}




function hitungBPJSdanPPH21(
    object $data,
    float $gaji_bulan_ini,
    float $total_gaji_lembur,
    float $gaji_libur,
    float $tambahan_shift_malam,
    float $bonus1x,
    float $thr
): array {


    $k = $data->karyawan;

    /**
     * =========================
     * POTONGAN KARYAWAN
     * =========================
     */

    // JP
    if ($k->potongan_JP == 1) {
        $jp = ($k->gaji_bpjs <= 11086300)
            ? $k->gaji_bpjs * 0.01
            : 11086300 * 0.01;
    } else {
        $jp = 0;
    }

    // JHT
    $jht = ($k->potongan_JHT == 1)
        ? $k->gaji_bpjs * 0.02
        : 0;

    // BPJS Kesehatan (karyawan)
    if ($k->potongan_kesehatan == 1) {
        $kesehatan = min($k->gaji_bpjs, 12000000) * 0.01;
    } else {
        $kesehatan = 0;
    }

    // Tanggungan
    $tanggungan = ($k->tanggungan >= 1)
        ? $k->tanggungan * $k->gaji_bpjs * 0.01
        : 0;

    /**
     * =========================
     * IURAN COMPANY
     * =========================
     */

    $gaji_bpjs_max = min($k->gaji_bpjs, 12000000);
    $gaji_jp_max   = min($k->gaji_bpjs, 11086300);

    $kesehatan_company = ($k->potongan_kesehatan != 0)
        ? ($gaji_bpjs_max * 4) / 100
        : 0;

    if ($k->potongan_JKK) {
        $jkk_company = ($k->gaji_bpjs * 0.89) / 100;
    } else {
        $jkk_company = 0;
    }

    $jkm_company = ($k->potongan_JKM)
        ? ($k->gaji_bpjs * 0.3) / 100
        : 0;

    /**
     * =========================
     * ADJUST GAJI BPJS
     * =========================
     */

    if ($k->gaji_pokok != 0) {
        $gaji_bpjs_adjust =
            (
                $k->gaji_bpjs
                + $k->tunjangan_jabatan
                + $k->tunjangan_bahasa
                // + $k->tunjangan_housing
            ) * $gaji_bulan_ini / $k->gaji_pokok;
    } else {
        $gaji_bpjs_adjust = 0;
    }
    // if ($k->id_karyawan == 110) {
    //     dd(
    //         $k->id_karyawan,
    //         $gaji_bpjs_adjust,
    //         $k->gaji_bpjs,
    //         $k->tunjangan_jabatan,
    //         $k->tunjangan_bahasa,
    //         $k->tunjangan_housing,
    //         $gaji_bulan_ini,
    //         $k->gaji_pokok
    //     );
    // }


    /**
     * =========================
     * HITUNG PPH21
     * =========================
     */

    $total_tax =
        $gaji_bpjs_adjust
        + $jkk_company
        + $jkm_company
        + $kesehatan_company
        + $total_gaji_lembur
        + $gaji_libur
        + $tambahan_shift_malam;

    $tg =
        $gaji_bpjs_adjust
        + $jkk_company + $jkm_company + $kesehatan_company +
        $total_gaji_lembur +
        $gaji_libur +
        $bonus1x +
        $thr +
        $tambahan_shift_malam;





    $rate_pph21 = get_rate_ter_pph21(
        $k->ptkp,
        // $total_tax
        $tg

    );

    $pph21 = round(($tg * $rate_pph21) / 100, 2);

    $total_bpjs =  $total_tax;
    $bpjs_employee = $jht + $jp + $kesehatan;
    $prf_salary = $tambahan_shift_malam + $total_gaji_lembur + $gaji_libur +  $bonus1x + $thr + $gaji_bpjs_adjust;

    /**
     * =========================
     * RETURN SIAP UPDATE
     * =========================
     */
    return [
        'jp'         => round($jp, 2),
        'jht'        => round($jht, 2),
        'kesehatan'  => round($kesehatan, 2),
        'tanggungan' => round($tanggungan, 2),

        'jkk'        => $k->potongan_JKK,
        'jkm'        => $k->potongan_JKM,

        'kesehatan_company' => round($kesehatan_company, 2),
        'jkk_company'       => round($jkk_company, 2),
        'jkm_company'       => round($jkm_company, 2),

        'prf_salary'       => round($prf_salary, 2),



        'bpjs_employee'       =>  $bpjs_employee,
        'gaji_bpjs_adjust'  => round($gaji_bpjs_adjust, 2),
        // 'bpjs_adjustment'  => round($gaji_bpjs_adjust, 2),
        'pph21'             => $pph21,

        'total_bpjs' => $total_bpjs,
    ];
}
