<?php

namespace App\Livewire;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Ter;
use App\Models\User;

use App\Models\Company;
use App\Models\Jabatan;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Placement;
use App\Models\Requester;
use App\Models\Department;
use App\Models\Jamkerjaid;
use App\Models\Rekapbackup;
use Livewire\WithPagination;
use App\Models\Applicantfile;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use Illuminate\Http\Response;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;
use App\Models\Personnelrequestform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class Test extends Component
{
  // public $saturday;
  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $month;
  public $year;
  public $today;
  public $cx;
  public $test;


  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }

  public function DeleteBeforeSeptember()
  {
    Yfrekappresensi::whereDate('date', '<', '2025-09-01')->delete();
    $this->dispatch(
      'message',
      type: 'success',
      title: "Data seblum september 2025 berhasil dihapus."
    );
  }

  public function render()
  {

    $month = 11;
    $year = 2026;

    dd('aman');
    $data = Yfrekappresensi::where('date', '2026-01-24')
      ->whereNot('late', null)
      ->get();
    // dd($data->count());








    $payrolls = Payroll::whereYear('date', 2025)
      ->whereMonth('date', 9)
      ->get();

    $beda = []; // untuk menampung data yang tidak sama
    $cx = 0;

    foreach ($payrolls as $payroll) {
      $presensis = Yfrekappresensi::whereYear('date', 2025)
        ->whereMonth('date', 9)
        ->where('user_id', $payroll->id_karyawan)
        ->get();

      // $total_hari_kerja = Yfrekappresensi::whereYear('date', 2025)
      //   ->whereMonth('date', 9)
      //   ->where('user_id', $payroll->id_karyawan)
      //   ->count();

      $total_jam_kerja = $presensis->sum('total_jam_kerja');
      $total_jam_lembur = $presensis->sum('total_jam_lembur');
      // cek perbedaan
      // $payroll->hari_kerja != $total_hari_kerja ||
      if (
        $payroll->jam_kerja != $total_jam_kerja ||
        $payroll->jam_lembur != $total_jam_lembur
      ) {
        $beda[] = [
          'id_karyawan' => $payroll->id_karyawan,
          // 'hari_kerja_payroll' => $payroll->hari_kerja,
          // 'hari_kerja_presensi' => $total_hari_kerja,
          'jam_kerja_payroll' => $payroll->jam_kerja,
          'jam_kerja_presensi' => $total_jam_kerja,
          'jam_lembur_payroll' => $payroll->jam_lembur,
          'jam_lembur_presensi' => $total_jam_lembur,
        ];
      } else {
        $cx++;
      }
    }

    return view('livewire.test', [
      'beda' => $beda,
      'jumlah_sama' => $cx,
    ]);
  }
}
