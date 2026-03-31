<?php

namespace App\Livewire;

use App\Models\Ter;
use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Validation\Rules\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class Terwr extends Component
{
    public $file, $ter = '';
    use WithFileUploads;

    public function filter($ter)
    {
        $this->ter = $ter;
    }

    public function render()
    {
        if ($this->ter == '') {

            $data = Ter::all();
        } else {
            $data = Ter::where('ter', $this->ter)->get();
        }
        return view('livewire.terwr', [
            'data' => $data
        ]);
    }
}
