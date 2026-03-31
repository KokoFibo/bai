<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Yfrekappresensi;

class Yfdeletetanggalpresensiwr extends Component
{
    public $tanggal;
    public $lokasi;

    public function deleteByPabrik()
    {
        $data = Yfrekappresensi::where('date', $this->tanggal)->get(['karyawan_id', 'id']);
        foreach ($data as $d) {
            if ($this->lokasi == 0) {
                $is_terdaftar = Karyawan::where('id', $d->karyawan_id)->whereIn('placement', ['YIG', 'YSM'])->first();
                if ($is_terdaftar != null) {
                    Yfrekappresensi::find($d->id)->delete();
                }
            }
            if ($this->lokasi == 1) {
                $is_terdaftar = Karyawan::where('id', $d->karyawan_id)->where('placement', 'YCME')->first();
                if ($is_terdaftar != null) {
                    Yfrekappresensi::find($d->id)->delete();
                }
            }
            if ($this->lokasi == 2) {
                $is_terdaftar = Karyawan::where('id', $d->karyawan_id)->where('placement', 'YEV')->first();
                if ($is_terdaftar != null) {
                    Yfrekappresensi::find($d->id)->delete();
                }
            }
        }

        // $this->dispatch('success', message: 'Data pada tanggal tersebut telah di hapus');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data pada tanggal tersebut telah di hapus',
        );
    }

    public function delete()
    {
        $data = Yfrekappresensi::whereDate('date', $this->tanggal)->get();
        if ($data->isEmpty($data)) {
            // $this->dispatch('error', message: 'Data presensi tidak ditemukan');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data presensi tidak ditemukan',
            );
        } else {
            Yfrekappresensi::whereDate('date', $this->tanggal)->delete();
            // $this->dispatch('success', message: 'Data pada tanggal tersebut telah di hapus');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data pada tanggal tersebut telah di hapus',
            );
        }
    }
    public function exit()
    {
        $this->reset();
        return redirect()->to('/newpresensi');
        // or sepertoi dibawah juga bisa
        // return redirect('/yfpresensiindexwr');
    }
    public function render()
    {
        return view('livewire.yfdeletetanggalpresensiwr');
    }
}
