<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class UpdatedPresensi extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public $date, $month, $year;

    public function mount () {
        $this->month = 11;
        $this->year = 2023;
        $this->date = Carbon::today();
    }
    public function checkUpdatedPresensi () {
        // $month = Carbon::parse(now())->format('m');
        // $year = Carbon::parse($this->date)->format('Y');
 
    }
    public function render()
    {
        $data = Yfrekappresensi::whereMonth('date', $this->month)
       ->whereYear('date', $this->year)
       ->whereDate('updated_at', $this->date)
       ->paginate(10);

        return view('livewire.updated-presensi', compact('data'));
    }
}
