<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

// class HeadcountExport implements FromView,  ShouldAutoSize, WithColumnFormatting, WithStyles
class HeadcountExport implements FromView, ShouldAutoSize, WithColumnFormatting, WithStyles
{

    use Exportable;
    protected $month, $year;


    public $yig_hr, $yig_finance, $yig_procurement, $yig_ga, $yig_legal, $yig_exim, $yig_me, $yig_bd, $yig_qc, $yig_production, $yig_warehouse, $yig_bod, $yig_total;
    public $ycme_hr, $ycme_finance, $ycme_procurement, $ycme_ga, $ycme_legal, $ycme_exim, $ycme_me, $ycme_bd, $ycme_qc, $ycme_production, $ycme_warehouse, $ycme_bod, $ycme_total;
    public $ysm_hr, $ysm_finance, $ysm_procurement, $ysm_ga, $ysm_legal, $ysm_exim, $ysm_me, $ysm_bd, $ysm_qc, $ysm_production, $ysm_warehouse, $ysm_bod, $ysm_total;
    public $yam_hr, $yam_finance, $yam_procurement, $yam_ga, $yam_legal, $yam_exim, $yam_me, $yam_bd, $yam_qc, $yam_production, $yam_warehouse, $yam_bod, $yam_total;

    public $yev_hr, $yev_finance, $yev_procurement, $yev_ga, $yev_legal, $yev_exim, $yev_me, $yev_bd, $yev_qc, $yev_production, $yev_warehouse, $yev_bod, $yev_total;
    public $yevsmoot_hr, $yevsmoot_finance, $yevsmoot_procurement, $yevsmoot_ga, $yevsmoot_legal, $yevsmoot_exim, $yevsmoot_me, $yevsmoot_bd, $yevsmoot_qc, $yevsmoot_production, $yevsmoot_warehouse, $yevsmoot_bod, $yevsmoot_total;
    public $yevoffero_hr, $yevoffero_finance, $yevoffero_procurement, $yevoffero_ga, $yevoffero_legal, $yevoffero_exim, $yevoffero_me, $yevoffero_bd, $yevoffero_qc, $yevoffero_production, $yevoffero_warehouse, $yevoffero_bod, $yevoffero_total;
    public $yevsunra_hr, $yevsunra_finance, $yevsunra_procurement, $yevsunra_ga, $yevsunra_legal, $yevsunra_exim, $yevsunra_me, $yevsunra_bd, $yevsunra_qc, $yevsunra_production, $yevsunra_warehouse, $yevsunra_bod, $yevsunra_total;
    public $yevaima_hr, $yevaima_finance, $yevaima_procurement, $yevaima_ga, $yevaima_legal, $yevaima_exim, $yevaima_me, $yevaima_bd, $yevaima_qc, $yevaima_production, $yevaima_warehouse, $yevaima_bod, $yevaima_total;
    public $yevelektronik_hr, $yevelektronik_finance, $yevelektronik_procurement, $yevelektronik_ga, $yevelektronik_legal, $yevelektronik_exim, $yevelektronik_me, $yevelektronik_bd, $yevelektronik_qc, $yevelektronik_production, $yevelektronik_warehouse, $yevelektronik_bod, $yevelektronik_total;

    public $yig_total1, $ycme_total1, $ysm_total1, $yam_total1, $yev_total1, $yevsmoot_total1, $yevoffero_total1, $yevsunra_total1, $yevaima_total1, $yevelektronik_total1;


