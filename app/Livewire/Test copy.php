<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Test extends Component
{
  // public $saturday;
  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $month;
  public $year;
  public $today;
  public $cx;

  public function mount()
  {
    $this->cx = 0;
    $this->today = now();

    $this->year = now()->year;
    $this->month = now()->month;
  }

  public function build()
  {
    build_payroll1('03', '2024');
  }
  public function shortJam($jam)
  {
    if ($jam != null) {
      $arrJam = explode(':', $jam);
      return $arrJam[0] . ':' . $arrJam[1];
    }
  }



  public function render()
  {
    $month = '04';
    $year = '2024';
    $startTime = '07:46';
    $endTime = '07:47';
    $first_out = '11:30:00';
    // $startTime = Carbon::createFromFormat('H:i:s', $jam1);
    // $endTime = Carbon::createFromFormat('H:i:s', $jam2);
    // $diffInMinutes = $endTime->diffInMinutes($startTime);
    $t1 = strtotime('11:30:00');
    $t2 = strtotime($first_out);
    $perJam = 60;
    $diff = gmdate('H:i:s', $t1 - $t2);
    $late = ceil(hoursToMinutes($diff) / $perJam);

    $data = Yfrekappresensi::where('date', '2024-05-04')
      // ->whereBetween('second_out', ['00:00:00', '04:59:00'])->orderBy('second_out', 'ASC')
      ->whereBetween('first_in', ['18:00:00', '18:59:00'])->orderBy('first_in', 'ASC')
      ->paginate(10);

    $data_first_in = Yfrekappresensi::where('date', '2024-05-04') // 511 data
      ->whereBetween('first_in', ['19:00:00', '23:00:00'])->orderBy('first_in', 'ASC')
      // ->paginate(10);
      ->get();

    // ->get();

    $data_second_out = Yfrekappresensi::where('date', '2024-05-04') // second out telat 4 data
      ->whereBetween('first_in', ['19:00:00', '21:00:00'])
      ->whereBetween('second_out', ['00:00:00', '04:59:00'])
      ->orderBy('second_out', 'ASC')
      ->paginate(10);

    $first_out = '01:30:00';
    $result = date('H:i:s', strtotime($first_out) - 3 * 3600);
    // dd($result);
    $cx = 0;
    if ($data_first_in->count() > 0) {
      foreach ($data_first_in as $d) {
        $cx++;
        $data = Yfrekappresensi::find($d->id);
        $data->first_in = date('H:i:s', strtotime($d->first_in) - 3 * 3600);
        $data->second_out = date('H:i:s', strtotime($d->second_out) - 3 * 3600);
        if ($data->overtime_in) {
          $data->overtime_in = date('H:i:s', strtotime($d->overtime_in) - 3 * 3600);
        }
        if ($data->overtime_out) {
          $data->overtime_out = date('H:i:s', strtotime($d->overtime_out) - 3 * 3600);
        }
        $data->first_out = null;
        $data->second_in = null;
        $data->late = null;
        $noscan = '';
        $noscan = noScan($data->first_in, $data->first_out, $data->second_in, $data->second_out, $data->overtime_in, $data->overtime_out);
        if ($noscan == '') {
          $data->no_scan = null;
          $data->no_scan_history = null;
        }
        $data->save();
      }
      dd('done: ', $cx);
    }


    $data_first_in_paginate = Yfrekappresensi::where('date', '2024-05-04') // 511 data
      ->whereBetween('first_in', ['15:00:00', '23:00:00'])->orderBy('first_in', 'ASC')
      ->paginate(10);




    return view('livewire.test', [
      'data' => $data_first_in_paginate,
    ]);
  }
}
