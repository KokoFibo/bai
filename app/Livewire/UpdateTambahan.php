<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use Illuminate\Support\Facades\DB;

class UpdateTambahan extends Component
{
    public $user_id, $nama_karyawan, $karyawan_id;

    public $tanggal, $uang_makan, $bonus_lain, $thr, $baju_esd, $gelas, $sandal;
    public $seragam, $sport_bra, $hijab_instan, $id_card_hilang, $masker_hijau, $potongan_lain;
    public $year, $month;
    public $id;

    public function getData()
    {
        $data = Bonuspotongan::find($this->id);
        $data_karyawan = Karyawan::find($data->karyawan_id);
        $this->nama_karyawan = $data_karyawan->nama;

        $this->user_id = $data->user_id;
        $this->karyawan_id = $data->karyawan_id;
        $this->uang_makan = number_format($data->uang_makan);
        $this->bonus_lain = number_format($data->bonus_lain);
        $this->thr = number_format($data->thr);
        $this->baju_esd = number_format($data->baju_esd);
        $this->gelas = number_format($data->gelas);
        $this->sandal = number_format($data->sandal);
        $this->seragam = number_format($data->seragam);
        $this->sport_bra = number_format($data->sport_bra);
        $this->hijab_instan = number_format($data->hijab_instan);
        $this->id_card_hilang = number_format($data->id_card_hilang);
        $this->masker_hijau = number_format($data->masker_hijau);
        $this->potongan_lain = number_format($data->potongan_lain);
        $this->tanggal = $data->tanggal;
    }
    public function mount($id)
    {
        $this->id = $id;
        $this->getData();
    }

    public function update()
    {
        $this->uang_makan = convert_numeric($this->uang_makan);
        $this->bonus_lain = convert_numeric($this->bonus_lain);
        $this->thr = convert_numeric($this->thr);
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
            $this->thr == null &&
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

        $data = Bonuspotongan::find($this->id);
        $data->uang_makan = $this->uang_makan;
        $data->bonus_lain = $this->bonus_lain;
        $data->thr = $this->thr;
        $data->baju_esd = $this->baju_esd;
        $data->gelas = $this->gelas;
        $data->sandal = $this->sandal;
        $data->seragam = $this->seragam;
        $data->sport_bra = $this->sport_bra;
        $data->hijab_instan = $this->hijab_instan;
        $data->id_card_hilang = $this->id_card_hilang;
        $data->masker_hijau = $this->masker_hijau;
        $data->potongan_lain = $this->potongan_lain;
        $data->tanggal = $this->tanggal;

        $data->save();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data sudah di Update',
        );
        $this->getData();
        // $this->clear_data();
        // return redirect('/tambahan');
    }

    public function clear_data()
    {
        $this->user_id = null;
        $this->nama_karyawan = null;
        $this->uang_makan = null;
        // $this->bonus = null;
        $this->bonus_lain = null;
        $this->thr = null;
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

        return view('livewire.update-tambahan')->layout('layouts.appeloe');
    }
}
