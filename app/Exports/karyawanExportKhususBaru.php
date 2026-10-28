<?php

namespace App\Exports;

use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class karyawanExportKhususBaru implements
    FromView,
    ShouldAutoSize,
    WithColumnFormatting,
    WithStyles
{
    protected $semuaData;
    protected $bulan;
    protected $tahun;

    public function __construct($semuaData, $bulan, $tahun)
    {
        $this->semuaData = $semuaData;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $query = Karyawan::query();

        $query->where(function ($q) {
            // Semua karyawan dengan status ini selalu ditampilkan.
            $q->whereIn('status_karyawan', [
                'PKWT',
                'PKWTT',
                'Dirumahkan',
            ]);

            if ($this->semuaData) {
                // Semua karyawan Resigned dan Blacklist ditampilkan.
                $q->orWhereIn('status_karyawan', [
                    'Resigned',
                    'Blacklist',
                ]);
            } else {
                // Menentukan tanggal awal berdasarkan bulan dan tahun.
                $tanggalMulai = Carbon::create(
                    (int) $this->tahun,
                    (int) $this->bulan,
                    1
                )->startOfMonth()->toDateString();

                // Filter Resigned berdasarkan tanggal_resigned.
                $q->orWhere(function ($resigned) use ($tanggalMulai) {
                    $resigned
                        ->where('status_karyawan', 'Resigned')
                        ->whereNotNull('tanggal_resigned')
                        ->where(
                            'tanggal_resigned',
                            '>=',
                            $tanggalMulai
                        );
                });

                // Filter Blacklist berdasarkan tanggal_blacklist.
                $q->orWhere(function ($blacklist) use ($tanggalMulai) {
                    $blacklist
                        ->where('status_karyawan', 'Blacklist')
                        ->whereNotNull('tanggal_blacklist')
                        ->where(
                            'tanggal_blacklist',
                            '>=',
                            $tanggalMulai
                        );
                });
            }
        });

        // Mengambil seluruh data yang memenuhi filter.
        $data = $query->get();

        // Menambahkan tanda petik satu di depan nomor rekening.
        foreach ($data as $karyawan) {
            if (
                $karyawan->nomor_rekening !== null &&
                $karyawan->nomor_rekening !== ''
            ) {
                $karyawan->nomor_rekening =
                    "'" . $karyawan->nomor_rekening;
            }
        }

        // Menentukan judul laporan.
        $header_text = $this->semuaData
            ? 'Data Seluruh Karyawan'
            : 'Data Karyawan Mulai ' .
            Carbon::create(
                (int) $this->tahun,
                (int) $this->bulan,
                1
            )->translatedFormat('F Y');

        // Mengirim data ke template Excel.
        return view('karyawan_excel_view_khusus_baru', [
            'data' => $data,
            'header_text' => $header_text,
        ]);
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => '0',
            'Q' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 24,
                ],
            ],
            2 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
