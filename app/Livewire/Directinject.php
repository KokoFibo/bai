<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Livewire\Component;

class Directinject extends Component
{
    public $user_id;
    public $date;

    public $record;

    public $first_in;
    public $first_out;
    public $second_in;
    public $second_out;
    public $overtime_in;
    public $overtime_out;

    public $late;
    public $shift;
    public $no_scan;

    public $total_jam_kerja;
    public $total_hari_kerja;
    public $total_jam_lembur;

    public $total_jam_kerja_libur;
    public $total_hari_kerja_libur;
    public $total_jam_lembur_libur;

    public function cari()
    {
        $this->validate([
            'user_id' => 'required',
            'date' => 'required|date'
        ]);

        $this->record = Yfrekappresensi::where('user_id', $this->user_id)
            ->whereDate('date', $this->date)
            ->first();

        if (!$this->record) {

            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data tidak ditemukan.'
            );

            return;
        }

        $this->fill([
            'first_in' => $this->record->first_in,
            'first_out' => $this->record->first_out,
            'second_in' => $this->record->second_in,
            'second_out' => $this->record->second_out,
            'overtime_in' => $this->record->overtime_in,
            'overtime_out' => $this->record->overtime_out,
            'late' => $this->record->late,
            'shift' => $this->record->shift,
            'no_scan' => $this->record->no_scan,
            'total_jam_kerja' => $this->record->total_jam_kerja,
            'total_hari_kerja' => $this->record->total_hari_kerja,
            'total_jam_lembur' => $this->record->total_jam_lembur,
            'total_jam_kerja_libur' => $this->record->total_jam_kerja_libur,
            'total_hari_kerja_libur' => $this->record->total_hari_kerja_libur,
            'total_jam_lembur_libur' => $this->record->total_jam_lembur_libur,
        ]);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data berhasil ditemukan.'
        );
    }

    public function simpan()
    {
        if (!$this->record) {
            return;
        }

        $this->record->update([

            'first_in' => $this->first_in,
            'first_out' => $this->first_out,
            'second_in' => $this->second_in,
            'second_out' => $this->second_out,
            'overtime_in' => $this->overtime_in,
            'overtime_out' => $this->overtime_out,

            'late' => $this->late,
            'shift' => $this->shift,
            'no_scan' => $this->no_scan,

            'total_jam_kerja' => $this->total_jam_kerja,
            'total_hari_kerja' => $this->total_hari_kerja,
            'total_jam_lembur' => $this->total_jam_lembur,

            'total_jam_kerja_libur' => $this->total_jam_kerja_libur,
            'total_hari_kerja_libur' => $this->total_hari_kerja_libur,
            'total_jam_lembur_libur' => $this->total_jam_lembur_libur,
        ]);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data berhasil diperbarui.'
        );
    }

    public function render()
    {
        return view('livewire.directinject');
    }
}
