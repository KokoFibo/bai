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

class DepartmentExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    use Exportable;
    protected $selected_departemen, $status, $month, $year;
    public function __construct($selected_departemen, $status, $month, $year)
    {
        $this->selected_departemen = $selected_departemen;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
    }

    public function query()
    {
        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        if ($this->selected_departemen == 0) {
            return Payroll::whereIn('status_karyawan', $statuses)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('id_karyawan', 'asc');
        } else {
            return Payroll::whereIn('status_karyawan', $statuses)
                ->where('departemen', $this->selected_departemen)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('id_karyawan', 'asc');
        }
    }

    public function map($payroll): array
    {
        return [
            $payroll->id_karyawan,
            $payroll->nama,
            $payroll->nama_bank,
            $payroll->nomor_rekening,
            $payroll->jabatan,
            $payroll->company,
            $payroll->placement,
            $payroll->departemen,
            $payroll->metode_penggajian,
            $payroll->hari_kerja,
            $payroll->jam_kerja,
            $payroll->jam_lembur,
            $payroll->jumlah_jam_terlambat,
            $payroll->tambahan_shift_malam,
            $payroll->gaji_pokok,
            $payroll->gaji_lembur,
            $payroll->gaji_libur,

            $payroll->gaji_bpjs,
            $payroll->bonus1x,
            $payroll->potongan1x,
            $payroll->total_noscan,
            $payroll->denda_lupa_absen,
            $payroll->denda_resigned,
            $payroll->pajak,
            $payroll->jht,
            $payroll->jp,
            $payroll->jkk,
            $payroll->jkm,
            $payroll->kesehatan,
            $payroll->tanggungan,
            $payroll->iuran_air,
            $payroll->iuran_locker,
            $payroll->status_karyawan,
            $payroll->total,

        ];
    }

    public function headings(): array
    {
        return [
            [
                'Payroll'
            ],
            [
                'ID Karyawan',
                'Nama',
                'Nama Bank',
                'No. Rekening',
                'Jabatan',
                'Company',
                'Placement',
                'Department',
                'Metode Penggajian',
                'Total Hari Kerja',
                'Total Jam Kerja (bersih)',
                'Jam Lembur',
                'Jumlah Jam Terlambat',
                'Tambahan Shift Malam',
                'Gaji Pokok',
                'Gaji Lembur',
                'Gaji Libur',
                'Gaji BPJS',
                'Bonus/U. Makan',
                'Potongan 1X Potong',
                'Total No Scan',
                'Denda Lupa Absen',
                'Denda Resigned',
                'Pajak',
                'JHT',
                'JP',
                'JKK',
                'JKM',
                'Kesehatan',
                'Tanggungan',
                'Iuran Air',
                'Iuran Locker',
                'Status Karyawan',
                'Total',

            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Gaji Karyawan';
    }


    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'D' => "0",
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'V' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'X' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Y' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Z' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AD' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AE' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AF' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'AH' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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
