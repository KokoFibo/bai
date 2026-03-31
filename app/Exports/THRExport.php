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


class THRExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    // protected $data;

    // protected $selected_company, $status, $month, $year;
    // public function __construct($selected_company, $status, $month, $year)

    protected $cutOffDate;
    public function __construct($cutOffDate)
    {
        $this->cutOffDate = $cutOffDate;
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

        $data = Karyawan::with(['placement', 'company', 'department', 'jabatan'])->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])->get();

        $header_text = 'Perincian THR untuk OS';
        return view('thr_excel_view', [
            'cutOffDate' => $this->cutOffDate,
            'data' => $data,
            'header_text' => $header_text
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => "0",
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,

        ];
    }
}

// }
