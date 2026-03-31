<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MultipleKaryawanForm implements FromView
{
    public $karyawans;
    public $header_text;

    public function __construct($karyawans, $header_text)
    {
        $this->karyawans = $karyawans;
        $this->header_text = $header_text;
    }
    public function view(): View
    {
        return view('karyawan_excel_form_view', [
            // 'data' => $data,
            'karyawans' => $this->karyawans,
            'header_text' => $this->header_text
        ]);
    }
}
