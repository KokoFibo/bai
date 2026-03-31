<?php

namespace App\Exports;

use Style\Alignment;
use App\Models\Datas;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class ExcelTanpaContactDarurat implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
{
    protected $datas;

    public function __construct(object $datas)
    {
        $this->datas = $datas;
    }



    public function collection()
    {
        return $this->datas;
    }

    public function title(): string
    {
        return 'Data Karyawan Aktif Tanpa Kontak Darurat';
    }

    public function headings(): array
    {
        return [
            [
                'Data Karyawan'
            ],
            [
                'ID',
                'Nama',
                'Email',
                'HP',
                'Telepon',
                'Company',
                'Placement',
                'Department',
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'C' => "0",
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }



    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
            '2' => ['font' => ['bold' => true]],
            '1' => ['font' => ['size' => 24]],
            'C' => ['text' => ['align' => 'center']],
        ];
        // atau bisa juga
        // $sheet->getStyle('1')->getFont()->setBold(true);
        // $sheet->getStyle('2')->getFont()->setBold(true);

    }
}
