<?php

namespace App\Exports;


use Carbon\Carbon;
use App\Models\Karyawan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class SalaryAdjustmentExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles

{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    protected $data;

    protected $pilihLamaKerja, $search_placement;
    public function __construct($pilihLamaKerja, $search_placement)
    {
        $this->pilihLamaKerja = $pilihLamaKerja;
        $this->search_placement = $search_placement;
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
        $bulan3 = Carbon::now()->startOfMonth()->subMonths(4);
        $bulan4 = Carbon::now()->startOfMonth()->subMonths(5);
        $bulan5 = Carbon::now()->startOfMonth()->subMonths(6);
        $bulan6 = Carbon::now()->startOfMonth()->subMonths(7);
        $bulan7 = Carbon::now()->startOfMonth()->subMonths(8);
        $bulan8 = Carbon::now()->startOfMonth()->subMonths(9);
        $bulan9 = Carbon::now()->startOfMonth()->subMonths(10);
        switch ($this->pilihLamaKerja) {
            case "3":


                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('department_id', [3, 5])
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement_id', $this->search_placement);
                    })
                    ->get();


                break;

            case "4":



                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('department_id', [3, 5])
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement_id', $this->search_placement);
                    })
                    ->get();



                break;

            case "5":


                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('department_id', [3, 5])
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement_id', $this->search_placement);
                    })
                    ->get();
                break;

            case "6":


                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('department_id', [3, 5])
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement_id', $this->search_placement);
                    })
                    ->get();
                break;

            case "7":


                $data = Karyawan::where(function ($query) use ($bulan7) {
                    $query->whereMonth('tanggal_bergabung', $bulan7->format('m'))
                        ->orWhere('tanggal_bergabung', '<=', Carbon::now()->subMonths(8));
                })
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->where('metode_penggajian', 'Perjam')

                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('department_id', [3, 5])
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement_id', $this->search_placement);
                    })
                    ->get();
                break;
        }
        $header_text = 'Penyesuaian gaji karyawan yang telah bekerja diatas ' . $this->pilihLamaKerja . ' Bulan';

        return view('salary_adjustment_view', [
            'data' => $data,
            'header_text' => $header_text
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'K' => "0"
        ];
    }
}
