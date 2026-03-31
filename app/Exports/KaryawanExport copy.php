<?php

namespace App\Exports;

use App\Models\Karyawan;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Style\Alignment;
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

class KaryawanExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $selected_company, $selectStatus;

    public function __construct($selected_company, $selectStatus)
    {
        $this->selected_company = $selected_company;
        $this->selectStatus = $selectStatus;
    }

    public function query()
    {
        if ($this->selectStatus == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        } elseif ($this->selectStatus == 2) {
            $statuses = ['Blacklist', 'Resigned'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        switch ($this->selected_company) {
            case 0:
                return Karyawan::whereIn('status_karyawan', $statuses);
                break;

            case 1:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('placement', 'YCME');
                break;

            case 2:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('placement', 'YEV');
                break;

            case 3:
                return Karyawan::whereIn('status_karyawan', $statuses)->whereIn('placement', ['YIG', 'YSM']);
                break;

            case 4:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'ASB');
                break;

            case 5:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'DPA');
                break;

            case 6:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'YCME');
                break;

            case 7:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'YEV');
                break;

            case 8:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'YIG');
                break;

            case 9:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'YSM');
                break;
            case 10:
                return Karyawan::whereIn('status_karyawan', $statuses)->where('company', 'YAM');
                break;
        }
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->id_karyawan, $karyawan->nama, $karyawan->company, $karyawan->placement, $karyawan->jabatan->nama_jabatan,
            $karyawan->status_karyawan, $karyawan->tanggal_bergabung, $karyawan->metode_penggajian, $karyawan->gaji_pokok, $karyawan->gaji_overtime, $karyawan->gaji_bpjs
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => '0',

            'G' => NumberFormat::FORMAT_DATE_XLSX15,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,


        ];
    }

    public function headings(): array
    {
        return [['Data Karyawan'], [
            'ID Karyawan', 'Nama', 'Company', 'Placement', 'Jabatan',
            'Status Karyawan', 'Tanggal Bergabung', 'Metode Penggajian', 'Gaji Pokok', 'Gaji Lembur', 'Gaji BPJS',
        ]];
    }

    public function title(): string
    {
        return 'Data Karyawan';
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
