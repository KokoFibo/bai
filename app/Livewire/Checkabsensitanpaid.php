<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;
use Illuminate\Support\Facades\DB;

class Checkabsensitanpaid extends Component
{
    public $month, $year;
    public $unmatchedUserIds;

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function delete()
    {
        $data = $this->checkUnmatch($this->month, $this->year);

        foreach ($data as $d) {

            Yfrekappresensi::whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('user_id', $d)
                ->delete(); // Perform delete operation
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data unMatch terlah dihapus dari YFrekappresensi',
        );
    }


    public function checkUnmatch($month, $year)
    {
        $this->unmatchedUserIds = DB::table('yfrekappresensis')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereNotIn('user_id', function ($query) {
                $query->select('id_karyawan')->from('karyawans');
            })
            ->distinct()
            ->pluck('user_id'); // Ambil daftar user_id yang tidak cocok


        return $this->unmatchedUserIds;
    }

    public function render()
    {
        $data = $this->checkUnmatch($this->month, $this->year);
        return view('livewire.checkabsensitanpaid', [
            'data' => $data,

        ]);
    }
}
