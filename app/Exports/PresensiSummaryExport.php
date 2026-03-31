<?php

namespace App\Exports;

use Style\Alignment;
use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

// class BankReportExcel implements FromCollection, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles
class PresensiSummaryExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    use Exportable;

    protected $selected_company, $year, $month;
    public function __construct($selected_company, $year, $month)
    {
        $this->selected_company = $selected_company;
        $this->year = $year;
        $this->month = $month;
       
    }

    public function query()
    {
        $status = 1;
        if ($status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        if ($status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        switch ($this->selected_company) {
            
            case 0:
                return Payroll::whereIn('status_karyawan', $statuses)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year); 
                break;

            case 1:
                return Payroll::whereIn('status_karyawan', $statuses)
                ->where('placement', 'YCME')
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);
                break;

            case 2:
                return Payroll::whereIn('status_karyawan', $statuses)->where('placement', 'YEV')
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);
                break;

            case 3:
                return Payroll::whereIn('status_karyawan', $statuses)->whereIn('placement', ['YIG', 'YSM'])
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);

                break;

            case 4:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'ASB')
                    ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);

                break;

            case 5:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'DPA')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year);

                break;

            case 6:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YCME')
                    ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);

                break;

            case 7:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YEV')
                    ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year);

                break;

            case 8:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YIG')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year);

                break;

            case 9:
                return Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company', 'YSM')
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year);

                break;
        }
    }

    public function map($payroll): array
    {
        return [$payroll->id_karyawan, $payroll->nama,  $payroll->jabatan, $payroll->company, $payroll->placement, 
        $payroll->metode_penggajian,  $payroll->status_karyawan, $payroll->hari_kerja, $payroll->jam_kerja, $payroll->jam_lembur, 
        $payroll->jumlah_jam_terlambat, $payroll->tambahan_jam_shift_malam, 
        $payroll->total_noscan, 
        
    ];
    }

    public function headings(): array
    {
        return [['Summary Presensi'], ['ID Karyawan', 'Nama', 'Jabatan', 'Company', 'Placement', 
        'Metode Penggajian','Status Karyawan', 'Total Hari Kerja', 'Total Jam Kerja (bersih)', 'Jam Lembur', 
        'Jumlah Jam Terlambat', 'Tambahan Jam Shift Malam',
        'Total No Scan'
         , 
         ]];
    }

    public function title(): string
    {
        return 'Summary Presensi';
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => '0',
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
           
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            '1' => ['font' => ['bold' => true]],
            '2' => ['font' => ['bold' => true]],
            '1' => ['font' => ['size' => 24]],
            // 'C' => ['text' => ['align' => 'center']],
        ];
        // atau bisa juga
        // $sheet->getStyle('1')->getFont()->setBold(true);
        // $sheet->getStyle('2')->getFont()->setBold(true);
    }
}
