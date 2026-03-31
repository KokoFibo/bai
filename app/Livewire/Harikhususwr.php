<?php

namespace App\Livewire;

use App\Models\Harikhusus;
use Livewire\Component;

class Harikhususwr extends Component
{
    public $date, $is_friday = false, $is_saturday = false, $is_sunday = false, $is_hari_libur_nasional = false;

    // public function clear()
    // {
    //     $this->date = null;
    //     $this->is_friday = false;
    //     $this->is_saturday = false;
    //     $this->is_sunday = false;
    //     $this->is_hari_libur_nasional = false;
    // }


    public function save()
    {
        // dd($this->date, $this->is_friday, $this->is_saturday, $this->is_sunday, $this->is_hari_libur_nasional);
        $this->validate([
            'date' => 'required|date',
            'is_friday' => 'nullable|boolean',
            'is_saturday' => 'nullable|boolean',
            'is_sunday' => 'nullable|boolean',
            'is_hari_libur_nasional' => 'nullable|boolean',
        ]);
        // Cek apakah tanggal sudah ada
        // $data = new Harikhusus();
        // $data->date = $this->date;
        // $data->is_friday = $this->is_friday;
        // $data->is_saturday = $this->is_saturday;
        // $data->is_sunday = $this->is_sunday;
        // $data->is_hari_libur_nasional = $this->is_hari_libur_nasional;
        // $data->save();

        if (
            !$this->is_friday &&
            !$this->is_saturday &&
            !$this->is_sunday &&
            !$this->is_hari_libur_nasional
        ) {
            session()->flash('message', 'Minimal satu jenis hari khusus harus dipilih.');
            return;
        }

        // $data = new Harikhusus();
        // $data->date = $this->date;
        // $data->is_friday = $this->is_friday;
        // $data->is_saturday = $this->is_saturday;
        // $data->is_sunday = $this->is_sunday;
        // $data->is_hari_libur_nasional = $this->is_hari_libur_nasional;
        // $data->save();

        // session()->flash('message', 'Hari khusus berhasil disimpan.');
        // $this->reset(['date', 'is_friday', 'is_saturday', 'is_sunday', 'is_hari_libur_nasional']);

        Harikhusus::updateOrCreate(
            ['date' => $this->date], // Hanya cari berdasarkan tanggal
            [
                'is_friday' => $this->is_friday,
                'is_saturday' => $this->is_saturday,
                'is_sunday' => $this->is_sunday,
                'is_hari_libur_nasional' => $this->is_hari_libur_nasional,
            ]
        );

        // session()->flash('message', 'Hari khusus berhasil disimpan.');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Hari khusus berhasil disimpan.',
        );
        $this->reset(['date', 'is_friday', 'is_saturday', 'is_sunday', 'is_hari_libur_nasional']);
    }

    public function delete($id)
    {
        $data = Harikhusus::find($id);
        if ($data) {
            $data->delete();
            // session()->flash('message', 'Hari khusus berhasil dihapus.');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Hari khusus berhasil Delete.',
            );
        } else {
            // session()->flash('message', 'Hari khusus tidak ditemukan.');
            $this->dispatch(
                'error',
                type: 'success',
                title: 'Hari khusus tidak ditemukan.',
            );
        }
    }

    public function render()
    {
        $data = Harikhusus::all();
        return view('livewire.harikhususwr', ['data' => $data]);
    }
}
