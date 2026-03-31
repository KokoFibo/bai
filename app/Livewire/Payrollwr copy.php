<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lock;
use App\Models\Payroll;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Exports\PphExport;
use App\Models\Jamkerjaid;
use Livewire\WithPagination;
use App\Jobs\BuildPayrollJob;
use App\Exports\PayrollExport;
use App\Models\Yfrekappresensi;
use App\Exports\BankReportExcel;
use App\Exports\PlacementExport;
use App\Exports\DepartmentExport;
use App\Jobs\rebuildJob;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;



class Payrollwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $selected_company = 0;
    public $selected_placement = 0;
    public $selected_departemen = 0;
    public $departments;
    public $search;
    public $perpage = 10;
    public $month;
    public $year;
    public $columnName = 'id_karyawan';
    public $direction = 'asc';
    public $status = 1;
    public $data_payroll;
    public $data_karyawan;
    public $cx;
    public $lock_presensi;
    public $lock_slip_gaji;
    public $lock_data;
    public $select_month, $select_year;

    public function close_succesful_rebuilt()
    {
        $rebuild = Lock::find(1);
        $rebuild->rebuild_done = false;
        $rebuild->save();
    }


    public function export()
    {
        $nama_file = '';
        if ($this->selected_company != 0) {
            switch ($this->selected_company) {
                case 0:
                    $nama_file = 'semua_payroll.xlsx';
                    break;

                case 4:
                    $nama_file = 'payroll_company_ASB.xlsx';
                    break;

                case 5:
                    $nama_file = 'payroll_company_DPA.xlsx';
                    break;

                case 6:
                    $nama_file = 'payroll_company_YCME.xlsx';
                    break;

                case 7:
                    $nama_file = 'payroll_company_YEV.xlsx';
                    break;

                case 8:
                    $nama_file = 'payroll_company_YIG.xlsx';
                    break;

                case 9:
                    $nama_file = 'payroll_company_YSM.xlsx';
                    break;

                case 10:
                    $nama_file = 'payroll_company_YAM.xlsx';
                    break;
                case 11:
                    $nama_file = 'payroll_company_GAMA.xlsx';
                    break;
                case 12:
                    $nama_file = 'payroll_company_WAS.xlsx';
                    break;
            }
        } elseif ($this->selected_placement != 0) {
            switch ($this->selected_placement) {
                case 0:
                    $nama_file = 'semua_payroll.xlsx';
                    break;

                    // case 1:
                    //     $nama_file = 'payroll_placement_pabrik1.xlsx';
                    //     break;

                    // case 2:
                    //     $nama_file = 'payroll_placement_pabrik2.xlsx';
                    //     break;

                    // case 3:
                    //     $nama_file = 'payroll_placement_kantor.xlsx';
                    //     break;

                    // case 4:
                    //     $nama_file = 'payroll_placement_ASB.xlsx';
                    //     break;

                    // case 5:
                    //     $nama_file = 'payroll_placement_DPA.xlsx';
                    //     break;

                case 6:
                    $nama_file = 'payroll_placement_YCME.xlsx';
                    break;

                case 7:
                    $nama_file = 'payroll_placement_YEV.xlsx';
                    break;

                case 8:
                    $nama_file = 'payroll_placement_YIG.xlsx';
                    break;

                case 9:
                    $nama_file = 'payroll_placement_YSM.xlsx';
                    break;
                case 10:
                    $nama_file = 'payroll_placement_YAM.xlsx';
                    break;
                case 11:
                    $nama_file = 'payroll_placement_YEV_SMOOT.xlsx';
                    break;
                case 12:
                    $nama_file = 'payroll_placement_YEV_OFFERO.xlsx';
                    break;
                case 13:
                    $nama_file = 'payroll_placement_YEV_SUNRA.xlsx';
                    break;
                case 14:
                    $nama_file = 'payroll_placement_YEV_AIMA.xlsx';
                    break;
            }
        } else {
            if ($this->selected_departemen == 0) {
                $nama_file = 'semua_payroll.xlsx';
            } else {
                $nama_file = 'payroll_department_' . sambungKata($this->selected_departemen) . '.xlsx';
            }
        }

        $nama_file = nama_file_excel($nama_file, $this->month, $this->year);


        if ($this->selected_company != 0) {
            return Excel::download(new PayrollExport($this->selected_company, $this->status, $this->month, $this->year), $nama_file);
        } else if ($this->selected_placement != 0) {
            return Excel::download(new PlacementExport($this->selected_placement, $this->status, $this->month, $this->year), $nama_file);
        } else {
            return Excel::download(new DepartmentExport($this->selected_departemen, $this->status, $this->month, $this->year), $nama_file);
        }
    }


    public function bankexcel()
    {
        $nama_file = '';
        if ($this->selected_company != 0) {
            switch ($this->selected_company) {
                case '0':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'semua_karyawan_Bank.xlsx';
                    break;
                    // case '2':
                    //     $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    //         ->whereMonth('date', $this->month)
                    //         ->whereYear('date', $this->year)
                    //         ->orderBy('id_karyawan', 'asc')
                    //         ->where('placement', 'YCME')
                    //         ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    //     $nama_file = 'Pabrik-1_Bank.xlsx';
                    //     break;
                    // case '3':
                    //     $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    //         ->whereMonth('date', $this->month)
                    //         ->whereYear('date', $this->year)
                    //         ->orderBy('id_karyawan', 'asc')
                    //         ->where('placement', 'YEV')
                    //         ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    //     $nama_file = 'Pabrik-2_Bank.xlsx';
                    //     break;
                    // case '4':
                    //     $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    //         ->whereMonth('date', $this->month)
                    //         ->whereYear('date', $this->year)
                    //         ->orderBy('id_karyawan', 'asc')
                    //         ->whereIn('placement', ['YIG', 'YSM'])

                    //         ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    //     $nama_file = 'Kantor_Bank.xlsx';
                    //     break;

                case '4':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'ASB')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'ASB_Company_Bank.xlsx';
                    break;
                case '5':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'DPA')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'DPA_Company_Bank.xlsx';
                    break;
                case '6':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'YCME')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YCME_Company_Bank.xlsx';
                    break;
                case '7':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'YEV')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_Company_Bank.xlsx';
                    break;
                case '8':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'YIG')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YIG_Company_Bank.xlsx';
                    break;
                case '9':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'YSM')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YSM_Company_Bank.xlsx';
                    break;
                case '10':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'YAM')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YAM_Company_Bank.xlsx';
                    break;
                case '11':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'GAMA')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'GAMA_Company_Bank.xlsx';
                    break;
                case '12':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('company', 'WAS')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'WAS_Company_Bank.xlsx';
                    break;
            }
        } elseif ($this->selected_placement != 0) {
            switch ($this->selected_placement) {
                case '0':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'semua_karyawan_Bank.xlsx';
                    break;
                case '6':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YCME')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YCME_Placement_Bank.xlsx';
                    break;
                case '7':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YEV')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_Placement_Bank.xlsx';
                    break;
                case '8':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YIG')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YIG_Placement_Bank.xlsx';
                    break;
                case '9':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YSM')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YSM_Placement_Bank.xlsx';
                    break;
                case '10':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YAM')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YAM_Placement_Bank.xlsx';
                    break;
                case '11':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YEV SMOOT')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_SMOOT_Placement_Bank.xlsx';
                    break;
                case '12':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YEV OFFERO')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_OFFERO_Placement_Bank.xlsx';
                    break;
                case '13':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YEV SUNRA')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_SUNRA_Placement_Bank.xlsx';
                    break;
                case '14':
                    $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy('id_karyawan', 'asc')
                        ->where('placement', 'YEV AIMA')
                        ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                    $nama_file = 'YEV_AIMA_Placement_Bank.xlsx';
                    break;
            }
        } else {
            if ($this->selected_departemen == 0) {
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement']);
                $nama_file = 'semua_karyawan_Bank.xlsx';
            } else {
                $payroll = Payroll::whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'])
                    ->whereMonth('date', $this->month)
                    ->whereYear('date', $this->year)
                    ->orderBy('id_karyawan', 'asc')
                    ->where('departemen', $this->selected_departemen)
                    ->get(['nama', 'nama_bank', 'nomor_rekening', 'total', 'company', 'placement', 'departemen']);
                $nama_file = sambungKata($this->selected_departemen) . '_Department_Bank.xlsx';
            }
        }

        $nama_file = nama_file_excel($nama_file, $this->month, $this->year);
        return Excel::download(new BankReportExcel($payroll), $nama_file);
    }

    public function showDetail($id_karyawan)
    {

        $this->data_payroll = Payroll::with('jamkerjaid')
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->where('id_karyawan', $id_karyawan)
            ->first();

        $this->data_karyawan = Karyawan::where('id_karyawan', $id_karyawan)->first();
        // dd($this->data_karyawan);
    }

    public function sortColumnName($namaKolom)
    {
        $this->columnName = $namaKolom;
        $this->direction = $this->swapDirection();
    }
    public function swapDirection()
    {
        return $this->direction === 'asc' ? 'desc' : 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->departments = Karyawan::select('department_id')
            ->distinct()
            ->pluck('department_id')
            ->toArray();

        $data = Payroll::first();
        if (now()->day < 5) {
            $this->year =
                now()->subMonth()->year;
            $this->month =
                now()->subMonth()->month;
        } else {
            $this->year = now()->year;
            $this->month = now()->month;
        }

        if ($data != null) {
            $this->data_payroll = Payroll::with('jamkerjaid')
                ->whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('id_karyawan', $data->id_karyawan)
                ->first();
            $this->data_karyawan = Karyawan::where('id_karyawan', $data->id_karyawan)->first();
        }

        $lock = Lock::find(1);
        $this->lock_presensi = $lock->presensi;
        $this->lock_slip_gaji = $lock->slip_gaji;
        $this->lock_data = $lock->data;
    }
    public function updatedLockPresensi()
    {
        // $lock=Lock::find(1);
        $lock = Lock::first();
        $lock->presensi = $this->lock_presensi;
        $lock->save();
    }
    public function updatedLockSlipGaji()
    {
        // $lock=Lock::find(1);
        $lock = Lock::first();
        $lock->slip_gaji = $this->lock_slip_gaji;
        $lock->save();
    }
    public function updatedLockData()
    {
        // $lock=Lock::find(1);
        $lock = Lock::first();
        $lock->data = $this->lock_data;
        $lock->save();
    }



    // public function getPayrollQueue()
    // {
    //     $this->dispatch(new BuildPayrollJob($this->month, $this->year));
    // }

    public function buat_payroll($queue)
    {

        // supaya tidak dilakukan bersamaan
        if (check_absensi_kosong()) {
            clear_locks();
            // $this->dispatch('error', message: 'Masih ada data kosong di presensi');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Masih ada data kosong di presensi',
            );
            return;
        }
        $lock = Lock::find(1);
        if ($lock->build == 1) {
            // $this->dispatch('error', message: 'Mohon dicoba sebentar lagi');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Mohon dicoba sebentar lagi',
            );
            return;
        } else {
            $lock->build = 1;
            $lock->save();
        }

        // hapus ini kalau sudah kelar develop
        $lock->build = 1;
        $lock->save();

        $startTime = microtime(true);
        if ($queue == 'noQueue') {
            $result  = build_payroll($this->month, $this->year);
        } else {
            $lock = Lock::find(1);
            $lock->rebuild_done = 2;
            $lock->save();
            dispatch(new rebuildJob($this->month, $this->year));
            $result = 1;
        }


        if ($result == 0) {
            // $this->dispatch('error', message: 'Data Presensi tidak ada');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data Presensi tidak ada',
            );
        } else {
            // $this->dispatch('success', message: 'Data Payroll Karyawan Sudah di Built ( ' . number_format((microtime(true) - $startTime), 2) . ' seconds )');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Payroll Karyawan Sudah di Built ( ' . number_format((microtime(true) - $startTime), 2) . ' seconds )',
            );
        }


        $lock->build = 0;
        $lock->save();
        $this->mount();
    }



    // ok3
    public function bonus_potongan()
    {
        $bonus = 0;
        $potongaan = 0;
        $all_bonus = 0;
        $all_potongan = 0;
        $tambahan = Tambahan::whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year)
            ->get();

        foreach ($tambahan as $d) {
            $all_bonus = $d->uang_makan + $d->bonus_lain;
            $all_potongan = $d->baju_esd + $d->gelas + $d->sandal + $d->seragam + $d->sport_bra + $d->hijab_instan + $d->id_card_hilang + $d->masker_hijau + $d->potongan_lain;
            $id_payroll = Payroll::whereMonth('date', $this->month)
                ->whereYear('date', $this->year)
                ->where('id_karyawan', $d->user_id)
                ->first();
            if ($id_payroll != null) {
                $payroll = Payroll::find($id_payroll->id);
                $payroll->bonus1x = $payroll->bonus1x + $all_bonus;
                $payroll->potongan1x = $payroll->potongan1x + $all_potongan;
                $payroll->total = $payroll->total + $all_bonus - $all_potongan;
                $payroll->save();
            }
        }

        // $this->dispatch('success', message: 'Bonus dan Potangan added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Bonus dan Potangan added',
        );
    }

    public function getPayrollQuery($statuses, $search = null, $placement = null, $company = null, $departemen = null)
    {
        return Payroll::query()

            ->whereIn('status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $query
                    // ->where('id_karyawan', 'LIKE', '%' . trim($search) . '%')
                    ->where('id_karyawan',  trim($search))
                    ->orWhere('nama', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('jabatan', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('company', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('departemen', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('metode_penggajian', 'LIKE', '%' . trim($search) . '%')
                    ->orWhere('status_karyawan', 'LIKE', '%' . trim($search) . '%');
            })
            ->when($placement, function ($query) use ($placement) {
                $query->where('placement', $placement);
            })
            ->when($company, function ($query) use ($company) {
                $query->where('company', $company);
            })
            ->when($departemen, function ($query) use ($departemen) {
                $query->where('departemen', $departemen);
            })

            ->orderBy($this->columnName, $this->direction);
    }

    public function updatedSelectedCompany()
    {
        $this->selected_placement = 0;
        $this->selected_departemen = 0;
    }
    public function updatedSelectedPlacement()
    {
        $this->selected_company = 0;
        $this->selected_departemen = 0;
    }
    public function updatedSelectedDepartemen()
    {
        $this->selected_company = 0;
        $this->selected_placement = 0;
    }

    public function updatedYear()
    {
        $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        $this->month = $this->select_month[0];
    }

    public function render()
    {

        $this->cx++;

        $this->select_year = Payroll::select(DB::raw('YEAR(date) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();
        // ooo
        // $this->select_month = Payroll::select(DB::raw('MONTH(date) as month'))->whereYear('date', $this->year)
        //     ->distinct()
        //     ->pluck('month')
        //     ->toArray();
        $months = Payroll::select(DB::raw('MONTH(date) as month'))
            ->whereYear('date', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        if (!in_array($this->month, $months)) {
            $months[] = $this->month;
        }

        $this->select_month = $months;

        if ($this->status == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned'];
        } elseif ($this->status == 2) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }

        if ($this->selected_placement == 0 && $this->selected_departemen == 0) {

            switch ($this->selected_company) {
                case 0:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 1:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->where('placement', 'YCME')
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 2:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 3:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereIn('placement', ['YIG', 'YSM'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = Payroll::query()
                        ->whereIn('status_karyawan', $statuses)
                        ->when($this->search, function ($query) {
                            $query
                                ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('company', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
                        })
                        ->whereIn('placement', ['YIG', 'YSM'])
                        ->orderBy($this->columnName, $this->direction)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 4:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'ASB')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'ASB')

                        ->where('company', 'ASB')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 5:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'DPA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'DPA')

                        ->where('company', 'DPA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 6:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YCME')

                        ->where('company', 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 7:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YEV')

                        ->where('company', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 8:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'YIG')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YIG')

                        ->where('company', 'YIG')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 9:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'YSM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YSM')
                        ->where('company', 'YSM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 10:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'YAM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'YAM')
                        ->where('company', 'YAM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 11:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'GAMA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'GAMA')
                        ->where('company', 'GAMA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 12:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('company', 'WAS')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', 'WAS')
                        ->where('company', 'WAS')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
            }
        } elseif ($this->selected_company == 0 && $this->selected_departemen == 0) {
            switch ($this->selected_placement) {
                case 0:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 1:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->where('placement', 'YCME')
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 2:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 3:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereIn('placement', ['YIG', 'YSM'])
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = Payroll::query()
                        ->whereIn('status_karyawan', $statuses)
                        ->when($this->search, function ($query) {
                            $query
                                ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('jabatan', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('company', 'LIKE', '%' . trim($this->search) . '%')
                                ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
                        })
                        ->whereIn('placement', ['YIG', 'YSM'])
                        ->orderBy($this->columnName, $this->direction)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 4:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'ASB')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'ASB', '')

                        ->where('placement', 'ASB')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 5:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'DPA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'DPA', '')

                        ->where('placement', 'DPA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 6:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YCME', '')

                        ->where('placement', 'YCME')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 7:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV', '')

                        ->where('placement', 'YEV')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 8:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YIG')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YIG', '')

                        ->where('placement', 'YIG')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 9:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YSM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YSM', '')
                        ->where('placement', 'YSM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 10:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YAM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YAM', '')
                        ->where('placement', 'YAM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 11:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV SMOOT')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV SMOOT', '')
                        ->where('placement', 'YEV SMOOT')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 12:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV OFFERO')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV OFFERO', '')
                        ->where('placement', 'YEV OFFERO')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 13:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV SUNRA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV SUNRA', '')
                        ->where('placement', 'YEV SUNRA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 14:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('placement', 'YEV AIMA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, 'YEV AIMA', '')
                        ->where('placement', 'YEV AIMA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
            }
        } else {
            switch ($this->selected_departemen) {
                case 0:
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search)
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'BD':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'BD')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'BD')

                        ->where('departemen', 'BD')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'Engineering':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Engineering')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Engineering')

                        ->where('departemen', 'Engineering')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'EXIM':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'EXIM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'EXIM')

                        ->where('departemen', 'EXIM')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'Finance Accounting':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Finance Accounting')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Finance Accounting')

                        ->where('departemen', 'Finance Accounting')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'GA':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'GA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'GA')

                        ->where('departemen', 'GA')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'Gudang':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Gudang')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Gudang')

                        ->where('departemen', 'Gudang')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'HR':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'HR')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'HR')

                        ->where('departemen', 'HR')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'Legal':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Legal')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');

                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Legal')

                        ->where('departemen', 'Legal')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;

                case 'Procurement':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Procurement')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Procurement')
                        ->where('departemen', 'Procurement')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 'Produksi':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Produksi')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Produksi')
                        ->where('departemen', 'Produksi')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 'Quality Control':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Quality Control')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Quality Control')
                        ->where('departemen', 'Quality Control')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
                case 'Board of Director':
                    $total = Payroll::whereIn('status_karyawan', $statuses)
                        ->where('departemen', 'Board of Director')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->sum('total');
                    $payroll = $this->getPayrollQuery($statuses, $this->search, '', '', 'Board of Director')
                        ->where('departemen', 'Board of Director')
                        ->whereMonth('date', $this->month)
                        ->whereYear('date', $this->year)
                        ->orderBy($this->columnName, $this->direction)
                        ->paginate($this->perpage);
                    break;
            }
        }

        $tgl = Payroll::whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->select('created_at')->first();
        if ($tgl != null) {
            $last_build = Carbon::parse($tgl->created_at)->diffForHumans();
        } else {
            $last_build = 0;
        }

        $data_kosong = Jamkerjaid::count();


        $this->cx++;


        return view('livewire.payrollwr', compact(['payroll', 'total', 'last_build', 'data_kosong']));
    }
}
