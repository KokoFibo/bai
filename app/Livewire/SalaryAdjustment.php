<?php

namespace App\Livewire;

use App\Exports\SalaryAdjustmentExport;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class SalaryAdjustment extends Component
{
    use WithPagination;
    public $today;
    protected $paginationTheme = 'bootstrap';

    public $pilihLamaKerja, $gaji_rekomendasi;
    public $gaji, $gaji_pokok;
    public $id, $nama;
    public $search_id_karyawan;
    public $search_nama;
    public $search_company;
    public $search_placement;
    public $search_department;
    public $search_jabatan;
    public $search_status;
    public $search_tanggal_bergabung;
    public $search_gaji_pokok;
    public $search_gaji_overtime;
    public $columnName = 'id_karyawan';
    public $direction = 'desc';

    public function excel()
    {
        $nama_file = 'Penyesuaian_Gaji_' . $this->pilihLamaKerja . '_Bulan_Kerja.xlsx';
        // return Excel::download(new SalaryAdjustmentExport($this->pilihLamaKerja), $nama_file);
        return Excel::download(new SalaryAdjustmentExport($this->pilihLamaKerja, $this->search_placement), $nama_file);
    }

    public function adjust()
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
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    // ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    // ->whereNotIn('departemen', ['EXIM', 'GA'])

                    ->whereNotIn('department_id', [3, 5])

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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->get();
                $this->gaji_rekomendasi = 2100000;
                break;

            case "4":

                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->get();



                $this->gaji_rekomendasi = 2200000;
                break;

            case "5":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->get();
                $this->gaji_rekomendasi = 2300000;
                break;

            case "6":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->get();
                $this->gaji_rekomendasi = 2400000;
                break;

            case "7":
                // $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                //     ->where('gaji_pokok', '<', 2500000)
                //     ->where('metode_penggajian', 'Perjam')
                //     ->whereNot('gaji_pokok', 0)
                //     ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                //     ->whereNotIn('department_id', [3, 5])->get();

                $data2 = Karyawan::where(function ($query) use ($bulan7) {
                    $query->whereMonth('tanggal_bergabung', $bulan7->format('m'))
                        ->orWhere('tanggal_bergabung', '<=', Carbon::now()->subMonths(8));
                })
                    ->whereDate('gaji_pokok', '<', 2500000)
                    ->where('gaji_pokok', '>', 0) // instead of `whereNot('gaji_pokok', 0)`
                    ->where('metode_penggajian', 'Perjam')
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
                    ->get();

                // $data = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                $data = Karyawan::where(function ($query) use ($bulan7) {
                    $query->whereMonth('tanggal_bergabung', $bulan7->format('m'))
                        ->orWhere('tanggal_bergabung', '<=', Carbon::now()->subMonths(8));
                })
                    ->where('gaji_pokok', '<', 2500000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->get();
                $this->gaji_rekomendasi = 2500000;
                break;
        }

        // dd($data->all());
        $cx = 0;
        foreach ($data as $d) {
            $k = Karyawan::find($d->id);
            // $k->gaji_pokok = $this->gaji_rekomendasi;
            $k->gaji_pokok = $k->gaji_pokok + 100000;
            $k->save();
            $cx++;
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Berhasil Sesuaikan : ' . $cx . ' Data',
        );
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
    public function mount()
    {
        $this->today = now();
        $this->pilihLamaKerja = 3;
        $this->gaji_rekomendasi = 2100000;
        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
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

    public function edit($id)
    {
        $this->gaji = 0;
        $data = Karyawan::find($id);

        $this->gaji_pokok = $data->gaji_pokok;
        $this->id = $id;
        $this->nama = $data->nama;
    }

    public function save()
    {
        $this->gaji = convert_numeric($this->gaji);

        if ($this->gaji < $this->gaji_pokok || $this->gaji > $this->gaji_rekomendasi) {

            // $this->dispatch('error', message: 'Gaji tidak sesuai rekomendasi');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Gaji tidak sesuai rekomendasi',
            );
            return;
        }
        $data = Karyawan::find($this->id);
        $data->gaji_pokok = $this->gaji;
        $data->save();
        $this->gaji = 0;

        // $this->dispatch('success', message: 'Data Gaji Karyawan Sudah di Sesuaikan');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Gaji Karyawan Sudah di Sesuaikan',
        );
    }
    public function refresh()
    {
        $this->search_nama = "";
        $this->search_id_karyawan = "";
        $this->search_company = "";
        $this->search_placement = "";
        $this->search_jabatan = "";
        $this->search_department = "";
        $this->search_tanggal_bergabung = "";
        $this->columnName = 'id_karyawan';
        $this->direction = 'desc';
    }

    public function render()
    {



        $bulan3 = Carbon::now()->startOfMonth()->subMonths(4);
        $bulan4 = Carbon::now()->startOfMonth()->subMonths(5);
        $bulan5 = Carbon::now()->startOfMonth()->subMonths(6);
        $bulan6 = Carbon::now()->startOfMonth()->subMonths(7);
        $bulan7 = Carbon::now()->startOfMonth()->subMonths(8);
        $bulan8 = Carbon::now()->startOfMonth()->subMonths(9);
        $bulan9 = Carbon::now()->startOfMonth()->subMonths(10);

        // dd($bulan3->format('m'));

        switch ($this->pilihLamaKerja) {
            case "3":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    // ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    // ->whereNotIn('departemen', ['EXIM', 'GA'])

                    ->whereNotIn('department_id', [3, 5])

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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2100000;
                break;

            case "4":

                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);



                $this->gaji_rekomendasi = 2200000;
                break;

            case "5":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2300000;
                break;

            case "6":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2400000;
                break;

            case "7":
                // $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                $data2 = Karyawan::where(function ($query) use ($bulan7) {
                    $query->whereMonth('tanggal_bergabung', $bulan7->format('m'))
                        ->orWhere('tanggal_bergabung', '<=', Carbon::now()->subMonths(8));
                })
                    ->where('gaji_pokok', '<', 2500000)
                    ->where('gaji_pokok', '>', 0) // instead of `whereNot('gaji_pokok', 0)`
                    ->where('metode_penggajian', 'Perjam')
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
                    ->get();

                // $data = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                $data = Karyawan::where(function ($query) use ($bulan7) {
                    $query->whereMonth('tanggal_bergabung', $bulan7->format('m'))
                        ->orWhere('tanggal_bergabung', '<=', Carbon::now()->subMonths(8));
                })
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->where('metode_penggajian', 'Perjam')
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                    ->whereNotIn('department_id', [3, 5])
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
                    ->when($this->search_department, function ($query) {
                        $query->where('department_id', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2500000;
                break;

                // case "8":
                // $data2 = Karyawan::where('tanggal_bergabung', '<=', Carbon::now()->subMonths(8))
                //     ->where('gaji_pokok', '<', 2500000)
                // ->where('metode_penggajian','Perjam')   
                // ->whereNot('gaji_pokok', 0)
                //     ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                //     ->whereNotIn('department_id', [3, 5])->get();

                // $data = Karyawan::where('tanggal_bergabung', '<=', Carbon::now()->subMonths(8))
                //     ->where('gaji_pokok', '<', 2500000)
                // ->where('metode_penggajian','Perjam')    
                // ->whereNot('gaji_pokok', 0)
                //     ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
                //     ->whereNotIn('department_id', [3, 5])
                //     ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                //     ->when($this->search_id_karyawan, function ($query) {
                //         $query->where('id_karyawan', trim($this->search_id_karyawan));
                //     })

                //     ->when($this->search_company, function ($query) {
                //         $query->where('company_id', $this->search_company);
                //     })
                //     ->when($this->search_placement, function ($query) {
                //         $query->where('placement_id', $this->search_placement);
                //     })


                //     ->when($this->search_jabatan, function ($query) {
                //         $query->where('jabatan_id', $this->search_jabatan);
                //     })
                //     ->when($this->search_department, function ($query) {
                //         $query->where('department_id', $this->search_department);
                //     })
                //     // ->orderBy('tanggal_bergabung', 'desc')
                //     ->orderBy($this->columnName, $this->direction)
                //     ->paginate(10);
                // $this->gaji_rekomendasi = 2500000;
                // break;
        }

        $jabatans = array();
        $departments = array();
        $companies = array();
        $placements = array();

        $jabatans = array_merge($jabatans, $data2->pluck('jabatan_id')->unique()->toArray());
        $departments = array_merge($departments, $data2->pluck('department_id')->unique()->toArray());
        $companies = array_merge($companies, $data2->pluck('company_id')->unique()->toArray());
        $placements = array_merge($placements, $data2->pluck('placement_id')->unique()->toArray());

        // if ($this->pilihLamaKerja == 7) {
        //     dd($data2);
        // }


        return view('livewire.salary-adjustment', compact('data', 'departments', 'jabatans', 'companies', 'placements'));
    }
}
