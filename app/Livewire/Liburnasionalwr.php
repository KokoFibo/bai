<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Liburnasional;

class Liburnasionalwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama_hari_libur, $tanggal_mulai_hari_libur, $tanggal_akhir_libur, $jumlah_hari_libur;
    public $month, $year;
    public $is_edit, $is_create_new, $id;
    public function mount()
    {
        $this->is_edit = false;
        $this->is_create_new = false;
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function create_new()
    {
        $this->is_create_new = true;
    }

    public function cancel()
    {
        $this->reset();
        $this->is_edit = false;
        $this->is_create_new = false;
        $this->year = now()->year;
        $this->month = now()->month;
    }


    // public function exit()
    // {
    //     $this->reset();
    //     return redirect()->to('/');
    // }

    protected $rules = [
        'nama_hari_libur' => 'required',
        'tanggal_mulai_hari_libur' => 'date:required',
        'tanggal_akhir_libur' => 'nullable',
    ];

    public function updatedTanggalAkhirLibur()
    {
        if ($this->tanggal_akhir_libur == null) {
            $this->jumlah_hari_libur = 1;
        } else {
            $date_start = Carbon::parse($this->tanggal_mulai_hari_libur);
            $date_end = Carbon::parse($this->tanggal_akhir_libur);
            $this->jumlah_hari_libur = $date_start->diffInDays($date_end, false) + 1;
        }
    }

    public function edit($id)
    {
        $this->is_edit = true;
        $this->is_create_new = true;

        $data = Liburnasional::find($id);
        $this->id = $id;
        $this->nama_hari_libur = $data->nama_hari_libur;
        $this->tanggal_mulai_hari_libur = $data->tanggal_mulai_hari_libur;
        $this->tanggal_akhir_libur = $data->tanggal_akhir_libur;
        $this->jumlah_hari_libur = $data->jumlah_hari_libur;
    }

    public function update()
    {
        $this->validate();
        if ($this->tanggal_akhir_libur == null) {
            $this->jumlah_hari_libur = 1;
        } else {
            $date_start = Carbon::parse($this->tanggal_mulai_hari_libur);
            $date_end = Carbon::parse($this->tanggal_akhir_libur);
            $this->jumlah_hari_libur = $date_start->diffInDays($date_end, false) + 1;
        }
        $data = Liburnasional::find($this->id);
        $data->nama_hari_libur = $this->nama_hari_libur;
        $data->tanggal_mulai_hari_libur = $this->tanggal_mulai_hari_libur;
        $data->tanggal_akhir_libur = $this->tanggal_akhir_libur;
        $data->jumlah_hari_libur = $this->jumlah_hari_libur;
        $data->save();
        $this->cancel();

        // $this->dispatch('success', message: 'Hari libur nasional berhasil di update');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Hari libur nasional berhasil di update',
        );
    }



    public function save()
    {

        $this->validate();
        if ($this->tanggal_akhir_libur == null) {
            $this->jumlah_hari_libur = 1;
        } else {
            $date_start = Carbon::parse($this->tanggal_mulai_hari_libur);
            $date_end = Carbon::parse($this->tanggal_akhir_libur);
            $this->jumlah_hari_libur = $date_start->diffInDays($date_end, false) + 1;
        }
        $data = new Liburnasional;
        $data->nama_hari_libur = $this->nama_hari_libur;
        $data->tanggal_mulai_hari_libur = $this->tanggal_mulai_hari_libur;
        $data->tanggal_akhir_libur = $this->tanggal_akhir_libur;
        $data->jumlah_hari_libur = $this->jumlah_hari_libur;
        $data->save();
        $this->cancel();
        // $this->dispatch('success', message: 'Hari libur nasional berhasil disimpan');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Hari libur nasional berhasil disimpan',
        );
    }
    public function delete($id)
    {
        $data = Liburnasional::find($id);
        $data->delete();
        // $this->dispatch('success', message: 'Hari libur nasional berhasil di delete');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Hari libur nasional berhasil di delete',
        );
    }
    public function render()
    {
        $data = Liburnasional::orderBy('tanggal_mulai_hari_libur', 'asc')
            ->when($this->month != "", function ($query) {
                $query
                    ->whereMonth('tanggal_mulai_hari_libur', $this->month);
            })
            ->whereYear('tanggal_mulai_hari_libur', $this->year)
            ->get();
        return view('livewire.liburnasionalwr', [
            'data' => $data
        ]);
    }
}