    public function __construct($month, $year)
    {

        $this->month = $month;
        $this->year = $year;
        // dd($this->month, $this->year);
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

    public function columnFormats(): array
    {
        return [
            // 'C' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED,

        ];
    }

    public function yevelektronik()
    {
        $this->yevelektronik_hr = 0;
        $this->yevelektronik_finance = 0;
        $this->yevelektronik_procurement = 0;
        $this->yevelektronik_ga = 0;
        $this->yevelektronik_legal = 0;
        $this->yevelektronik_exim = 0;
        $this->yevelektronik_me = 0;
        $this->yevelektronik_bd = 0;
        $this->yevelektronik_qc = 0;
        $this->yevelektronik_production = 0;
        $this->yevelektronik_warehouse = 0;
        $this->yevelektronik_bod = 0;
        $this->yevelektronik_total = 0;

        $this->yevelektronik_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'HR')->count();
        $this->yevelektronik_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Finance Accounting')->count();
        $this->yevelektronik_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Procurement')->count();
        $this->yevelektronik_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'GA')->count();
        $this->yevelektronik_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Legal')->count();
        $this->yevelektronik_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'EXIM')->count();
        $this->yevelektronik_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Engineering')->count();
        $this->yevelektronik_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'BD')->count();
        $this->yevelektronik_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Quality Control')->count();
        $this->yevelektronik_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Produksi')->count();
        $this->yevelektronik_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Gudang')->count();
        $this->yevelektronik_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->where('departemen', 'Board of Director')->count();
        $this->yevelektronik_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV ELEKTRONIK')->count();
        $this->yevelektronik_total = $this->yevelektronik_hr + $this->yevelektronik_finance + $this->yevelektronik_procurement + $this->yevelektronik_ga + $this->yevelektronik_legal + $this->yevelektronik_exim + $this->yevelektronik_me + $this->yevelektronik_bd + $this->yevelektronik_qc + $this->yevelektronik_production + $this->yevelektronik_warehouse + $this->yevelektronik_bod;
    }

    public function yevaima()
    {
        $this->yevaima_hr = 0;
        $this->yevaima_finance = 0;
        $this->yevaima_procurement = 0;
        $this->yevaima_ga = 0;
        $this->yevaima_legal = 0;
        $this->yevaima_exim = 0;
        $this->yevaima_me = 0;
        $this->yevaima_bd = 0;
        $this->yevaima_qc = 0;
        $this->yevaima_production = 0;
        $this->yevaima_warehouse = 0;
        $this->yevaima_bod = 0;
        $this->yevaima_total = 0;

        $this->yevaima_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'HR')->count();
        $this->yevaima_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Finance Accounting')->count();
        $this->yevaima_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Procurement')->count();
        $this->yevaima_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'GA')->count();
        $this->yevaima_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Legal')->count();
        $this->yevaima_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'EXIM')->count();
        $this->yevaima_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Engineering')->count();
        $this->yevaima_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'BD')->count();
        $this->yevaima_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Quality Control')->count();
        $this->yevaima_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Produksi')->count();
        $this->yevaima_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Gudang')->count();
        $this->yevaima_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->where('departemen', 'Board of Director')->count();
        $this->yevaima_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV AIMA')->count();
        $this->yevaima_total = $this->yevaima_hr + $this->yevaima_finance + $this->yevaima_procurement + $this->yevaima_ga + $this->yevaima_legal + $this->yevaima_exim + $this->yevaima_me + $this->yevaima_bd + $this->yevaima_qc + $this->yevaima_production + $this->yevaima_warehouse + $this->yevaima_bod;
    }

    public function yevsunra()
    {
        $this->yevsunra_hr = 0;
        $this->yevsunra_finance = 0;
        $this->yevsunra_procurement = 0;
        $this->yevsunra_ga = 0;
        $this->yevsunra_legal = 0;
        $this->yevsunra_exim = 0;
        $this->yevsunra_me = 0;
        $this->yevsunra_bd = 0;
        $this->yevsunra_qc = 0;
        $this->yevsunra_production = 0;
        $this->yevsunra_warehouse = 0;
        $this->yevsunra_bod = 0;
        $this->yevsunra_total = 0;

        $this->yevsunra_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'HR')->count();
        $this->yevsunra_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Finance Accounting')->count();
        $this->yevsunra_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Procurement')->count();
        $this->yevsunra_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'GA')->count();
        $this->yevsunra_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Legal')->count();
        $this->yevsunra_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'EXIM')->count();
        $this->yevsunra_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Engineering')->count();
        $this->yevsunra_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'BD')->count();
        $this->yevsunra_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Quality Control')->count();
        $this->yevsunra_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Produksi')->count();
        $this->yevsunra_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Gudang')->count();
        $this->yevsunra_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->where('departemen', 'Board of Director')->count();
        $this->yevsunra_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SUNRA')->count();
        $this->yevsunra_total = $this->yevsunra_hr + $this->yevsunra_finance + $this->yevsunra_procurement + $this->yevsunra_ga + $this->yevsunra_legal + $this->yevsunra_exim + $this->yevsunra_me + $this->yevsunra_bd + $this->yevsunra_qc + $this->yevsunra_production + $this->yevsunra_warehouse + $this->yevsunra_bod;
    }

    public function yevoffero()
    {
        $this->yevoffero_hr = 0;
        $this->yevoffero_finance = 0;
        $this->yevoffero_procurement = 0;
        $this->yevoffero_ga = 0;
        $this->yevoffero_legal = 0;
        $this->yevoffero_exim = 0;
        $this->yevoffero_me = 0;
        $this->yevoffero_bd = 0;
        $this->yevoffero_qc = 0;
        $this->yevoffero_production = 0;
        $this->yevoffero_warehouse = 0;
        $this->yevoffero_bod = 0;
        $this->yevoffero_total = 0;

        $this->yevoffero_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'HR')->count();
        $this->yevoffero_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Finance Accounting')->count();
        $this->yevoffero_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Procurement')->count();
        $this->yevoffero_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'GA')->count();
        $this->yevoffero_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Legal')->count();
        $this->yevoffero_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'EXIM')->count();
        $this->yevoffero_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Engineering')->count();
        $this->yevoffero_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'BD')->count();
        $this->yevoffero_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Quality Control')->count();
        $this->yevoffero_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Produksi')->count();
        $this->yevoffero_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Gudang')->count();
        $this->yevoffero_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->where('departemen', 'Board of Director')->count();
        $this->yevoffero_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV OFFERO')->count();
        $this->yevoffero_total = $this->yevoffero_hr + $this->yevoffero_finance + $this->yevoffero_procurement + $this->yevoffero_ga + $this->yevoffero_legal + $this->yevoffero_exim + $this->yevoffero_me + $this->yevoffero_bd + $this->yevoffero_qc + $this->yevoffero_production + $this->yevoffero_warehouse + $this->yevoffero_bod;
    }

    public function yevsmoot()
    {
        $this->yevsmoot_hr = 0;
        $this->yevsmoot_finance = 0;
        $this->yevsmoot_procurement = 0;
        $this->yevsmoot_ga = 0;
        $this->yevsmoot_legal = 0;
        $this->yevsmoot_exim = 0;
        $this->yevsmoot_me = 0;
        $this->yevsmoot_bd = 0;
        $this->yevsmoot_qc = 0;
        $this->yevsmoot_production = 0;
        $this->yevsmoot_warehouse = 0;
        $this->yevsmoot_bod = 0;
        $this->yevsmoot_total = 0;

        $this->yevsmoot_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'HR')->count();
        $this->yevsmoot_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Finance Accounting')->count();
        $this->yevsmoot_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Procurement')->count();
        $this->yevsmoot_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'GA')->count();
        $this->yevsmoot_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Legal')->count();
        $this->yevsmoot_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'EXIM')->count();
        $this->yevsmoot_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Engineering')->count();
        $this->yevsmoot_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'BD')->count();
        $this->yevsmoot_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Quality Control')->count();
        $this->yevsmoot_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Produksi')->count();
        $this->yevsmoot_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Gudang')->count();
        $this->yevsmoot_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->where('departemen', 'Board of Director')->count();
        $this->yevsmoot_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV SMOOT')->count();
        $this->yevsmoot_total = $this->yevsmoot_hr + $this->yevsmoot_finance + $this->yevsmoot_procurement + $this->yevsmoot_ga + $this->yevsmoot_legal + $this->yevsmoot_exim + $this->yevsmoot_me + $this->yevsmoot_bd + $this->yevsmoot_qc + $this->yevsmoot_production + $this->yevsmoot_warehouse + $this->yevsmoot_bod;
    }

    public function yev()
    {
        $this->yev_hr = 0;
        $this->yev_finance = 0;
        $this->yev_procurement = 0;
        $this->yev_ga = 0;
        $this->yev_legal = 0;
        $this->yev_exim = 0;
        $this->yev_me = 0;
        $this->yev_bd = 0;
        $this->yev_qc = 0;
        $this->yev_production = 0;
        $this->yev_warehouse = 0;
        $this->yev_bod = 0;
        $this->yev_total = 0;

        $this->yev_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'HR')->count();
        $this->yev_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Finance Accounting')->count();
        $this->yev_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Procurement')->count();
        $this->yev_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'GA')->count();
        $this->yev_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Legal')->count();
        $this->yev_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'EXIM')->count();
        $this->yev_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Engineering')->count();
        $this->yev_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'BD')->count();
        $this->yev_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Quality Control')->count();
        $this->yev_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Produksi')->count();
        $this->yev_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Gudang')->count();
        $this->yev_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->where('departemen', 'Board of Director')->count();
        $this->yev_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YEV')->count();
        $this->yev_total = $this->yev_hr + $this->yev_finance + $this->yev_procurement + $this->yev_ga + $this->yev_legal + $this->yev_exim + $this->yev_me + $this->yev_bd + $this->yev_qc + $this->yev_production + $this->yev_warehouse + $this->yev_bod;
    }




    public function ycme()
    {
        $this->ycme_hr = 0;
        $this->ycme_finance = 0;
        $this->ycme_procurement = 0;
        $this->ycme_ga = 0;
        $this->ycme_legal = 0;
        $this->ycme_exim = 0;
        $this->ycme_me = 0;
        $this->ycme_bd = 0;
        $this->ycme_qc = 0;
        $this->ycme_production = 0;
        $this->ycme_warehouse = 0;
        $this->ycme_bod = 0;
        $this->ycme_total = 0;

        $this->ycme_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'HR')->count();
        $this->ycme_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Finance Accounting')->count();
        $this->ycme_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Procurement')->count();
        $this->ycme_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'GA')->count();
        $this->ycme_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Legal')->count();
        $this->ycme_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'EXIM')->count();
        $this->ycme_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Engineering')->count();
        $this->ycme_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'BD')->count();
        $this->ycme_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Quality Control')->count();
        $this->ycme_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Produksi')->count();
        $this->ycme_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Gudang')->count();
        $this->ycme_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->where('departemen', 'Board of Director')->count();
        $this->ycme_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YCME')->count();
        $this->ycme_total = $this->ycme_hr + $this->ycme_finance + $this->ycme_procurement + $this->ycme_ga + $this->ycme_legal + $this->ycme_exim + $this->ycme_me + $this->ycme_bd + $this->ycme_qc + $this->ycme_production + $this->ycme_warehouse + $this->ycme_bod + $this->ycme_total;
    }
    public function yig()
    {
        $this->yig_hr = 0;
        $this->yig_finance = 0;
        $this->yig_procurement = 0;
        $this->yig_ga = 0;
        $this->yig_legal = 0;
        $this->yig_exim = 0;
        $this->yig_me = 0;
        $this->yig_bd = 0;
        $this->yig_qc = 0;
        $this->yig_production = 0;
        $this->yig_warehouse = 0;
        $this->yig_bod = 0;
        $this->yig_total = 0;

        $this->yig_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'HR')->count();
        $this->yig_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Finance Accounting')->count();
        $this->yig_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Procurement')->count();
        $this->yig_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'GA')->count();
        $this->yig_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Legal')->count();
        $this->yig_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'EXIM')->count();
        $this->yig_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Engineering')->count();
        $this->yig_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'BD')->count();
        $this->yig_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Quality Control')->count();
        $this->yig_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Produksi')->count();
        $this->yig_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Gudang')->count();
        $this->yig_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->where('departemen', 'Board of Director')->count();
        $this->yig_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YIG')->count();
        $this->yig_total = $this->yig_hr + $this->yig_finance + $this->yig_procurement + $this->yig_ga + $this->yig_legal + $this->yig_exim + $this->yig_me + $this->yig_bd + $this->yig_qc + $this->yig_production + $this->yig_warehouse + $this->yig_bod + $this->yig_total;
    }
    public function ysm()
    {
        $this->ysm_hr = 0;
        $this->ysm_finance = 0;
        $this->ysm_procurement = 0;
        $this->ysm_ga = 0;
        $this->ysm_legal = 0;
        $this->ysm_exim = 0;
        $this->ysm_me = 0;
        $this->ysm_bd = 0;
        $this->ysm_qc = 0;
        $this->ysm_production = 0;
        $this->ysm_warehouse = 0;
        $this->ysm_bod = 0;
        $this->ysm_total = 0;

        $this->ysm_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'HR')->count();
        $this->ysm_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Finance Accounting')->count();
        $this->ysm_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Procurement')->count();
        $this->ysm_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'GA')->count();
        $this->ysm_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Legal')->count();
        $this->ysm_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'EXIM')->count();
        $this->ysm_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Engineering')->count();
        $this->ysm_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'BD')->count();
        $this->ysm_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Quality Control')->count();
        $this->ysm_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Produksi')->count();
        $this->ysm_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Gudang')->count();
        $this->ysm_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->where('departemen', 'Board of Director')->count();
        $this->ysm_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YSM')->count();
        $this->ysm_total = $this->ysm_hr + $this->ysm_finance + $this->ysm_procurement + $this->ysm_ga + $this->ysm_legal + $this->ysm_exim + $this->ysm_me + $this->ysm_bd + $this->ysm_qc + $this->ysm_production + $this->ysm_warehouse + $this->ysm_bod + $this->ysm_total;
    }
    public function yam()
    {
        $this->yam_hr = 0;
        $this->yam_finance = 0;
        $this->yam_procurement = 0;
        $this->yam_ga = 0;
        $this->yam_legal = 0;
        $this->yam_exim = 0;
        $this->yam_me = 0;
        $this->yam_bd = 0;
        $this->yam_qc = 0;
        $this->yam_production = 0;
        $this->yam_warehouse = 0;
        $this->yam_bod = 0;
        $this->yam_total = 0;

        $this->yam_hr = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'HR')->count();
        $this->yam_finance = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Finance Accounting')->count();
        $this->yam_procurement = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Procurement')->count();
        $this->yam_ga = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'GA')->count();
        $this->yam_legal = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Legal')->count();
        $this->yam_exim = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'EXIM')->count();
        $this->yam_me = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Engineering')->count();
        $this->yam_bd = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'BD')->count();
        $this->yam_qc = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Quality Control')->count();
        $this->yam_production = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Produksi')->count();
        $this->yam_warehouse = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Gudang')->count();
        $this->yam_bod = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->where('departemen', 'Board of Director')->count();
        $this->yam_total1 = Payroll::whereYear('date', $this->year)->whereMonth('date', $this->month)->where('placement', 'YAM')->count();
        $this->yam_total = $this->yam_hr + $this->yam_finance + $this->yam_procurement + $this->yam_ga + $this->yam_legal + $this->yam_exim + $this->yam_me + $this->yam_bd + $this->yam_qc + $this->yam_production + $this->yam_warehouse + $this->yam_bod + $this->yam_total;
    }

    public function view(): View
    {

        $header_text = 'Headcount ' . nama_bulan($this->month) . ' ' . $this->year;

        // return view('payroll_excel_view', [
        //     'data' => $data,
        //     'header_text' => $header_text
        // ]);
        $this->yig();
        $this->ycme();
        $this->ysm();
        $this->yam();
        $this->yev();
        $this->yevelektronik();
        $this->yevaima();
        $this->yevsunra();
        $this->yevoffero();
        $this->yevsmoot();
        // $header_text = 'Yifang Non OS head count for ' . monthname($this->month) . ' ' . $this->year;
        $header_text = 'Yifang OS Headcount for ' . monthname($this->month) . ' ' . $this->year;

        return view('headcount_excel_view', [
            'header_text' => $header_text,

            // YIG
            'yig_hr' => $this->yig_hr,
            'yig_finance' => $this->yig_finance,
            'yig_procurement' => $this->yig_procurement,
            'yig_ga' => $this->yig_ga,
            'yig_legal' => $this->yig_legal,
            'yig_exim' => $this->yig_exim,
            'yig_me' => $this->yig_me,
            'yig_bd' => $this->yig_bd,
            'yig_qc' => $this->yig_qc,
            'yig_production' => $this->yig_production,
            'yig_warehouse' => $this->yig_warehouse,
            'yig_bod' => $this->yig_bod,
            'yig_total' => $this->yig_total,

            //YCME
            'ycme_hr' => $this->ycme_hr,
            'ycme_finance' => $this->ycme_finance,
            'ycme_procurement' => $this->ycme_procurement,
            'ycme_ga' => $this->ycme_ga,
            'ycme_legal' => $this->ycme_legal,
            'ycme_exim' => $this->ycme_exim,
            'ycme_me' => $this->ycme_me,
            'ycme_bd' => $this->ycme_bd,
            'ycme_qc' => $this->ycme_qc,
            'ycme_production' => $this->ycme_production,
            'ycme_warehouse' => $this->ycme_warehouse,
            'ycme_bod' => $this->ycme_bod,
            'ycme_total' => $this->ycme_total,

            //YSM
            'ysm_hr' => $this->ysm_hr,
            'ysm_finance' => $this->ysm_finance,
            'ysm_procurement' => $this->ysm_procurement,
            'ysm_ga' => $this->ysm_ga,
            'ysm_legal' => $this->ysm_legal,
            'ysm_exim' => $this->ysm_exim,
            'ysm_me' => $this->ysm_me,
            'ysm_bd' => $this->ysm_bd,
            'ysm_qc' => $this->ysm_qc,
            'ysm_production' => $this->ysm_production,
            'ysm_warehouse' => $this->ysm_warehouse,
            'ysm_bod' => $this->ysm_bod,
            'ysm_total' => $this->ysm_total,

            //YAM
            'yam_hr' => $this->yam_hr,
            'yam_finance' => $this->yam_finance,
            'yam_procurement' => $this->yam_procurement,
            'yam_ga' => $this->yam_ga,
            'yam_legal' => $this->yam_legal,
            'yam_exim' => $this->yam_exim,
            'yam_me' => $this->yam_me,
            'yam_bd' => $this->yam_bd,
            'yam_qc' => $this->yam_qc,
            'yam_production' => $this->yam_production,
            'yam_warehouse' => $this->yam_warehouse,
            'yam_bod' => $this->yam_bod,
            'yam_total' => $this->yam_total,

            //YEV
            'yev_hr' => $this->yev_hr,
            'yev_finance' => $this->yev_finance,
            'yev_procurement' => $this->yev_procurement,
            'yev_ga' => $this->yev_ga,
            'yev_legal' => $this->yev_legal,
            'yev_exim' => $this->yev_exim,
            'yev_me' => $this->yev_me,
            'yev_bd' => $this->yev_bd,
            'yev_qc' => $this->yev_qc,
            'yev_production' => $this->yev_production,
            'yev_warehouse' => $this->yev_warehouse,
            'yev_bod' => $this->yev_bod,
            'yev_total' => $this->yev_total,

            // YEV Aima
            'yevaima_hr' => $this->yevaima_hr,
            'yevaima_finance' => $this->yevaima_finance,
            'yevaima_procurement' => $this->yevaima_procurement,
            'yevaima_ga' => $this->yevaima_ga,
            'yevaima_legal' => $this->yevaima_legal,
            'yevaima_exim' => $this->yevaima_exim,
            'yevaima_me' => $this->yevaima_me,
            'yevaima_bd' => $this->yevaima_bd,
            'yevaima_qc' => $this->yevaima_qc,
            'yevaima_production' => $this->yevaima_production,
            'yevaima_warehouse' => $this->yevaima_warehouse,
            'yevaima_bod' => $this->yevaima_bod,
            'yevaima_total' => $this->yevaima_total,
            'yevaima_total' => $this->yevaima_total,

            // YEV Sunra
            'yevsunra_hr' => $this->yevsunra_hr,
            'yevsunra_finance' => $this->yevsunra_finance,
            'yevsunra_procurement' => $this->yevsunra_procurement,
            'yevsunra_ga' => $this->yevsunra_ga,
            'yevsunra_legal' => $this->yevsunra_legal,
            'yevsunra_exim' => $this->yevsunra_exim,
            'yevsunra_me' => $this->yevsunra_me,
            'yevsunra_bd' => $this->yevsunra_bd,
            'yevsunra_qc' => $this->yevsunra_qc,
            'yevsunra_production' => $this->yevsunra_production,
            'yevsunra_warehouse' => $this->yevsunra_warehouse,
            'yevsunra_bod' => $this->yevsunra_bod,
            'yevsunra_total' => $this->yevsunra_total,
            'yevsunra_total' => $this->yevsunra_total,

            // YEV Offero
            'yevoffero_hr' => $this->yevoffero_hr,
            'yevoffero_finance' => $this->yevoffero_finance,
            'yevoffero_procurement' => $this->yevoffero_procurement,
            'yevoffero_ga' => $this->yevoffero_ga,
            'yevoffero_legal' => $this->yevoffero_legal,
            'yevoffero_exim' => $this->yevoffero_exim,
            'yevoffero_me' => $this->yevoffero_me,
            'yevoffero_bd' => $this->yevoffero_bd,
            'yevoffero_qc' => $this->yevoffero_qc,
            'yevoffero_production' => $this->yevoffero_production,
            'yevoffero_warehouse' => $this->yevoffero_warehouse,
            'yevoffero_bod' => $this->yevoffero_bod,
            'yevoffero_total' => $this->yevoffero_total,
            'yevoffero_total' => $this->yevoffero_total,

            // YEV Smoot
            'yevsmoot_hr' => $this->yevsmoot_hr,
            'yevsmoot_finance' => $this->yevsmoot_finance,
            'yevsmoot_procurement' => $this->yevsmoot_procurement,
            'yevsmoot_ga' => $this->yevsmoot_ga,
            'yevsmoot_legal' => $this->yevsmoot_legal,
            'yevsmoot_exim' => $this->yevsmoot_exim,
            'yevsmoot_me' => $this->yevsmoot_me,
            'yevsmoot_bd' => $this->yevsmoot_bd,
            'yevsmoot_qc' => $this->yevsmoot_qc,
            'yevsmoot_production' => $this->yevsmoot_production,
            'yevsmoot_warehouse' => $this->yevsmoot_warehouse,
            'yevsmoot_bod' => $this->yevsmoot_bod,
            'yevsmoot_total' => $this->yevsmoot_total,
            'yevsmoot_total' => $this->yevsmoot_total,

            // YEV Elektronik
            'yevelektronik_hr' => $this->yevelektronik_hr,
            'yevelektronik_finance' => $this->yevelektronik_finance,
            'yevelektronik_procurement' => $this->yevelektronik_procurement,
            'yevelektronik_ga' => $this->yevelektronik_ga,
            'yevelektronik_legal' => $this->yevelektronik_legal,
            'yevelektronik_exim' => $this->yevelektronik_exim,
            'yevelektronik_me' => $this->yevelektronik_me,
            'yevelektronik_bd' => $this->yevelektronik_bd,
            'yevelektronik_qc' => $this->yevelektronik_qc,
            'yevelektronik_production' => $this->yevelektronik_production,
            'yevelektronik_warehouse' => $this->yevelektronik_warehouse,
            'yevelektronik_bod' => $this->yevelektronik_bod,
            'yevelektronik_total' => $this->yevelektronik_total,
            'yevelektronik_total' => $this->yevelektronik_total,

        ]);
    }
}
