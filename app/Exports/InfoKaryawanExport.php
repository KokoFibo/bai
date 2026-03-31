<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class InfoKaryawanExport implements FromView, ShouldAutoSize
{
    public $data;
    public $periode;
    public $label;
    public $title;

    public function __construct($data, $periode, $label, $title)
    {
        $this->data = $data;
        $this->periode = $periode;
        $this->label = $label;
        $this->title = $title;
    }

    public function view(): View
    {
        return view('info_karyawan_excel_view', [
            'data'    => $this->data,
            'periode' => $this->periode,
            'label' => $this->label,
            'title' => $this->title,
        ]);
    }
}
