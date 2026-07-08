<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;



class SlipGajiExport implements
    FromView,
    WithStyles,
    WithTitle,
    WithColumnFormatting,
    WithEvents
{
    protected Payroll $payroll;

    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
    }

    public function view(): View
    {
        return view(
            'exports.slip-gaji',
            [
                'payroll'      => $this->payroll,

                'periode'      => $this->getPeriode(),

                'informasi'    => $this->getInformasi(),

                'penerimaan'   => $this->getPenerimaan(),

                'potongan'     => $this->getPotongan(),

                'rupiah'       => fn($angka) => $this->formatRupiah($angka),
            ]
        );
    }

    public function title(): string
    {
        return 'Slip Gaji';
    }

    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->getDefaultRowDimension()
    //         ->setRowHeight(20);

    //     $sheet->getDefaultColumnDimension()
    //         ->setWidth(18);

    //     return [];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // ==========================
                // AUTO WIDTH COLUMN
                // ==========================
                foreach ($sheet->getColumnIterator() as $column) {

                    $columnLetter = $column->getColumnIndex();

                    $maxLength = 0;

                    foreach ($sheet->getRowIterator() as $row) {

                        $cell = $sheet->getCell(
                            $columnLetter . $row->getRowIndex()
                        );

                        $value = $cell->getValue();

                        if ($value !== null) {

                            // Convert value menjadi string
                            $value = (string) $value;

                            $length = mb_strlen($value);

                            if ($length > $maxLength) {
                                $maxLength = $length;
                            }
                        }
                    }


                    // Lebar minimum 12
                    // Lebar maksimum 35
                    $width = max($maxLength + 3, 12);

                    $sheet->getColumnDimension($columnLetter)
                        ->setWidth(min($width, 35));
                }


                // ==========================
                // WRAP TEXT UNTUK COLSPAN / MERGE
                // ==========================
                $sheet->getStyle(
                    $sheet->calculateWorksheetDimension()
                )
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    );


                // ==========================
                // ROW HEIGHT
                // ==========================
                $sheet->getDefaultRowDimension()
                    ->setRowHeight(20);


                // ==========================
                // CENTER UNTUK HEADER
                // ==========================
                $sheet->getStyle('A1:D5')
                    ->getAlignment()
                    ->setHorizontal(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    );
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()
            ->setRowHeight(20);

        return [];
    }
    public function getPeriode(): string
    {
        return \Carbon\Carbon::parse(
            $this->payroll->date
        )->translatedFormat('F Y');
    }
    // public function formatRupiah($angka): string
    // {
    //     return $angka;
    // }
    // public function formatRupiah($angka)
    // {
    //     return   $angka;
    //     // return 'Rp ' . number_format(
    //     //     $angka ?? 0,
    //     //     0,
    //     //     ',',
    //     //     '.'
    //     // );
    // }
    public function getInformasi(): array
    {
        return [

            'Nama'             => $this->payroll->nama,

            // 'ID Karyawan'      => $this->payroll->id_karyawan,

            // 'Perusahaan'       => $this->payroll->company_name,

            // 'Placement'        => $this->payroll->placement_name,

            // 'Department'       => $this->payroll->nama_department,

            'Jabatan'          => $this->payroll->nama_jabatan,

            'Status'           => $this->payroll->status_karyawan,

            // 'Hari Kerja'       => $this->payroll->hari_kerja,

            // 'Jam Kerja'        => $this->payroll->jam_kerja,

            // 'Jam Lembur'       => $this->payroll->jam_lembur,

            // 'Bank'             => $this->payroll->nama_bank,

            // 'No Rekening'      => $this->payroll->nomor_rekening,

            'PTKP'      => $this->payroll->ptkp,

            'Total Hari Kerja'      => $this->payroll->hari_kerja,

        ];
    }

    public function hitung_tunjangan_bpjs($gaji_bpjs, $kesehatan, $jkk, $jkm, $jp, $jht)
    {
        if ($gaji_bpjs >= 12000000) {
            $gaji_bpjs_max = 12000000;
        } else {
            $gaji_bpjs_max = $gaji_bpjs;
        }

        if ($gaji_bpjs >= 11086300) {
            $gaji_jp_max = 11086300;
        } else {
            $gaji_jp_max = $gaji_bpjs;
        }
        if ($kesehatan != 0) {
            $kesehatan_company = ($gaji_bpjs_max * 4) / 100;
        } else {
            $kesehatan_company = 0;
        }

        if ($jkk) {
            $jkk_company = ($gaji_bpjs * 0.89) / 100;
        } else {
            $jkk_company = 0;
        }

        if ($jkm) {
            $jkm_company = ($gaji_bpjs * 0.3) / 100;
        } else {
            $jkm_company = 0;
        }

        if ($jp != 0) {
            $jp_company = ($gaji_jp_max * 2) / 100;
        } else {
            $jp_company = 0;
        }

        if ($jht != 0) {
            $jht_company = ($gaji_bpjs * 3.7) / 100;
        } else {
            $jht_company = ($gaji_bpjs * 3.7) / 100;
        }
        return $jkk_company + $jkm_company + $kesehatan_company + $jp_company + $jht_company;
    }
    public function hitung_tunjangan_bpjs_tak_dihitung($gaji_bpjs,  $jp, $jht)
    {
        if ($gaji_bpjs >= 12000000) {
            $gaji_bpjs_max = 12000000;
        } else {
            $gaji_bpjs_max = $gaji_bpjs;
        }

        if ($gaji_bpjs >= 11086300) {
            $gaji_jp_max = 11086300;
        } else {
            $gaji_jp_max = $gaji_bpjs;
        }




        if ($jp != 0) {
            $jp_company = ($gaji_jp_max * 2) / 100;
        } else {
            $jp_company = 0;
        }

        if ($jht != 0) {
            $jht_company = ($gaji_bpjs * 3.7) / 100;
        } else {
            $jht_company = ($gaji_bpjs * 3.7) / 100;
        }
        return $jp_company + $jht_company;
    }
    public function getPenerimaan(): array
    {
        $tunjangan_bpjs = 0;
        $tunjangan_bpjs = $this->hitung_tunjangan_bpjs(
            $this->payroll->gaji_bpjs,
            $this->payroll->kesehatan,
            $this->payroll->jkk,
            $this->payroll->jkm,
            $this->payroll->jp,
            $this->payroll->jht
        );

        $bpjs_tak_dihitung = $this->hitung_tunjangan_bpjs_tak_dihitung(
            $this->payroll->gaji_bpjs,
            $this->payroll->jp,
            $this->payroll->jht
        );

        return [

            // 'Gaji Pokok'               => $this->payroll->gaji_pokok,
            'Gaji Pokok'               => $this->payroll->gaji_bulan_ini,

            'Tunjangan Makan'                => 1300000,

            'Tunjangan BPJS'                => $tunjangan_bpjs,

            'Lembur'              => $this->payroll->gaji_lembur * $this->payroll->jam_lembur,

            'Bonus / THR'                    => $this->payroll->bonus1x + $this->payroll->thr,

            'Penerimaan Lain'        => 0,

            'BPJS tak dihitung' =>  $bpjs_tak_dihitung,

        ];
    }
    public function getPotongan(): array
    {
        return [

            'Iuran BPJS Kesehatan'     => $this->payroll->kesehatan,

            'Iuran BPJS Ketenagakerjaan'     => $this->payroll->kesehatan,

            'PPH21'              => $this->payroll->pph21,

            'Potongan Keterlambatan'      => 0,

            'Denda Resign'     => $this->payroll->denda_resigned,

            'Potongan Lainnya' => $this->payroll->potongan1x ?? 0,

        ];
    }
    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => "0",
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED


        ];
    }
}
