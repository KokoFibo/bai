<?php

namespace App\Exports;


use DateTime;

use App\Models\Payroll;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ExcelDetailReport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    use Exportable;

    protected $data;

    protected  $month, $year;
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            // 'D' => "0",
            'A' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'V' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'X' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'Y' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,

            'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AD' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AE' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AF' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AG' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AH' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AI' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AJ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AK' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AL' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AM' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AN' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            // 'AS' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AT' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AU' => "0",
            'AV' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            2    => ['font' => ['bold' => true]],
            // Styling a specific cell by coordinate.

            // Styling an entire column.
            2  => ['font' => ['size' => 15]],
            // 2 => ['font' => ['italic' => true]],
            3  => ['font' => ['size' => 12]],


        ];
    }

    public function view(): View
    {
        $data = Payroll::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->select('placement_id', 'company_id', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total) as total'))
            ->groupBy('placement_id', 'company_id')
            ->get()
            ->groupBy('placement_id');

        $totalStaff = $data->flatten()->sum('jumlah');
        $totalAmount = $data->flatten()->sum('total');

        // start 

        // $tahun = now()->year;
        $tahun = $this->year;

        // Ambil data dari Januari hingga bulan ini
        $laporan_bulanan = collect();
        // foreach (range(1, now()->month) as $bulan) {
        //     $laporan = DB::table('payrolls')
        //         ->selectRaw('placement_id, SUM(total) as total_gaji, COUNT(DISTINCT id_karyawan) as jumlah_karyawan, 
        //         SUM(tambahan_shift_malam) as tambahan_shift_malam,
        //         SUM(jam_kerja) as jam_kerja,  
        //         SUM(jam_lembur) as jam_lembur, 
        //         SUM(bonus1x) as bonus1x, 
        //         SUM(potongan1x) + sum(denda_lupa_absen) + sum(denda_resigned)+ sum(iuran_air) as potongan1x,
        //         SUM(gaji_pokok/198*jam_kerja) as total_gaji_pokok,
        //         SUM(gaji_lembur*jam_lembur) as total_lemburan
        //         ')
        //         ->where('metode_penggajian', 'Perjam')
        //         ->whereYear('date', $tahun)
        //         ->whereMonth('date', $bulan)
        //         ->groupBy('placement_id')
        //         ->get()
        //         ->map(function ($row) use ($bulan) {
        //             return (object)[
        //                 'bulan' => $bulan,
        //                 'placement_id' => $row->placement_id,
        //                 'total_gaji' => $row->total_gaji,
        //                 'jumlah_karyawan' => $row->jumlah_karyawan,
        //                 'tambahan_shift_malam' => $row->tambahan_shift_malam,
        //                 'jam_kerja' => $row->jam_kerja,
        //                 'jam_lembur' => $row->jam_lembur,
        //                 'bonus1x' => $row->bonus1x,
        //                 'potongan1x' => $row->potongan1x,
        //                 'total_gaji_pokok' => $row->total_gaji_pokok,
        //                 'total_lemburan' => $row->total_lemburan,
        //                 // 'rata_rata_gaji' => $row->total_gaji_pokok / $row->total_gaji,
        //                 'rata_rata_gaji' => $row->jumlah_karyawan > 0
        //                     ? $row->total_gaji_pokok / $row->jumlah_karyawan
        //                     : 0,

        //                 'rata_rata_gaji_perjam' => $row->jam_kerja > 0
        //                     ? $row->total_gaji_pokok / $row->jam_kerja
        //                     : 0,

        //                 'rata_rata_lembur_perjam' => $row->jam_lembur > 0
        //                     ? ($row->total_lemburan ?? 0) / $row->jam_lembur
        //                     : 0,
        //             ];
        //         });

        //     $laporan_bulanan = $laporan_bulanan->merge($laporan);
        // }

        foreach (range(1, now()->month) as $bulan) {
            $laporan = DB::table('payrolls')
                ->join('placements', 'payrolls.placement_id', '=', 'placements.id')
                ->selectRaw('placements.placement_name as placement_name, placement_id, 

                    SUM(total) as total_gaji, 
                    COUNT(DISTINCT id_karyawan) as jumlah_karyawan, 
                    SUM(tambahan_shift_malam) as tambahan_shift_malam,
                    SUM(jam_kerja) as jam_kerja,  
                    SUM(jam_lembur) as jam_lembur, 
                    SUM(bonus1x) as bonus1x, 
                    SUM(potongan1x) + SUM(denda_lupa_absen) + SUM(denda_resigned) + SUM(iuran_air) as potongan1x,
                    SUM(gaji_pokok/198*jam_kerja) as total_gaji_pokok,
                    SUM(gaji_lembur*jam_lembur) as total_lemburan')
                ->where('metode_penggajian', 'Perjam')
                ->whereYear('date', $tahun)
                ->whereMonth('date', $bulan)
                ->groupBy('placement_id', 'placements.placement_name')
                ->get()
                ->map(function ($row) use ($bulan) {
                    return (object)[
                        'bulan' => $bulan,
                        'placement_id' => $row->placement_id,
                        'placement_name' => $row->placement_name,
                        'total_gaji' => $row->total_gaji,
                        'jumlah_karyawan' => $row->jumlah_karyawan,
                        'tambahan_shift_malam' => $row->tambahan_shift_malam,
                        'jam_kerja' => $row->jam_kerja,
                        'jam_lembur' => $row->jam_lembur,
                        'bonus1x' => $row->bonus1x,
                        'potongan1x' => $row->potongan1x,
                        'total_gaji_pokok' => $row->total_gaji_pokok,
                        'total_lemburan' => $row->total_lemburan,
                        'rata_rata_gaji' => $row->jumlah_karyawan > 0
                            ? $row->total_gaji_pokok / $row->jumlah_karyawan
                            : 0,
                        'rata_rata_gaji_perjam' => $row->jam_kerja > 0
                            ? $row->total_gaji_pokok / $row->jam_kerja
                            : 0,
                        'rata_rata_lembur_perjam' => $row->jam_lembur > 0
                            ? ($row->total_lemburan ?? 0) / $row->jam_lembur
                            : 0,
                    ];
                });

            $laporan_bulanan = $laporan_bulanan->merge($laporan);
        }

        // Sort final data berdasarkan placement_name
        // $laporan_bulanan = $laporan_bulanan->sortBy('placement_name')->values();

        $laporan_bulanan = $laporan_bulanan->sortBy([
            ['bulan', 'desc'],
            ['placement_name', 'asc'],
        ])->values();

        // return view('livewire.laporan', [
        return view('payroll_excel_detail_report_view', [
            'month' => $this->month,
            'year' => $this->year,
            'data' => $data,
            'totalStaff' => $totalStaff,
            'totalAmount' => $totalAmount,
            'laporan_bulanan' => $laporan_bulanan,
        ]);
    }
}
