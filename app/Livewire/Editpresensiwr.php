<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Yfrekappresensi;

class Editpresensiwr extends Component
{
    public $user_id;
    public $date;
    public $first_in;
    public $first_out;
    public $second_in;
    public $second_out;
    public $overtime_in;
    public $overtime_out;
    public $late;
    public $late_history;
    public $no_scan;
    public $no_scan_history;
    public $shift;
    public $id;

    public function edit () {
        $data = Yfrekappresensi::where('user_id', $this->user_id)->where('date', $this->date)->first();
      if($data != null) {



       $this->id = $data->id;
       $this->first_in = trimTime($data->first_in);
       $this->first_out = trimTime($data->first_out);
       $this->second_in = trimTime($data->second_in);
       $this->second_out = trimTime($data->second_out);
       $this->overtime_in = trimTime($data->overtime_in);
       $this->overtime_out = trimTime($data->overtime_out);
       $this->late = $data->late;
       $this->late_history = $data->late_history;
       $this->no_scan = $data->no_scan;
       $this->no_scan_history = $data->no_scan_history;
       $this->shift = $data->shift;

    } else {
        $this->dispatch( 'error', message: 'Data tidak ada' );

    }
    }

    public function save () {
        $this->validate([
            'first_in' => 'date_format:H:i|nullable',
            'first_out' => 'date_format:H:i|nullable',
            'second_in' => 'date_format:H:i|nullable',
            'second_out' => 'date_format:H:i|nullable',
            'overtime_in' => 'date_format:H:i|nullable',
            'overtime_out' => 'date_format:H:i|nullable',
        ]);

        $this->first_in != null ? ($this->first_in = $this->first_in . ':00') : ($this->first_in = null);
        $this->first_out != null ? ($this->first_out = $this->first_out . ':00') : ($this->first_out = null);
        $this->second_in != null ? ($this->second_in = $this->second_in . ':00') : ($this->second_in = null);
        $this->second_out != null ? ($this->second_out = $this->second_out . ':00') : ($this->second_out = null);
        $this->overtime_in != null ? ($this->overtime_in = $this->overtime_in . ':00') : ($this->overtime_in = null);
        $this->overtime_out != null ? ($this->overtime_out = $this->overtime_out . ':00') : ($this->overtime_out = null);

        $data = Yfrekappresensi::find($this->id);

        $data->first_in = $this->first_in;
       $data->first_out = $this->first_out;
       $data->second_in = $this->second_in;
       $data->second_out = $this->second_out;
       $data->overtime_in = $this->overtime_in;
       $data->overtime_out = $this->overtime_out;

       $data->late = $this->late;
       $data->late_history = $this->late_history;
       $data->no_scan = $this->no_scan;
       $data->no_scan_history = $this->no_scan_history;
       $data->shift = $this->shift;
       $data->save();
       $this->dispatch( 'success', message: 'Data berhasil di update' );
       $this->edit();


    }
    public function render()
    {
        return view('livewire.editpresensiwr');
    }
}
