<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use Illuminate\Support\Facades\DB;

class AddTambahan extends Component
{

    // public $id, $is_edit, $id_tambahan, $modal, $search, $user_id, $nama_karyawan, $karyawan_id;
    public $id,  $user_id, $nama_karyawan, $karyawan_id;

    public $tanggal, $uang_makan, $bonus_lain, $baju_esd, $gelas, $sandal;
    public $seragam, $sport_bra, $hijab_instan, $id_card_hilang, $masker_hijau, $potongan_lain;
    public $year, $month;
    // public $columnName = 'user_id';
    // public $direction = 'desc';
    // public $select_month, $select_year;

    public function mount()
    {
        $this->modal = false;
        $this->is_edit = false;
        $this->year = now()->year;
        $this->month = now()->month;
        $this->tanggal = now()->toDateString();
        $this->columnName = 'user_id';
        $this->direction = 'desc';


        $this->select_year = Bonuspotongan::select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year')
            ->toArray();

        $this->select_month = Bonuspotongan::select(DB::raw('MONTH(tanggal) as month'))
            ->distinct()
            ->pluck('month')
            ->toArray();
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
        $this->uang_makan = convert_numeric($this->uang_makan);
        $this->bonus_lain = convert_numeric($this->bonus_lain);
        $this->baju_esd = convert_numeric($this->baju_esd);
        $this->gelas = convert_numeric($this->gelas);
        $this->sandal = convert_numeric($this->sandal);
        $this->seragam = convert_numeric($this->seragam);
        $this->sport_bra = convert_numeric($this->sport_bra);
        $this->hijab_instan = convert_numeric($this->hijab_instan);
        $this->id_card_hilang = convert_numeric($this->id_card_hilang);
        $this->masker_hijau = convert_numeric($this->masker_hijau);
        $this->potongan_lain = convert_numeric($this->potongan_lain);

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
        $data = new Bonuspotongan();
        $data->user_id = $this->user_id;

        $data->karyawan_id = $this->karyawan_id;


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

        // $this->dispatch('success', message: 'Data sudah di Add');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data sudah di Add',
        );

        $this->clear_data();
        // return redirect('/tambahan');
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

    public function cancel()
    {
        $this->clear_data();
        return redirect('/tambahan');
    }

    public function render()
    {
        return view('livewire.add-tambahan')->layout('layouts.appeloe');
    }
}
