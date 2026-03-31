<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
// use Maatwebsite\Excel\Excel;
use Illuminate\Http\Request;
use App\Exports\BankReportExcel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiSummaryExport;

class ReportController extends Controller
{
    // public function index()
    // {
    //     $select_month = Payroll::select(DB::raw('MONTH(date) as month'))
    //         ->distinct()
    //         ->pluck('month')
    //         ->toArray();
    //     $select_year = Payroll::select(DB::raw('YEAR(date) as year'))
    //         ->distinct()
    //         ->pluck('year')
    //         ->toArray();
    //     return view('reports.index', [
    //         'select_month' => $select_month,
    //         'select_year' => $select_year
    //     ]);
    // }

    public function presensi_summary_index()
    {
        return view('reports.presensi_summary_index');
    }

    public function createExcel(Request $request)
    {
        $nama_file = '';
        switch ($request->selectedCompany) {
            case '1':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'semua_karyawan_Bank.xlsx';
                break;
            case '2':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('placement', 'YCME')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'Pabrik-1_Bank.xlsx';
                break;
            case '3':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('placement', 'YEV')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'Pabrik-2_Bank.xlsx';
                break;
            case '4':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->whereIn('placement', ['YIG', 'YSM'])

                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'Kantor_Bank.xlsx';
                break;
            case '5':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'ASB')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'ASB_Bank.xlsx';
                break;
            case '6':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'DPA')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'DPA_Bank.xlsx';
                break;
            case '7':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'YCME')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'YCME_Bank.xlsx';
                break;
            case '8':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'YEV')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'YEV_Bank.xlsx';
                break;
            case '9':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'YIG')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'YIG_Bank.xlsx';
                break;
            case '10':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'YSM')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'YSM_Bank.xlsx';
                break;
            case '11':
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('company', 'YAM')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'YAM_Bank.xlsx';
                break;
        }

        $nama_file = nama_file_excel($nama_file, $request->month, $request->year);
        return Excel::download(new BankReportExcel($payroll), $nama_file);
    }

    public function createExcelPresensiSummary(Request $request)
    {
        $nama_file = '';

        switch ($request->selectedCompany) {
            case 0:
                $nama_file = 'semua_Presensi_Summary.xlsx';
                break;

            case 1:
                $nama_file = 'Presensi_Summary_pabrik1.xlsx';
                break;

            case 2:
                $nama_file = 'Presensi_Summary_pabrik2.xlsx';
                break;

            case 3:
                $nama_file = 'Presensi_Summary_kantor.xlsx';
                break;

            case 4:
                $nama_file = 'Presensi_Summary_ASB.xlsx';
                break;

            case 5:
                $nama_file = 'Presensi_Summary_DPA.xlsx';
                break;

            case 6:
                $nama_file = 'Presensi_Summary_YCME.xlsx';
                break;

            case 7:
                $nama_file = 'Presensi_Summary_YEV.xlsx';
                break;

            case 8:
                $nama_file = 'Presensi_Summary_YIG.xlsx';
                break;

            case 9:
                $nama_file = 'Presensi_Summary_YSM.xlsx';
                break;
        }


        $nama_file = nama_file_excel($nama_file, $request->month, $request->year);

        return Excel::download(new PresensiSummaryExport($request->selectedCompany, $request->year, $request->month), $nama_file);
    }
}
