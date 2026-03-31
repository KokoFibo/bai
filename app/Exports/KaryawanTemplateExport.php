<?php

namespace App\Exports;

use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KaryawanTemplateExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    public $karyawans;
    public $header_text;

    public function __construct($karyawans, $header_text)
    {
        $this->karyawans = $karyawans;
        $this->header_text = $header_text;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            2    => ['font' => ['bold' => true]],
            // Styling a specific cell by coordinate.

            // Styling an entire column.
            2  => ['font' => ['size' => 15]],
            // 2 => ['font' => ['italic' => true]],
            3  => ['font' => ['size' => 12]],
        ];
    }


    public function view(): View
    {
        return view('karyawan_template_view', [
            // 'data' => $data,
            'karyawans' => $this->karyawans,
            'header_text' => $this->header_text
        ]);
    }
    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'D' => "0",
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,



        ];
    }
}
