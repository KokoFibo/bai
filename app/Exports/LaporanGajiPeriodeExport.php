<?php

namespace App\Exports;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanGajiPeriodeExport implements
    FromArray,
    WithHeadings,
    WithColumnFormatting,
    ShouldAutoSize,
    WithEvents,
    WithCustomStartCell
{
    protected $data;
    protected $months;
    protected $title;

    public function __construct($data, $months, $title)
    {
        $this->data = $data;
        $this->months = $months;
        $this->title = $title;
    }

    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    public function array(): array
    {
        $result = [];

        foreach ($this->data as $row) {

            $line = [
                $row['id'] ?? '',
                $row['nama'] ?? '',
                $row['company'] ?? '',
                $row['placement'] ?? '',
            ];

            foreach ($this->months as $m) {
                $line[] = isset($row[$m]) ? (int) $row[$m] : 0;
            }

            $result[] = $line;
        }

        return $result;
    }

    /*
    |--------------------------------------------------------------------------
    | Header Mulai dari Baris 2
    |--------------------------------------------------------------------------
    */

    public function startCell(): string
    {
        return 'A2';
    }

    public function headings(): array
    {
        $headers = ['ID Karyawan', 'Nama', 'Company', 'Directorate'];

        foreach ($this->months as $m) {
            $headers[] = Carbon::createFromFormat('Y-m', $m)
                ->translatedFormat('M Y');
        }

        return $headers;
    }

    /*
    |--------------------------------------------------------------------------
    | Format Angka
    |--------------------------------------------------------------------------
    */

    public function columnFormats(): array
    {
        $formats = [];

        $startColumnIndex = 5; // E

        foreach ($this->months as $index => $month) {

            $columnLetter = Coordinate::stringFromColumnIndex(
                $startColumnIndex + $index
            );

            // Format angka ribuan tanpa desimal
            $formats[$columnLetter] = '#,##0';
        }

        return $formats;
    }

    /*
    |--------------------------------------------------------------------------
    | Judul di Baris 1
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                if (empty($this->months)) {
                    return;
                }

                $startPeriod = Carbon::createFromFormat('Y-m', $this->months[0])
                    ->translatedFormat('M Y');

                $endPeriod = Carbon::createFromFormat('Y-m', end($this->months))
                    ->translatedFormat('M Y');

                // $title = "Tabel Perubahan Gaji Karyawan Periode {$startPeriod} sampai {$endPeriod}";
                $title = $this->title . "Periode {$startPeriod} sampai {$endPeriod}";

                $totalColumns = 2 + count($this->months);
                $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);

                // Merge cell untuk judul
                $event->sheet->mergeCells("A1:{$lastColumn}1");

                // Set judul
                $event->sheet->setCellValue('A1', $title);

                // Styling
                $event->sheet->getStyle("A1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
