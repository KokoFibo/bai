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
    $karyawans = Karyawan::where('placement_id', 110)->get();

    foreach ($karyawans as $karyawan) {
      $karyawan->placement_id = 109;
      $karyawan->save();
    }
    dd('dones');

    // $karyawans = DB::connection('mysql_salary')
    //   ->table('karyawans')
    //   // ->whereIn('placement_id', [106, 110])
    //   ->where('placement_id', 110)
    //   ->get();

    // $userIds = $karyawans->pluck('id_karyawan')->unique();
    // $users = DB::connection('mysql_salary')
    //   ->table('users')
    //   ->whereIn('username', $userIds)
    //   ->get();

    // foreach ($karyawans as $karyawan) {
    //   $data = (array) $karyawan;

    //   unset($data['id']); // penting

    //   Karyawan::on('mysql')->create($data);
    // }

    // foreach ($users as $user) {
    //   $data = (array) $user;

    //   unset($data['id']); // penting

    //   User::on('mysql')->create($data);
    // }




    // dd('done');
    // dd($data->count());








    return view('livewire.test');
  }
}
