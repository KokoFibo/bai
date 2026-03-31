<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;

class IuranLocker extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function delete($id)
    {
        $data = Karyawan::find($id);
        $data->iuran_locker = 0;
        $data->save();
        // $this->dispatch('success', message: 'Iuran Locker ' . $data->nama . ' sudah di kosongkan');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Iuran Locker ' . $data->nama . ' sudah di kosongkan',
        );
    }
    public function render()
    {
        $data = Karyawan::where('iuran_locker', '>', 0)
            ->where('tanggal_bergabung', '<', Carbon::now()->subMonth(13))->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->paginate(10);
        return view('livewire.iuran-locker', [
            'data' => $data
        ]);
    }
}
