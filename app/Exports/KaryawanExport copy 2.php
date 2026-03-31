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

class KaryawanExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $selected_placement, $selected_company, $selectStatus;

    public function __construct($selected_placement, $selected_company, $selectStatus)
    {
        $this->selected_placement = $selected_placement;
        $this->selected_company = $selected_company;
        $this->selectStatus = $selectStatus;
    }

    public function view(): View
    {

        if ($this->selectStatus == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        } elseif ($this->selectStatus == 2) {
            $statuses = ['Blacklist', 'Resigned'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }
        $data = Karyawan::whereIn('status_karyawan', $statuses);

        if ($this->selected_placement) {
            $data = $data->where('placement', $this->selected_placement);
        }

        if ($this->selected_company) {
            $data = $data->where('company_id', $this->selected_company);
        }

        $data = $data->get();

        // lllllllllllllllllllllllllllllllllllllllllllllllll


        // if ($this->selected_departemen != 0) {
        //     $header_text = 'Perincian Payroll untuk Department ' . $this->selected_departemen . ' ' . nama_bulan($this->month) . ' ' . $this->year;
        // } else {
        //     $header_text = 'Seluruh Perincian Payroll ' .  nama_bulan($this->month) . ' ' . $this->year;
        // }
        $placement = $this->selected_placement;
        $company = nama_company($this->selected_company);

        if ($placement && $company) {
            $header_text = "Excel Karyawan Placement $placement, Company $company";
        } elseif ($placement) {
            $header_text = "Excel Karyawan Placement $placement";
        } elseif ($company) {
            $header_text = "Excel Karyawan Company $company";
        } else {
            $header_text = 'Excel Seluruh Karyawan';
        }



        return view('karyawan_excel_view', [
            'data' => $data,
            'header_text' => $header_text
        ]);
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
        return Karyawan::whereIn('status_karyawan', $statuses)
            ->where('placement', 'selected_placement')
            ->where('company_id', 'selected_company');
    }
    //map
    // public function map($karyawan): array
    // {
    //     return [
    //         $karyawan->id_karyawan, $karyawan->nama, $karyawan->company, $karyawan->placement, $karyawan->jabatan->nama_jabatan,
    //         $karyawan->status_karyawan, $karyawan->tanggal_bergabung, $karyawan->metode_penggajian, $karyawan->gaji_pokok, $karyawan->gaji_overtime, $karyawan->gaji_bpjs
    //     ];
    // }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => '0',

            'H' => NumberFormat::FORMAT_DATE_XLSX15,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,


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
