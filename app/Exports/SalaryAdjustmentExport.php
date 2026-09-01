<?php

namespace App\Exports;

use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalaryAdjustmentExport implements
    FromView,
    ShouldAutoSize,
    WithColumnFormatting,
    WithStyles
{
    use Exportable;

    protected $pilihLamaKerja;
    protected $search_placement;

    public function __construct($pilihLamaKerja, $search_placement = null)
    {
        $this->pilihLamaKerja = $pilihLamaKerja;
        $this->search_placement = $search_placement;
    }

    /**
     * Mendapatkan data karyawan
     */
    private function getData()
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Tentukan bulan berdasarkan lama bekerja
        |--------------------------------------------------------------------------
        */

        switch ((string) $this->pilihLamaKerja) {

            case '3':
                $bulan = $now->copy()->startOfMonth()->subMonths(4);
                $tambahanGaji = 100000;
                break;

            case '4':
                $bulan = $now->copy()->startOfMonth()->subMonths(5);
                $tambahanGaji = 200000;
                break;

            case '5':
                $bulan = $now->copy()->startOfMonth()->subMonths(6);
                $tambahanGaji = 300000;
                break;

            case '6':
                $bulan = $now->copy()->startOfMonth()->subMonths(7);
                $tambahanGaji = 400000;
                break;

            case '7':
                $bulan = $now->copy()->startOfMonth()->subMonths(8);
                $tambahanGaji = 500000;
                break;

            default:
                $bulan = $now->copy()->startOfMonth()->subMonths(4);
                $tambahanGaji = 100000;
                break;
        }

        $gajiMinimal = 2200000;
        $gajiRekomendasi = $gajiMinimal + $tambahanGaji;

        /*
        |--------------------------------------------------------------------------
        | Query dasar
        |--------------------------------------------------------------------------
        */

        $query = Karyawan::query()
            ->with([
                'placement',
                'company',
                'department',
                'jabatan',
            ])

            /*
            |--------------------------------------------------------------------------
            | Filter lama bekerja
            |--------------------------------------------------------------------------
            */

            ->where(function ($query) use ($bulan) {

                if ((string) $this->pilihLamaKerja == '7') {

                    $query->whereMonth(
                        'tanggal_bergabung',
                        $bulan->format('m')
                    )
                        ->orWhere(
                            'tanggal_bergabung',
                            '<=',
                            Carbon::now()->subMonths(8)
                        );
                } else {

                    $query->whereMonth(
                        'tanggal_bergabung',
                        $bulan->format('m')
                    );
                }
            })

            /*
            |--------------------------------------------------------------------------
            | Hanya data mulai April 2026
            |--------------------------------------------------------------------------
            */

            ->whereDate(
                'tanggal_bergabung',
                '>=',
                '2026-04-01'
            )

            /*
            |--------------------------------------------------------------------------
            | Gaji
            |--------------------------------------------------------------------------
            */

            ->where(
                'gaji_pokok',
                '<',
                $gajiRekomendasi
            )

            ->where(
                'gaji_pokok',
                '>',
                0
            )

            /*
            |--------------------------------------------------------------------------
            | Metode penggajian
            |--------------------------------------------------------------------------
            */

            ->where(
                'metode_penggajian',
                'Perjam'
            )

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            ->whereIn(
                'status_karyawan',
                [
                    'PKWT',
                    'PKWTT',
                ]
            )

            /*
            |--------------------------------------------------------------------------
            | Exclude department
            |--------------------------------------------------------------------------
            */

            ->whereNotIn(
                'department_id',
                [3, 5]
            );

        /*
        |--------------------------------------------------------------------------
        | FILTER PLACEMENT
        |
        | Ini bagian penting.
        |
        | Kalau search_placement kosong/null:
        | jangan tambahkan where placement.
        |
        |--------------------------------------------------------------------------
        */

        if (
            $this->search_placement !== null &&
            $this->search_placement !== ''
        ) {

            $query->where(
                'placement_id',
                $this->search_placement
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil data
        |--------------------------------------------------------------------------
        */

        return [
            'data' => $query
                ->orderBy('id_karyawan', 'desc')
                ->get(),

            'gaji_rekomendasi' => $gajiRekomendasi,

            'header_text' =>
            'Penyesuaian gaji karyawan yang telah bekerja diatas '
                . $this->pilihLamaKerja
                . ' Bulan',

            'pilihLamaKerja' => $this->pilihLamaKerja,

            'search_placement' => $this->search_placement,

            'today' => Carbon::now(),
        ];
    }

    /**
     * View Excel
     */
    public function view(): View
    {
        $result = $this->getData();

        return view(
            'salary_adjustment_export',
            $result
        );
    }

    /**
     * Format kolom Excel
     */
    public function columnFormats(): array
    {
        return [

            // Gaji Pokok
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,

            // Gaji Rekomendasi
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,

        ];
    }

    /**
     * Style Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [

            // Header utama
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
            ],

            // Header tabel
            3 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                ],
            ],

        ];
    }
}
