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

class KaryawanByDepartmentExport implements FromQuery, WithHeadings, WithColumnFormatting, ShouldAutoSize, WithTitle, WithStyles, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    // protected $search_nama, $search_id_karyawan, $search_department, $search_placement,  $search_jabatan, $search_etnis;
    protected $search_nama, $search_id_karyawan, $search_company, $search_placement, $search_department, $search_jabatan, $search_etnis;

    public function __construct($search_nama,   $search_id_karyawan, $search_company, $search_placement, $search_department, $search_jabatan, $search_etnis)
    {


        $this->search_nama = $search_nama;
        $this->search_id_karyawan = $search_id_karyawan;
        $this->search_company = $search_company;
        $this->search_placement = $search_placement;
        $this->search_department = $search_department;
        $this->search_jabatan = $search_jabatan;
        $this->search_etnis = $search_etnis;

        // dd($this->search_etnis);
    }
    // public function __construct($search_nama, $search_id_karyawan, $search_placement, $search_department, $search_company, $search_jabatan, $search_etnis)
    // {

    //     $this->search_nama = $search_nama;
    //     $this->search_id_karyawan = $search_id_karyawan;
    //     $this->search_department = $search_department;
    //     $this->search_placement = $search_placement;
    //     $this->search_company = $search_company;
    //     $this->search_jabatan = $search_jabatan;
    //     $this->search_etnis = $search_etnis;
    //     dd($this->search_company);
    // }



    public function query()
    {
        $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        return Karyawan::query()

            ->whereIn('status_karyawan', $statuses)
            ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
            ->when($this->search_id_karyawan, function ($query) {
                $query->where('id_karyawan', trim($this->search_id_karyawan));
            })

            ->when($this->search_company, function ($query) {
                $query->where('company', $this->search_company);
            })

            ->when($this->search_placement, function ($query) {
                if ($this->search_placement == 1) {
                    $query->where('placement', 'YCME');
                } elseif ($this->search_placement == 2) {
                    $query->where('placement', 'YEV');
                } elseif ($this->search_placement == 4) {
                    $query->where('placement', 'YIG');
                } elseif ($this->search_placement == 5) {
                    $query->where('placement', 'YSM');
                } elseif ($this->search_placement == 6) {
                    $query->where('placement', 'YAM');
                } elseif ($this->search_placement == 7) {
                    $query->where('placement', 'YEV SMOOT');
                } elseif ($this->search_placement == 8) {
                    $query->where('placement', 'YEV OFFERO');
                } elseif ($this->search_placement == 9) {
                    $query->where('placement', 'YEV SUNRA');
                } elseif ($this->search_placement == 10) {
                    $query->where('placement', 'YEV AIMA');
                } else {
                    $query->whereIn('placement', ['YIG', 'YSM']);
                }
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan', $this->search_jabatan);
            })
            ->when($this->search_etnis, function ($query) {
                if ($this->search_etnis == 'kosong') {
                    $query->where('etnis', null)->orWhere('etnis', '');
                } else {
                    $query->where('etnis', $this->search_etnis);
                }
            })
            ->when($this->search_department, function ($query) {
                $query->where('departemen', trim($this->search_department));
            })

            ->orderBy('nama', 'asc');
    }

    public function map($karyawan): array
    {
        return [
            $karyawan->id_karyawan, $karyawan->nama, $karyawan->company, $karyawan->placement, $karyawan->departemen, $karyawan->jabatan, $karyawan->etnis,
            $karyawan->status_karyawan, $karyawan->tanggal_bergabung, $karyawan->metode_penggajian, $karyawan->gaji_pokok, $karyawan->gaji_overtime, $karyawan->gaji_bpjs,
            $karyawan->nama_bank, $karyawan->nomor_rekening
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => '0',

            'G' => NumberFormat::FORMAT_DATE_XLSX15,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'O' => "0",


        ];
    }

    public function headings(): array
    {
        return [['Data Karyawan'], [
            'ID Karyawan', 'Nama', 'Company', 'Placement', 'Department', 'Jabatan', 'Etnis',
            'Status Karyawan', 'Tanggal Bergabung', 'Metode Penggajian', 'Gaji Pokok', 'Gaji Lembur', 'Gaji BPJS', 'Bank', 'No rekening'
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
