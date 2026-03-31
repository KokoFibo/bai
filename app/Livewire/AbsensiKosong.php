<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class AbsensiKosong extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function delete()
    {
        $data = Yfrekappresensi::where('first_in', null)
            ->where('first_out', null)
            ->where('second_in', null)
            ->where('second_out', null)
            ->where('overtime_in', null)
            ->where('overtime_out', null)
            ->get();

        foreach ($data as $d) {
            $data_delete = Yfrekappresensi::find($d->id);
            $data_delete->delete();
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Semua data absensi kosong telah di delete',
        );
    }

    public function render()
    {
        $data = Yfrekappresensi::where('first_in', null)
            ->where('first_out', null)
            ->where('second_in', null)
            ->where('second_out', null)
            ->where('overtime_in', null)
            ->where('overtime_out', null)
            ->paginate(10);
        return view('livewire.absensi-kosong', compact('data'));
    }
}
