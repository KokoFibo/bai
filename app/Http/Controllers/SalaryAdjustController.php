<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Jobgrade;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Bonuspotongan;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SalaryAdjustController extends Controller
{

    function extractTanggal($string)
    {
        // Cari pola tanggal dengan format dd-mm-yyyy
        // preg_match('/\d{2}-\d{2}-\d{4}/', $string, $match);
        preg_match('/\d{1,2}-\d{1,2}-\d{4}/', $string, $match);

        if (!empty($match)) {
            $tanggal = $match[0];

            // Buat objek DateTime dari format d-m-Y
            $date = DateTime::createFromFormat('d-m-Y', $tanggal);

            // Pastikan parsing berhasil
            if ($date) {
                return $date->format('Y-m-d');
            }
        }

        // Jika tidak ditemukan atau gagal parsing
        return null;
    }


    public function import1(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Validasi header baris ke-4 (index 3)
        $expectedHeader = ['ID Employee', 'Nama', 'Departemen', 'Posisi Jabatan', 'Waktu Gabung', 'Bulan Penyesuaian Sebelumnya', 'Alasan', 'Gaji Sebelum', 'Jumlah Penyesuaian', 'Gaji Sesudah', 'Lemburan Awal', 'Perubahan Lemburan', 'Bonus'];
        $actualHeader = $rows[4]; // baris header

        if (array_diff($expectedHeader, $actualHeader)) {
            return back()->withErrors(['file' => 'Header file tidak sesuai format yang diharapkan.']);
        }

        $jumlahUpdate = 0;

        foreach ($rows as $index => $row) {
            if ($index < 5) continue; // skip header dan info awal
            $id_karyawan = isset($row[0]) ? (int) str_replace(',', '', $row[0]) : null;
            $nama = $row[1];
            $gaji_raw = $row[9] ?? null;
            $lembur_raw = $row[11] ?? null;

            $gaji_sesudah = null;
            $lembur_baru = null;

            if ($gaji_raw !== null && $gaji_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $gaji_raw)) {
                    $gaji_sesudah = (int) str_replace([',', '.'], '', $gaji_raw);
                } else {
                    return back()->with('error', "{$row[1]} - ID: {$row[0]}, GAJI POKOK di file excel harus numeric. {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }

            if ($lembur_raw !== null && $lembur_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $lembur_raw)) {
                    $lembur_baru = (int) str_replace([',', '.'], '', $lembur_raw);
                } else {
                    return back()->with('error', "{$nama} - ID: {$id_karyawan}, GAJI LEMBUR di file excel harus numeric, {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }

            if ($lembur_raw !== null && $lembur_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $lembur_raw)) {
                    $lembur_baru = (int) str_replace([',', '.'], '', $lembur_raw);
                } else {
                    return back()->with('error', "{$nama} - ID: {$id_karyawan}, GAJI LEMBUR di file excel harus numeric, {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }
            // if ($id_karyawan == 2792) dd($gaji_sesudah, $lembur_baru);
            if (!$id_karyawan  || !$gaji_sesudah) continue;

            $karyawan = Karyawan::where('id_karyawan', $id_karyawan)->where('nama', $nama)->first();
            // dd($karyawan, $id_karyawan, $nama);
            if ($karyawan) {
                $updated = false;

                if ($karyawan->gaji_pokok != $gaji_sesudah) {
                    $karyawan->gaji_pokok = $gaji_sesudah;
                    $updated = true;
                    // dd($karyawan->gaji_pokok, $gaji_sesudah, $updated);
                }

                if ($lembur_baru && $karyawan->gaji_overtime != $lembur_baru) {
                    $karyawan->gaji_overtime = $lembur_baru;
                    $updated = true;
                }

                if ($updated) {
                    $karyawan->tanggal_update = Carbon::now();
                    $karyawan->save();
                    $jumlahUpdate++;
                }
            }
        }

        return back()->with('success', "{$jumlahUpdate} data karyawan berhasil diperbarui.");
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Validasi header baris ke-4 (index 3)
        $expectedHeader = [
            'ID Employee',
            'Nama',
            'Departemen',
            'Posisi Jabatan',
            'Job Grade',
            'Waktu Gabung',
            'Bulan Penyesuaian Sebelumnya',
            'Alasan',
            'Gaji Sebelum',
            'Jumlah Penyesuaian',
            'Gaji Sesudah',
            'Lemburan Awal',
            'Perubahan Lemburan',
            'Bonus'
        ];
        // $actualHeader = $rows[4] ?? [];

        // if (array_diff($expectedHeader, $actualHeader)) {
        //     return back()->withErrors(['file' => 'Header file tidak sesuai format yang diharapkan.']);
        // }

        // $tanggal = $this->extractTanggal($rows[2][1]);
        $tanggal = Carbon::today()->toDateString();
        // dd($rows[2][1], $tanggal);
        $jobgrades = Jobgrade::pluck('id', 'grade')->toArray();


        $jumlahUpdate = 0;

        foreach ($rows as $index => $row) {
            if ($index < 5) continue; // Skip header dan informasi awal

            $id_karyawan = isset($row[0]) ? (int) str_replace(',', '', $row[0]) : null;
            $nama = $row[1];
            $jobGrade_raw = trim($row[4]) ?? null;

            $gaji_raw = $row[10] ?? null;
            $lembur_raw = $row[12] ?? null;
            $bonus_raw = $row[13] ?? null;

            $jobGrade_sesudah = null;
            $gaji_sesudah = null;
            $lembur_baru = null;
            $bonus_baru = null;

            // Validasi GAJI SESUDAH
            if ($gaji_raw !== null && $gaji_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $gaji_raw)) {
                    $gaji_sesudah = (int) str_replace([',', '.'], '', $gaji_raw);
                } else {
                    return back()->with('error', "{$nama} - ID: {$id_karyawan}, GAJI POKOK di file excel harus numeric. {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }


            // Validasi LEMBUR BARU
            if ($lembur_raw !== null && $lembur_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $lembur_raw)) {
                    $lembur_baru = (int) str_replace([',', '.'], '', $lembur_raw);
                } else {
                    return back()->with('error', "{$nama} - ID: {$id_karyawan}, GAJI LEMBUR di file excel harus numeric. {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }

            // Validasi BONUS BARU
            if ($bonus_raw !== null && $bonus_raw !== '') {
                if (preg_match('/^[\d,\.]+$/', $bonus_raw)) {
                    $bonus_baru = (int) str_replace([',', '.'], '', $bonus_raw);
                } else {
                    return back()->with('error', "{$nama} - ID: {$id_karyawan}, GAJI BONUS di file excel harus numeric. {$jumlahUpdate} data karyawan berhasil diperbarui.");
                }
            }


            // Skip jika ID kosong atau tidak ada data gaji/lembur
            if (!$id_karyawan || ($gaji_sesudah === null && $lembur_baru === null && $bonus_baru === null)) {
                continue;
            }

            $karyawan = Karyawan::where('id_karyawan', $id_karyawan)
                ->where('nama', $nama)
                ->first();


            if ($karyawan) {
                $updated = false;


                // Update gaji_pokok jika berbeda atau meskipun 0
                if ($gaji_sesudah !== null && $karyawan->gaji_pokok != $gaji_sesudah) {
                    $karyawan->gaji_pokok = $gaji_sesudah;

                    $updated = true;
                }

                // Update gaji_overtime jika berbeda atau meskipun 0
                if ($lembur_baru !== null && $karyawan->gaji_overtime != $lembur_baru) {
                    $karyawan->gaji_overtime = $lembur_baru;
                    $updated = true;
                }
                // update Job Grade
                if (!empty($jobGrade_raw) && isset($jobgrades[$jobGrade_raw])) {
                    $jobGrade_sesudah = $jobgrades[$jobGrade_raw];
                    $karyawan->level_jabatan = $jobGrade_sesudah;

                    $updated = true;
                } else {
                    // Kalau tidak ditemukan, kasih default value
                    $jobGrade_sesudah  = null;
                    $karyawan->level_jabatan = $jobGrade_sesudah;
                    $updated = true;
                }

                if ($updated) {
                    // $karyawan->tanggal_update = Carbon::now();
                    $karyawan->tanggal_update = Carbon::parse($tanggal);
                    $karyawan->save();
                    $jumlahUpdate++;
                }
                // if ($bonus_baru !== null) {

                if ($bonus_baru > 0) {
                    $data = Bonuspotongan::where('user_id', $id_karyawan)
                        ->whereMonth('tanggal', Carbon::parse($tanggal)->format('m'))
                        ->whereYear('tanggal', Carbon::parse($tanggal)->format('Y'))
                        ->first();
                    // if ($id_karyawan == 10063) dd($data);
                    if (!$data) {
                        // Simpan baru jika belum ada
                        $data = new Bonuspotongan;
                        $data->karyawan_id = $karyawan->id;
                        $data->user_id = $id_karyawan;
                        $data->tanggal = Carbon::parse($tanggal)->format('Y-m-d');
                        $data->bonus_lain = $bonus_baru;
                        $data->save();
                        $jumlahUpdate++;
                    } else {
                        // Update jika sudah ada
                        if ($data->bonus_lain != $bonus_baru) {
                            $data->bonus_lain = $bonus_baru;
                            $data->save();
                            $jumlahUpdate++;
                        }
                    }
                }
            }
        }

        return back()->with('success', "{$jumlahUpdate} data karyawan berhasil diperbarui.");
    }
}
