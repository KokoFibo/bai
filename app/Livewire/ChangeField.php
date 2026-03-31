<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class ChangeField extends Component
{
    public function change()
    {
        $data = Karyawan::get();
        $total_karyawan = Karyawan::count();
        $placement = '';
        $cx = 0;
        foreach ($data as $d) {
            switch ($d->placement) {
                case 'ASB':
                    $placement = '1';
                    $cx++;

                    break;
                case 'DPA':
                    $placement = '2';
                    $cx++;

                    break;
                case 'GAMA':
                    $placement = '3';
                    $cx++;

                    break;
                case 'WAS':
                    $placement = '4';
                    $cx++;

                    break;
                case 'YAM':
                    $placement = '5';
                    $cx++;

                    break;
                case 'YCME':
                    $placement = '6';
                    $cx++;

                    break;
                case 'YEV':
                    $placement = '7';
                    $cx++;

                    break;
                case 'YEV AIMA':
                    $placement = '8';
                    $cx++;

                    break;
                case 'YEV OFFERO':
                    $placement = '9';
                    $cx++;

                    break;
                case 'YEV SMOOT':
                    $placement = '10';
                    $cx++;

                    break;
                case 'YEV SUNRA':
                    $placement = '11';
                    $cx++;

                    break;
                case 'YIG':
                    $placement = '12';
                    $cx++;

                    break;
                case 'YSM':
                    $placement = '13';
                    $cx++;

                    break;
            }
            $d->placement = $placement;
            $d->save();
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: $cx . ' Data of ' . $total_karyawan . ' has been changed',
        );
    }
    public function render()
    {


        return view('livewire.change-field');
    }
}
