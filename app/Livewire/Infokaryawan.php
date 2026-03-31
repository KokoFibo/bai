<?php

namespace App\Livewire;

use App\Exports\InfoKaryawanExport;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;

use App\Models\Applicantfile;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Infokaryawan extends Component
{
    public $month, $year;
    public $showKaryawanTanpaEmail = false;
    public $showKaryawanTanpaRekening = false;


    public function toggleKaryawanTanpaRekening()
    {
        $this->showKaryawanTanpaRekening = !$this->showKaryawanTanpaRekening;
    }
    public function toggleKaryawanTanpaEmail()
    {
        $this->showKaryawanTanpaEmail = !$this->showKaryawanTanpaEmail;
    }


    public function render()
    {
        $total_karyawan_aktif = Karyawan::whereNotIn('status_karyawan', ['Resigned', 'Blacklist'])->count();

        // $total_karyawan_hadir_hari_ini = Yfrekappresensi::whereDate('date', today())->count();


        $dataTanpaRekening = Karyawan::where(function ($q) {
            $q->where('nomor_rekening', '')
                ->orWhereNull('nomor_rekening');
        })
            ->whereNotIn('status_karyawan', ['Resigned', 'Blacklist'])
            ->get();


        $jumlahTanpaRekening = $dataTanpaRekening->count();

        $total_karyawan_perbulan = Karyawan::where('metode_penggajian', 'Perbulan')
            ->whereNotIn('status_karyawan', ['Resigned', 'Blacklist'])->count();

        $total_karyawan_perjam = Karyawan::where('metode_penggajian', 'Perjam')
            ->whereNotIn('status_karyawan', ['Resigned', 'Blacklist'])->count();

        // hitung semua status dalam 1 query
        $statusCount = Karyawan::select('status_karyawan', DB::raw('COUNT(*) as total'))
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'])
            ->groupBy('status_karyawan')
            ->pluck('total', 'status_karyawan');

        // hitung jumlah karyawan yg punya ApplicantFile dalam 1 query bukan looping
        $karyawan_berdokumen = Applicantfile::distinct('id_karyawan')->count('id_karyawan');

        // 1. Ambil semua karyawan aktif yang seharusnya punya dokumen
        $karyawanYgHarusPunyaDokumen = Karyawan::whereNotNull('id_file_karyawan')
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->pluck('id_file_karyawan')
            ->toArray();

        // 2. Ambil semua id_karyawan yang sudah punya dokumen
        $karyawanYgSudahPunyaDokumen = Applicantfile::distinct('id_karyawan')
            ->pluck('id_karyawan')
            ->toArray();

        // 3. Cari yang belum punya dokumen
        $karyawan_tanpa_dokumen = array_diff(
            $karyawanYgHarusPunyaDokumen,
            $karyawanYgSudahPunyaDokumen
        );

        // 4. Hitung jumlahnya
        $jumlah_karyawan_tanpa_dokumen = count($karyawan_tanpa_dokumen);

        $karyawan_perbulan_tanpa_ptkp = Karyawan::whereNotNull('id_file_karyawan')
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where('ptkp', null)->count();

        $karyawan_tanpa_email = Karyawan::whereNotNull('id_file_karyawan')
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
            ->where(function ($q) {
                $q->whereNull('email')
                    ->orWhere('email', '')
                    ->orWhere('email', ' ');
            })
            ->get();

        $jumlah_karyawan_tanpa_email = $karyawan_tanpa_email->count();

        $duplicates = Karyawan::whereNotIn('status_karyawan', ['Blacklist', 'Resigned'])
            ->whereNotNull('nomor_rekening')
            ->where('nomor_rekening', '!=', '')
            ->get()
            ->groupBy(function ($item) {
                return trim($item->nomor_rekening);
            })
            ->filter(function ($group) {
                return $group->count() > 1;
            });

        // dd($duplicates->all());


        return view('livewire.infokaryawan', [
            'total_karyawan_aktif' => $total_karyawan_aktif,
            // 'total_karyawan_hadir_hari_ini' => $total_karyawan_hadir_hari_ini,
            'jumlahTanpaRekening' => $jumlahTanpaRekening,
            'dataTanpaRekening' => $dataTanpaRekening,

            'pkwt'       => $statusCount['PKWT']       ?? 0,
            'pkwtt'      => $statusCount['PKWTT']      ?? 0,
            'dirumahkan' => $statusCount['Dirumahkan'] ?? 0,
            'resigned'   => $statusCount['Resigned']   ?? 0,
            'blacklist'  => $statusCount['Blacklist']  ?? 0,

            'karyawan_berdokumen' => $karyawan_berdokumen,
            'total_karyawan_perjam' => $total_karyawan_perjam,
            'total_karyawan_perbulan' => $total_karyawan_perbulan,
            'jumlah_karyawan_tanpa_dokumen' => $jumlah_karyawan_tanpa_dokumen,
            'karyawan_perbulan_tanpa_ptkp' => $karyawan_perbulan_tanpa_ptkp,
            'karyawan_tanpa_email' => $karyawan_tanpa_email,
            'jumlah_karyawan_tanpa_email' => $jumlah_karyawan_tanpa_email,
            'duplicates' => $duplicates,

        ]);
    }
}
