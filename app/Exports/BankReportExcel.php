<?php

namespace App\Exports;

use App\Models\Payroll;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BankReportExcel implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    use Exportable;

    protected $data;

    // ($this->status, $this->month, $this->year, $nama_file, $this->selected_company, $this->selected_placement, $this->selected_departemen))

    protected $status, $month, $year, $company_id, $placement_id, $department_id;
    public function __construct($status, $month, $year,  $company_id, $placement_id, $department_id)
    {
        $this->company_id = $company_id;
        $this->placement_id = $placement_id;
        $this->department_id = $department_id;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
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

        if ($this->company_id == 0 && $this->placement_id == 0 && $this->department_id == 0) {
            $data = Payroll::whereIn('status_karyawan', $statuses)
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->orderBy('id_karyawan', 'asc')->get();
            $header_text = 'Perincian Payroll ' .  nama_bulan($this->month) . ' ' . $this->year;
        } else {


            if ($this->company_id) {
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('company_id', $this->company_id)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                $header_text = 'Perincian Payroll untuk Company ' . nama_company($this->company_id) . ' ' .  nama_bulan($this->month) . ' ' . $this->year;
            } else if ($this->placement_id) {
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('placement_id', $this->placement_id)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                $header_text = 'Perincian Payroll untuk Directorate ' . nama_placement($this->placement_id) . ' ' .  nama_bulan($this->month) . ' ' . $this->year;
            } else  if ($this->department_id) {
                $data = Payroll::whereIn('status_karyawan', $statuses)
                    ->where('department_id', $this->department_id)
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')->get();
                $header_text = 'Perincian Payroll untuk Department ' . nama_department($this->department_id) . ' ' .  nama_bulan($this->month) . ' ' . $this->year;
            }
        }




        // if ($this->selected_placement == 0) {
        //     $data = Payroll::whereIn('status_karyawan', $statuses)
        //         ->whereMonth('date', $this->month)
        //         ->whereYear('date', $this->year)
        //         ->orderBy('id_karyawan', 'asc')->get();
        // } else {
        //     $data = Payroll::whereIn('status_karyawan', $statuses)
        //         ->where('placement_id', $this->selected_placement)
        //         ->whereMonth('date', $this->month)
        //         ->whereYear('date', $this->year)
        //         ->orderBy('id_karyawan', 'asc')->get();
        // }


        return view('payroll_bank_view', [
            'data' => $data,
            'header_text' => $header_text
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'G' => "0",
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,

        ];
    }
}
