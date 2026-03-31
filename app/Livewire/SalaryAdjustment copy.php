<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;

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

            $this->dispatch('error', message: 'Gaji tidak sesuai rekomendasi');
            return;
        }
        $data = Karyawan::find($this->id);
        $data->gaji_pokok = $this->gaji;
        $data->save();
        $this->gaji = 0;

        $this->dispatch('success', message: 'Data Gaji Karyawan Sudah di Sesuaikan');
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

        // $this->pilihLamaKerja = 3;
        // $ninetyDaysAgo = Carbon::now()->subDays(90);
        // $hundredTwentyDaysAgo = Carbon::now()->subDays(120);
        // $hundredFiftyDaysAgo = Carbon::now()->subDays(150);
        // $hundredEigtyDaysAgo = Carbon::now()->subDays(180);
        // $twoHundredTenDaysAgo = Carbon::now()->subDays(210);
        // $twoHundredFortyDaysAgo = Carbon::now()->subDays(240);
        // $twoHundredseventyDaysAgo = Carbon::now()->subDays(270);

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
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan3->format('m'))
                    ->where('gaji_pokok', '<', 2100000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })
                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })
                    ->when($this->search_placement, function ($query) {
                        $query->where('placement', $this->search_placement);
                    })
                    // ->when($this->search_placement, function ($query) {
                    //     if ($this->search_placement == 1) {
                    //         $query->where('placement', 'YCME');
                    //     } elseif ($this->search_placement == 2) {
                    //         $query->where('placement', 'YEV');
                    //     } else {
                    //         $query->whereIn('placement', ['YIG', 'YSM']);
                    //     }
                    // })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2100000;
                break;

            case "4":

                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan4->format('m'))
                    ->where('gaji_pokok', '<', 2200000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })

                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })

                    ->when($this->search_placement, function ($query) {
                        if ($this->search_placement == 1) {
                            $query->where('placement', 'YCME');
                        } elseif ($this->search_placement == 2) {
                            $query->where('placement', 'YEV');
                        } else {
                            $query->whereIn('placement', ['YIG', 'YSM']);
                        }
                    })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);



                $this->gaji_rekomendasi = 2200000;
                break;

            case "5":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan5->format('m'))
                    ->where('gaji_pokok', '<', 2300000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })

                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })

                    ->when($this->search_placement, function ($query) {
                        if ($this->search_placement == 1) {
                            $query->where('placement', 'YCME');
                        } elseif ($this->search_placement == 2) {
                            $query->where('placement', 'YEV');
                        } else {
                            $query->whereIn('placement', ['YIG', 'YSM']);
                        }
                    })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2300000;
                break;

            case "6":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan6->format('m'))
                    ->where('gaji_pokok', '<', 2400000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })

                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })

                    ->when($this->search_placement, function ($query) {
                        if ($this->search_placement == 1) {
                            $query->where('placement', 'YCME');
                        } elseif ($this->search_placement == 2) {
                            $query->where('placement', 'YEV');
                        } else {
                            $query->whereIn('placement', ['YIG', 'YSM']);
                        }
                    })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2400000;
                break;

            case "7":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan7->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })

                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })

                    ->when($this->search_placement, function ($query) {
                        if ($this->search_placement == 1) {
                            $query->where('placement', 'YCME');
                        } elseif ($this->search_placement == 2) {
                            $query->where('placement', 'YEV');
                        } else {
                            $query->whereIn('placement', ['YIG', 'YSM']);
                        }
                    })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2500000;
                break;

            case "8":
                $data2 = Karyawan::whereMonth('tanggal_bergabung', $bulan8->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])->get();

                $data = Karyawan::whereMonth('tanggal_bergabung', $bulan8->format('m'))
                    ->where('gaji_pokok', '<', 2500000)
                    ->whereNot('gaji_pokok', 0)
                    ->whereIn('status_karyawan', ['PKWT', 'PKWTT', 'Dirumahkan'])
                    ->whereNotIn('departemen', ['EXIM', 'GA'])
                    ->where('nama', 'LIKE', '%' . trim($this->search_nama) . '%')
                    ->when($this->search_id_karyawan, function ($query) {
                        $query->where('id_karyawan', trim($this->search_id_karyawan));
                    })

                    ->when($this->search_company, function ($query) {
                        $query->where('company', $this->search_company);
                    })

                    ->when($this->search_placement, function ($query) {
                        if ($this->search_placement == 1) {
                            $query->where('placement', 'YCME');
                        } elseif ($this->search_placement == 2) {
                            $query->where('placement', 'YEV');
                        } else {
                            $query->whereIn('placement', ['YIG', 'YSM']);
                        }
                    })
                    ->when($this->search_jabatan, function ($query) {
                        $query->where('jabatan', $this->search_jabatan);
                    })
                    ->when($this->search_department, function ($query) {
                        $query->where('departemen', $this->search_department);
                    })
                    // ->orderBy('tanggal_bergabung', 'desc')
                    ->orderBy($this->columnName, $this->direction)
                    ->paginate(10);
                $this->gaji_rekomendasi = 2500000;
                break;
        }

        $jabatans = array();
        $departments = array();
        $companies = array();
        $placements = array();

        $jabatans = array_merge($jabatans, $data2->pluck('jabatan')->unique()->toArray());
        $departments = array_merge($departments, $data2->pluck('departemen')->unique()->toArray());
        $companies = array_merge($companies, $data2->pluck('company')->unique()->toArray());
        $placements = array_merge($placements, $data2->pluck('placement')->unique()->toArray());




        return view('livewire.salary-adjustment', compact('data', 'departments', 'jabatans', 'companies', 'placements'));
    }
}
