<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use Illuminate\Support\Facades\DB;

class Tambahanwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $id, $is_edit, $id_tambahan, $modal, $search, $user_id, $nama_karyawan, $karyawan_id;
    public $tanggal, $uang_makan, $bonus_lain, $baju_esd, $gelas, $sandal;
    public $seragam, $sport_bra, $hijab_instan, $id_card_hilang, $masker_hijau, $potongan_lain;
    public $year, $month;
    public $columnName = 'user_id';
    public $direction = 'desc';
    public $select_month, $select_year;

    public function refresh()
    {
        // $this->columnName = 'user_id';
        // $this->direction = 'desc';
        // $this->year = now()->year;
        // $this->month = now()->month;
        $this->mount();
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

    public function mount()
    {
        $this->modal = false;
        $this->is_edit = false;
        // $this->year = now()->year;
        // $this->month = now()->month;
        $this->tanggal = now()->toDateString();
        $this->columnName = 'user_id';
        $this->direction = 'desc';
        $temp = Bonuspotongan::orderBy('tanggal', 'desc')->first();
        $this->year = $temp->tanggal ? Carbon::parse($temp->tanggal)->year : now()->year;
        $this->month = $temp->tanggal ? Carbon::parse($temp->tanggal)->month : now()->month;

        $this->select_year = Bonuspotongan::select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $this->select_month = Bonuspotongan::select(DB::raw('MONTH(tanggal) as month'))
            ->distinct()
            ->pluck('month')
            ->toArray();
    }

    public function add()
    {
        // $data_karyawan = Karyawan::where('id_karyawan', ($id_karyawan))->first();
        // $this->id = $data_karyawan->id ;
        // $this->user_id = $id_karyawan;
        // $this->nama_karyawan = $data_karyawan->nama;
        // $this->tanggal =  date('Y-m-d');
        $this->modal = true;
    }

    public function updatedUserId()
    {
        try {
            $data = Karyawan::where('id_karyawan', $this->user_id)->first();

            $this->karyawan_id = $data->id;
            $this->nama_karyawan = $data->nama;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function save()
    {
        if (
            $this->uang_makan == null &&
            // $this->bonus == null &&
            $this->bonus_lain == null &&
            $this->baju_esd == null &&
            $this->gelas == null &&
            $this->sandal == null &&
            $this->seragam == null &&
            $this->sport_bra == null &&
            $this->hijab_instan == null &&
            $this->id_card_hilang == null &&
            $this->masker_hijau == null &&
            $this->potongan_lain == null
        ) {
            // $this->dispatch('error', message: 'Data tidak disimpan');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data tidak disimpan',
            );
            return;
        }
        if ($this->is_edit == false) {
            $data = new Bonuspotongan();
            $data->user_id = $this->user_id;

            $data->karyawan_id = $this->karyawan_id;
        } else {
            $data = Bonuspotongan::find($this->id_tambahan);
            // $data->user_id = $this->user_id;
            // $data->karyawan_id = $this->karyawan_id;
        }

        $data->uang_makan = $this->uang_makan;
        $data->bonus_lain = $this->bonus_lain;
        $data->baju_esd = $this->baju_esd;
        $data->gelas = $this->gelas;
        $data->sandal = $this->sandal;
        $data->seragam = $this->seragam;
        $data->sport_bra = $this->sport_bra;
        $data->hijab_instan = $this->hijab_instan;
        $data->id_card_hilang = $this->id_card_hilang;
        $data->masker_hijau = $this->masker_hijau;
        $data->potongan_lain = $this->potongan_lain;
        $data->tanggal = date('Y-m-d', strtotime($this->tanggal));
        $data->save();
        if ($this->is_edit == false) {
            // $this->dispatch('success', message: 'Data sudah di Add');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data sudah di Add',
            );
        } else {
            // $this->dispatch('success', message: 'Data sudah di Update');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data sudah di Update',
            );
        }
        $this->is_edit = false;
        $this->modal = false;
        $this->clear_data();
    }

    public function cancel()
    {
        $this->is_edit = false;
        $this->modal = false;
        $this->clear_data();
    }

    public function clear_data()
    {
        $this->user_id = null;
        $this->nama_karyawan = null;
        $this->uang_makan = null;
        // $this->bonus = null;
        $this->bonus_lain = null;
        $this->baju_esd = null;
        $this->gelas = null;
        $this->sandal = null;
        $this->seragam = null;
        $this->sport_bra = null;
        $this->hijab_instan = null;
        $this->id_card_hilang = null;
        $this->masker_hijau = null;
        $this->potongan_lain = null;
        $this->tanggal = now()->toDateString();
    }

    public function update($id)
    {
        $this->is_edit = true;
        $this->modal = true;

        $this->id_tambahan = $id;
        $data_tambahan = Bonuspotongan::find($id);
        $data_karyawan = Karyawan::find($data_tambahan->karyawan_id);
        $this->nama_karyawan = $data_karyawan->nama;

        $this->user_id = $data_tambahan->user_id;
        $this->karyawan_id = $data_tambahan->karyawan_id;
        $this->uang_makan = $data_tambahan->uang_makan;
        $this->bonus_lain = $data_tambahan->bonus_lain;
        $this->baju_esd = $data_tambahan->baju_esd;
        $this->gelas = $data_tambahan->gelas;
        $this->sandal = $data_tambahan->sandal;
        $this->seragam = $data_tambahan->seragam;
        $this->sport_bra = $data_tambahan->sport_bra;
        $this->hijab_instan = $data_tambahan->hijab_instan;
        $this->id_card_hilang = $data_tambahan->id_card_hilang;
        $this->masker_hijau = $data_tambahan->masker_hijau;
        $this->potongan_lain = $data_tambahan->potongan_lain;
        $this->tanggal = $data_tambahan->tanggal;
        // $this->tanggal = $data_tambahan->tanggal;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function delete($id)
    {
        $data_tambahan = Bonuspotongan::find($id);
        $data_tambahan->delete();
        // $this->dispatch('success', message: 'Data sudah di Delete');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data sudah di Delete',
        );
    }

    public function updatedYear()
    {
        $this->select_month = Bonuspotongan::select(DB::raw('MONTH(tanggal) as month'))->whereYear('tanggal', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        $this->month = $this->select_month[0];
    }

    public function render()
    {

        $this->select_year = Bonuspotongan::select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $this->select_month = Bonuspotongan::select(DB::raw('MONTH(tanggal) as month'))->whereYear('tanggal', $this->year)
            ->distinct()
            ->pluck('month')
            ->toArray();

        // $month_start = Carbon::now()
        //     ->startOfMonth()
        //     ->subMonth(1);
        // $month_end = Carbon::now()->endOfMonth();
        $data = Bonuspotongan::select(['bonuspotongans.*', 'karyawans.nama', 'karyawans.jabatan_id'])
            ->join('karyawans', 'bonuspotongans.karyawan_id', '=', 'karyawans.id')
            // ->whereBetween('bonuspotongans.tanggal', [$month_start, $month_end])
            ->whereMonth('bonuspotongans.tanggal', $this->month)
            ->whereYear('bonuspotongans.tanggal', $this->year)

            ->where(function ($query) {
                $query->where('karyawans.nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('karyawans.jabatan_id', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('bonuspotongans.user_id', trim($this->search));
            })

            // ->where('bonuspotongans.tanggal', '2024-01-22')
            // ->where('karyawans.nama', 'LIKE', '%' . trim($this->search) . '%')
            // ->orWhere('karyawans.jabatan', 'LIKE', '%' . trim($this->search) . '%')
            // ->orWhere('bonuspotongans.user_id', trim($this->search))
            ->orderBy($this->columnName, $this->direction)
            ->paginate(10);

        return view('livewire.tambahanwr', compact('data'));
    }
}
