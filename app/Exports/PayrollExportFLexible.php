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

class PayrollExportFLexible implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    protected $data;

    protected $columnName, $direction, $search,  $selected_company, $selected_placement, $selected_departemen, $status, $month, $year;

    public function __construct($columnName, $direction, $search,  $selected_company, $selected_placement, $selected_departemen, $status, $month, $year)
    {
        $this->columnName = $columnName;
        $this->direction = $direction;
        $this->search = $search;
        $this->selected_company = $selected_company;
        $this->selected_placement = $selected_placement;
        $this->selected_departemen = $selected_departemen;
        $this->status = $status;
        $this->month = $month;
        $this->year = $year;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
            // Styling a specific cell by coordinate.

            // Styling an entire column.
            // 2  => ['font' => ['size' => 15]],
            // 2  => ['font' => ['size' => 15]],
            // 2  => ['font' => ['size' => 15]],
            // 2 => ['font' => ['italic' => true]],
            // 3  => ['font' => ['size' => 12]],


        ];
    }

    public function getPayrollQuery($statuses, $search = null, $placement_id = null, $company_id = null, $department_id = null)
    {
        return Payroll::query()

            ->whereIn('status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $query
                    // ->where('id_karyawan', 'LIKE', '%' . trim($search) . '%')
                    ->where('id_karyawan',  trim($search))
                    ->orWhere('nama', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('jabatan_id', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('company_id', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('department_id', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('metode_penggajian', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('status_karyawan', 'LIKE', '%' . trim($search) . '%');
            })
            ->when($placement_id, function ($query) use ($placement_id) {
                $query->where('placement_id', $placement_id);
            })
            ->when($company_id, function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })
            ->when($department_id, function ($query) use ($department_id) {
                $query->where('department_id', $department_id);
            })

            ->orderBy($this->columnName, $this->direction);
    }

    private function applyCommonFilters($query)
    {
        return $query
            ->when($this->selected_company != 0, fn($q) => $q->where('company_id', $this->selected_company))
            ->when($this->selected_placement != 0, fn($q) => $q->where('placement_id', $this->selected_placement))
            ->when($this->selected_departemen != 0, fn($q) => $q->where('department_id', $this->selected_departemen))
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year);
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


        // $total = $this->applyCommonFilters(
        //     Payroll::whereIn('status_karyawan', $statuses)
        // )->sum('total');

        $payroll = $this->applyCommonFilters(
            $this->getPayrollQuery($statuses, $this->search)
        )->orderBy($this->columnName, $this->direction)
            ->get();

        $nama_company = '';
        $nama_directorate = '';
        $nama_departement = '';
        if ($this->selected_company != 0) {
            if ($this->selected_company == 0) {
                $nama_company = 'All Companies ';
            } else {
                $nama_company = 'Company: ' . nama_company($this->selected_company) . ', ';
            }
        }
        if ($this->selected_placement != 0) {
            if ($this->selected_placement == 0) {
                $nama_directorate = 'All Directorates';
            } else {
                $nama_directorate = 'Directorate: ' . nama_placement($this->selected_placement) . ', ';
            }
        }
        if ($this->selected_departemen != 0) {
            if ($this->selected_departemen == 0) {
                $nama_departement = 'All Departments ';
            } else {
                $nama_departement = 'Department: ' . nama_department($this->selected_departemen) . ', ';
            }
        }

        if ($this->selected_company == 0 && $this->selected_placement == 0 && $this->selected_departemen == 0) {

            $header_text = 'Perincian Payroll ' . monthName($this->month) . '-' . $this->year;
        } else {

            $header_text = 'Perincian Payroll untuk ' . $nama_directorate . $nama_company . $nama_departement;
        }

        if ($this->search != null) {
            $header_text = 'Perincian Payroll untuk ' . $nama_directorate . $nama_company . $nama_departement . 'search-' . $this->search . ', ';
        }

        $header_text = rtrim($header_text, ',');
        $header_text = $header_text . ' ' . monthName($this->month) . ' ' . $this->year;
        // $header_text = $header_text . ' ' . monthName($this->month) . ' ' . $this->year;


        $header_text = 'Perincian Payroll ' . nama_bulan($this->month) . ' ' . $this->year;

        return view('payroll_excel_view', [
            // 'data' => $data,
            'data' => $payroll,
            'header_text' => $header_text,
            'search' => $this->search,
            'nama_directorate' => nama_placement($this->selected_placement),
            'nama_company' => nama_company($this->selected_company),
            'nama_departement' => nama_department($this->selected_departemen)
        ]);
    }

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'D' => "0",
            'E' => NumberFormat::FORMAT_TEXT,
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
            'Z' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,

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
            'AP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AR' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AS' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'AT' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED

        ];
    }
}
