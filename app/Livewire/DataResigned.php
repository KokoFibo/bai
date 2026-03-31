<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;

class DataResigned extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $month;
    public $year;
    public $today;
    public $status_karyawan;
    public $cx;
    public $sama;



    public function mount()
    {
        $this->cx = 0;
        $this->today = now();
        $this->sama = '=';
        $this->year = now()->year;
        $this->month = now()->month;
        $this->status_karyawan = "Resigned";
    }

    public function updatingSama()
    {
        $this->resetPage();
    }
    public function updatingStatusKaryawan()
    {

        $this->resetPage();
    }



    public function delete()
    {
        $data = Karyawan::where('status_karyawan', $this->status_karyawan)
            ->when($this->status_karyawan == "Resigned", function ($query) {
                return $query->where(function ($query) {
                    $query->whereMonth('tanggal_resigned', $this->sama, $this->month)
                        ->orWhere(function ($query) {
                            $query->whereMonth('tanggal_resigned', '=', $this->month)
                                ->whereYear('tanggal_resigned', $this->sama, $this->year);
                        });
                })->orderBy('tanggal_resigned', 'desc');
            })
            ->when($this->status_karyawan == "Blacklist", function ($query) {
                return $query->where(function ($query) {
                    $query->whereMonth('tanggal_blacklist', $this->sama, $this->month)
                        ->orWhere(function ($query) {
                            $query->whereMonth('tanggal_blacklist', '=', $this->month)
                                ->whereYear('tanggal_blacklist', $this->sama, $this->year);
                        });
                })->orderBy('tanggal_blacklist', 'desc');
            })->get();
        $cx = 0;
        foreach ($data as $d) {
            if ($d->email != acakEmail($d->nama, $d->id_karyawan)) {
                $cx++;
                $data_hapus = User::where('username', $d->id_karyawan)->first();
                $data_karyawan = Karyawan::find($d->id);
                $data_id = User::find($data_hapus->id);

                $data_id->email = acakEmail($d->nama, $d->id_karyawan);
                $data_id->password = acakPassword($d->nama);
                $data_karyawan->email = acakEmail($d->nama, $d->id_karyawan);
                $data_id->save();
                $data_karyawan->save();
            }
        }

        if ($cx > 0) {
            $this->dispatch('success', message: 'Data Karyawan resigned/blacklist sudah di acak email & passwordnya');
        } else {
            $this->dispatch('error', message: 'Data Karyawan resigned/blacklist sudah di acak email & passwordnya');
        }
    }




    public function render()
    {
        $data = Karyawan::where('status_karyawan', $this->status_karyawan)
            ->when($this->status_karyawan == "Resigned", function ($query) {
                return $query->where(function ($query) {
                    $query->whereMonth('tanggal_resigned', $this->sama, $this->month)
                        ->orWhere(function ($query) {
                            $query->whereMonth('tanggal_resigned', '=', $this->month)
                                ->whereYear('tanggal_resigned', $this->sama, $this->year);
                        });
                })->orderBy('tanggal_resigned', 'desc');
            })
            ->when($this->status_karyawan == "Blacklist", function ($query) {
                return $query->where(function ($query) {
                    $query->whereMonth('tanggal_blacklist', $this->sama, $this->month)
                        ->orWhere(function ($query) {
                            $query->whereMonth('tanggal_blacklist', '=', $this->month)
                                ->whereYear('tanggal_blacklist', $this->sama, $this->year);
                        });
                })->orderBy('tanggal_blacklist', 'desc');
            })
            ->paginate(10);


        return view('livewire.data-resigned', compact(['data']));
    }
}
