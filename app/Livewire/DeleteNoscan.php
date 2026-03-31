<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Yfrekappresensi;

class DeleteNoscan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tgl_noscan, $show_noscan_history, $show_noscan;
    // public  $data, $data1;

    public function mount()
    {
        $this->show_noscan_history = true;
        $this->show_noscan = false;
    }

    public function show_noscan_history_button()
    {
        $this->show_noscan_history = true;
        $this->show_noscan = false;
    }
    public function show_noscan_button()
    {
        $this->show_noscan = true;
        $this->show_noscan_history = false;
    }

    public function delete_noscan_history()
    {
        $data = Yfrekappresensi::where('date', $this->tgl_noscan)->where('no_scan_history', 'No Scan')->where('no_scan', null)->get();
        $cx = 0;

        foreach ($data as $item) {
            $item->no_scan_history = null;
            $item->save();
            $cx++;
        }
        // $this->dispatch('success', message: $cx . ' Data no scan history sudah di delete');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'ID: ' . $this->username . 'Data no scan history sudah di delete',
        );
    }

    //function ini sengaja di non aktifkan
    // public function delete_noscan()
    // {
    //     $data = $data = Yfrekappresensi::where('date', $this->tgl_noscan)->where('no_scan', 'No Scan')->get();
    //     $cx = 0;
    //     foreach ($data as $item) {
    //         $item->no_scan = null;
    //         $item->save();
    //         $cx++;
    //     }
    //     $this->dispatch('success', message: $cx . ' Data no scan sudah di delete');
    // }


    public function render()
    {
        if ($this->show_noscan) {

            $data = Yfrekappresensi::where('date', $this->tgl_noscan)->where('no_scan', 'No Scan')->paginate(10);
        } else {

            $data = Yfrekappresensi::where('date', $this->tgl_noscan)->where('no_scan_history', 'No Scan')->where('no_scan', null)->paginate(10);
        }
        // $data1 = Yfrekappresensi::where('date', $tgl)->where('no_scan_history', 'No Scan')->get();


        return view('livewire.delete-noscan', [
            'data' => $data,

        ]);
    }
}
