<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Removepresensiduplikatwr extends Component
{
    public $tgl;

    public function process () {

    $data = Yfrekappresensi::whereDate('date', $this->tgl)->first();
   if($data != null) {



        Yfrekappresensi::whereDate('date', $this->tgl)
    ->select('user_id', DB::raw('MIN(id) as id'))
    ->groupBy('user_id')
    ->havingRaw('COUNT(*) > 1')
    ->get()
    ->each(function ($duplicate) {
        Yfrekappresensi::where('user_id', $duplicate->user_id)
            ->where('id', '<>', $duplicate->id)
            ->whereDate('date', $this->tgl)
            ->delete();
    });
         $this->dispatch( 'success', message: 'Dulicate Removed' );
        } else
        {
            $this->dispatch( 'error', message: 'No Data Found' );

        }
     }
    public function render()
    {
        return view('livewire.removepresensiduplikatwr');
    }
}
