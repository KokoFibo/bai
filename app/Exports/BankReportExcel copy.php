<?php

namespace App\Exports;

use Style\Alignment;
use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class BankReportExcel implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
{
    protected $payroll;

    public function __construct(object $payroll)
    {
        $this->payroll = $payroll;
    }



    public function collection()
    {
        return $this->payroll;
    }

    public function title(): string
    {
        return 'Laporan Gaji Karyawan';
    }

    public function headings(): array
    {
        return [
            [
                'Data Karyawan'
            ],
            [
                'Nama',
                'Bank',
                'No. Rekening',
                'Total Pembayaran',
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
