<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
// use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Exports\KaryawanExport;
use App\Exports\DataPelitaExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KaryawanByEtnisExport;
use Illuminate\Database\Query\Builder;
use App\Exports\KaryawanByDepartmentExport;
use App\Models\Placement;
use Google\Service\YouTube\ThirdPartyLinkStatus;

class Karyawanindexwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month, $year;
    public $search;
    public $columnName = 'id_karyawan';
    public $direction = 'desc';
    public $id;
    public $selectedAll = [];
    public $selectStatus = 'Aktif';
    public $perpage = 10;
    public $selected_company;
    public $selected_placement;
    public $cx = 0;

    // variable untuk filter
    public $search_id_karyawan;
    public $search_nama;
    public $search_company;
    public $search_placement;
    public $search_department;
    public $search_jabatan;
    public $search_etnis;
    public $search_status;
    public $search_tanggal_bergabung;
    public $search_gaji_pokok;
    public $search_gaji_overtime;
    public $is_tanggal_gajian;
    public $delete_id;


    // public $departments, $companies, $etnises, $jabatans;

    #[On('delete-confirmed')]
    public function delete()
    {
        $id = $this->delete_id;
        $Data_Karyawan = Karyawan::find($id);
        $dataUser = User::where('username', $Data_Karyawan->id_karyawan)->first();
        if ($dataUser->id) {
            $user = User::find($dataUser->id);
            $user->delete();
            $Data_Karyawan->delete();

            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Karyawan Sudah di delete',
                position: 'center'
            );
        } else {
            // $this->dispatch('info', message: 'Data Karyawan Sudah di Delete, User tidak terdelete');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Karyawan Sudah di Delete, User tidak terdelete',
                position: 'center'
            );
        }
    }


    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $data = Karyawan::find($id);

        $this->dispatch('show-delete-confirmation', text: $data->nama);
    }

    public function updatedSearchTanggalBergabung()
    {
        $this->columnName = 'tanggal_bergabung';
        $this->direction = $this->search_tanggal_bergabung;
    }
    public function updatedSearchGajiPokok()
    {
        $this->columnName = 'gaji_pokok';
        $this->direction = $this->search_gaji_pokok;
    }
    public function updatedSearchGajiOvertime()
    {
        $this->columnName = 'gaji_overtime';
        $this->direction = $this->search_gaji_overtime;
    }

    public function excelByDepartment()
    {
        $nama_file = "";
        switch ($this->search_placement) {
            case '1':
                $nama_file = "pabrik_1_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '2':
                $nama_file = "pabrik_2_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '3':
                $nama_file = "kantor_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '4':
                $nama_file = "YIG_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '5':
                $nama_file = "YSM_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '6':
                $nama_file = "YAM_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '7':
                $nama_file = "YEV_SMOOT_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '8':
                $nama_file = "YEV_OFFERO_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '9':
                $nama_file = "YEV_SUNRA_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case '10':
                $nama_file = "YEV_AIMA_" . $this->search_department . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
        }

        $nama_file =
            'data_karyawan' . "_" . $this->month . "_" . $this->year . ".xlsx";



        return Excel::download(new KaryawanByDepartmentExport($this->search_nama, $this->search_id_karyawan, $this->search_company, $this->search_placement, $this->search_department, $this->search_jabatan, $this->search_etnis), $nama_file);
    }
    public function excelByEtnis()
    {

        $nama_file = "";
        switch ($this->search_etnis) {

            case 'Jawa':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Sunda':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Tionghoa':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Batak':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Lampung':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Palembang':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'Lainnya':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
            case 'kosong':
                $nama_file = "Etnis_" . $this->search_etnis . "_" . $this->month . "_" . $this->year . ".xlsx";
                break;
        }
        // dd($nama_file);
        return Excel::download(new KaryawanByEtnisExport($this->search_etnis), $nama_file);
    }
    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
        $this->selected_company = 0;

        $this->selectStatus = 1;

        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
        $this->search_etnis = "";

        $dateToCheck = now(); // Replace this with your actual date
        if ($dateToCheck->day >= 1 && $dateToCheck->day <= 6)
            $this->is_tanggal_gajian = true;
        else
            $this->is_tanggal_gajian = false;
    }

    public function reset_filter()
    {
        $this->selectStatus = 1;
        $this->search_nama = "";
        $this->search_id_karyawan = "";
        $this->search_company = "";
        $this->search_placement = "";
        $this->search_jabatan = "";
        $this->search_etnis = "";
        $this->search_department = "";
        $this->search_tanggal_bergabung = "";
        $this->search_gaji_pokok = "";
        $this->search_gaji_overtime = "";
        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
    }





    public function no_edit()
    {
        // $this->dispatch('error', message: 'Data Karyawan ini Tidak dapat di Update');
        $this->dispatch(
            'message',
            type: 'error',
            title: 'Data Karyawan ini Tidak dapat di Update',
            position: 'center'
        );
    }

    public function confirmDelete($id)
    {
        $this->id = $id;
        $this->dispatch('swal:confirm', [
            'title' => 'Apakah Anda Yakin',
            'text' => 'isi text dengan apa?',
            'id' => $id,
        ]);
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
    public function updatingSearchPlacement()
    {
        $this->resetPage();
    }
    public function updatingSearchDepartment()
    {

        $this->resetPage();
    }
    public function updatingSearchEtnis()
    {
        $this->resetPage();
    }
    public function updatingSearchJabatan()
    {
        $this->resetPage();
    }

    public function updatingSearchCompany()
    {
        $this->resetPage();
    }

    public function excel()
    {
        $nama_file = "";
        // switch ($this->selected_company) {

        //     case '0':
        //         $nama_file = "semua_karyawan.xlsx";
        //         break;
        //     case '1':
        //         $nama_file = "Karyawan_Pabrik-1.xlsx";
        //         break;
        //     case '2':
        //         $nama_file = "Karyawan_Pabrik-2.xlsx";
        //         break;
        //     case '3':
        //         $nama_file = "Karyawan_Kantor.xlsx";
        //         break;
        //     case '4':
        //         $nama_file = "Karyawan_ASB.xlsx";
        //         break;
        //     case '5':
        //         $nama_file = "Karyawan_DPA.xlsx";
        //         break;
        //     case '6':
        //         $nama_file = "Karyawan_YCME.xlsx";
        //         break;
        //     case '7':
        //         $nama_file = "Karyawan_YEV.xlsx";
        //         break;
        //     case '8':
        //         $nama_file = "Karyawan_YIG.xlsx";
        //         break;
        //     case '9':
        //         $nama_file = "Karyawan_YSM.xlsx";
        //         break;
        //     case '10':
        //         $nama_file = "Karyawan_YAM.xlsx";
        //         break;
        // }

        $placement_fn = nama_placement($this->search_placement);
        $company_fn = nama_company($this->search_company);

        if ($placement_fn && $company_fn) {
            $nama_file = "Karyawan_Placement_" .  $placement_fn . '_Company_' . $company_fn . '.xlsx';
        } elseif ($placement_fn) {
            $nama_file = "Karyawan_Placement_" .  $placement_fn . '.xlsx';
        } elseif ($company_fn) {
            $nama_file = "Karyawan_Company_" .  $company_fn . '.xlsx';
        } else {
            $nama_file = 'Seluruh_Karyawan.xlsx';
        }

        return Excel::download(new karyawanExport($this->search_placement, $this->search_company, $this->selectStatus), $nama_file);
    }

    public function getPayrollQuery($statuses, $search = null, $placement_id = null, $company_id = null)
    {
        return Karyawan::query()
            ->whereIn('status_karyawan', $statuses)
            ->when($search, function ($query) use ($search) {
                $query

                    ->where('id_karyawan', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('jabatan_id', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('etnis', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('company_id', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('metode_penggajian', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->when($placement_id, function ($query) use ($placement_id) {
                $query->where('placement_id', $placement_id);
            })
            ->when($company_id, function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })

            ->orderBy($this->columnName, $this->direction);
    }


    public function render()
    {
        if ($this->selectStatus == 1) {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan'];
        } elseif ($this->selectStatus == 2) {
            $statuses = ['Resigned'];
        } elseif ($this->selectStatus == 3) {
            $statuses = ['Blacklist'];
        } else {
            $statuses = ['PKWT', 'PKWTT', 'Dirumahkan', 'Resigned', 'Blacklist'];
        }


        $placements = Karyawan::whereIn('status_karyawan', $statuses)
            // ->when($this->search_department, function ($query) {
            //     $query->where('department_id', $this->search_department);
            // })

            // ->when($this->search_company, function ($query) {
            //     $query->where('company_id', trim($this->search_company));
            // })
            // ->when($this->search_jabatan, function ($query) {
            //     $query->where('jabatan_id', trim($this->search_jabatan));
            // })
            ->pluck('placement_id')->unique();

        // $placements = Placement::all();

        $departments = Karyawan::whereIn('status_karyawan', $statuses)
            ->when($this->search_placement, function ($query) {
                $query->where('placement_id', $this->search_placement);
            })

            ->when($this->search_company, function ($query) {
                $query->where('company_id', trim($this->search_company));
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan_id', trim($this->search_jabatan));
            })
            ->pluck('department_id')->unique();

        $companies = Karyawan::whereIn('status_karyawan', $statuses)
            ->when($this->search_placement, function ($query) {
                $query->where('placement_id', $this->search_placement);
            })
            ->when($this->search_department, function ($query) {
                $query->where('department_id', trim($this->search_department));
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan_id', trim($this->search_jabatan));
            })
            ->pluck('company_id')->unique();

        $jabatans = Karyawan::whereIn('status_karyawan', $statuses)
            ->when($this->search_placement, function ($query) {
                $query->where('placement_id', $this->search_placement);
            })
            ->when($this->search_department, function ($query) {
                $query->where('department_id', trim($this->search_department));
            })
            ->when($this->search_company, function ($query) {
                $query->where('company_id', trim($this->search_company));
            })
            ->pluck('jabatan_id')->unique();

        $etnises = Karyawan::whereIn('status_karyawan', $statuses)
            ->when($this->search_placement, function ($query) {
                $query->where('placement_id', $this->search_placement);
            })
            ->when($this->search_department, function ($query) {
                $query->where('department_id', trim($this->search_department));
            })
            ->when($this->search_company, function ($query) {
                $query->where('company_id', trim($this->search_company));
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan_id', trim($this->search_jabatan));
            })
            ->pluck('etnis')
            ->unique()
            ->sort();



        $datas = Karyawan::query()
            ->whereIn('status_karyawan', $statuses)
            ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
            ->when($this->search_id_karyawan, function ($query) {
                $query->where('id_karyawan', trim($this->search_id_karyawan));
            })

            ->when($this->search_company, function ($query) {
                $query->where('company_id', $this->search_company);
            })

            ->when($this->search_placement, function ($query) {
                $query->where('placement_id', $this->search_placement);
            })
            ->when($this->search_jabatan, function ($query) {
                $query->where('jabatan_id', $this->search_jabatan);
            })
            ->when($this->search_etnis, function ($query) {
                if ($this->search_etnis == 'kosong') {
                    $query->where('etnis', null)->orWhere('etnis', '');
                } else {
                    $query->where('etnis', $this->search_etnis);
                }
            })
            ->when($this->search_department, function ($query) {
                $query->where('department_id', trim($this->search_department));
            })

            ->orderBy($this->columnName, $this->direction)
            ->paginate($this->perpage);
        $this->cx++;
        // return view('livewire.karyawanindexwr', compact(['datas', 'departments', 'jabatans', 'etnises', 'companies', 'jabatans']));

        return view('livewire.karyawanindexwr', [
            'datas' => $datas,
            'placements' => $placements,
            'departments' => $departments,
            'jabatans' => $jabatans,
            'companies' => $companies,
            'etnises' => $etnises,
        ]);
    }
}
