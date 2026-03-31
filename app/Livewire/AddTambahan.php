<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Bonuspotongan;
use Illuminate\Support\Facades\DB;

class AddTambahan extends Component
{
    public $karyawan = [];
    public $tanggal, $uang_makan, $bonus_lain, $thr, $baju_esd, $gelas, $sandal;
    public $seragam, $sport_bra, $hijab_instan, $id_card_hilang, $masker_hijau, $potongan_lain;
    public $year, $month;

    public function mount()
    {
        $this->karyawan = [
            ['user_id' => '', 'nama_karyawan' => '']
        ];

        $this->year = now()->year;
        $this->month = now()->month;
        $this->tanggal = now()->toDateString();
    }

    public function updated($propertyName, $value)
    {
        // Cek jika yang diperbarui adalah user_id dalam array karyawan
        if (preg_match('/karyawan\.(\d+)\.user_id/', $propertyName, $matches)) {
            $index = $matches[1];

            // Ambil data karyawan berdasarkan ID yang dimasukkan
            $user = Karyawan::where('id_karyawan', $value)->first();
            $is_exist = Bonuspotongan::where('user_id', $value)
                ->whereMonth('tanggal',  Carbon::parse($this->tanggal))
                ->whereYear('tanggal',  Carbon::parse($this->tanggal))
                ->count();

            // Update nama_karyawan di array berdasarkan index
            if ($user) {
                if ($is_exist > 0) {
                    $msg = $user->nama . " - Data ini sudah ada";
                } else {
                    $msg = $user->nama;
                }
            } else {
                $msg = "User tidak ditemukan";
            }
            $this->karyawan[$index]['nama_karyawan'] = $user ? $msg : "Tidak ditemukan";
        }
    }


    public function addRow()
    {
        $this->karyawan[] = ['user_id' => '', 'nama_karyawan' => ''];
    }

    public function removeRow($index)
    {
        unset($this->karyawan[$index]);
        $this->karyawan = array_values($this->karyawan); // Reset index array
    }

    public function updatedKaryawan($key, $value)
    {
        if (str_contains($key, '.user_id')) {
            $index = explode('.', $key)[1];

            // Ambil data karyawan dari database
            $user = Karyawan::where('id_karyawan', $value)->first();

            // Update nama_karyawan di index yang sesuai
            $this->karyawan[$index]['nama_karyawan'] = $user->nama ?? 'Tidak ditemukan';
        }
    }

    public function save()
    {
        foreach ($this->karyawan as $data) {
            $is_exist = Bonuspotongan::where('user_id', $data['user_id'])
                ->whereMonth('tanggal',  Carbon::parse($this->tanggal))
                ->whereYear('tanggal',  Carbon::parse($this->tanggal))
                ->count();
            if ($is_exist > 0) {
                $namaBulan = Carbon::parse($this->tanggal)->translatedFormat('F');
                $namaTahun = Carbon::parse($this->tanggal)->format('Y');
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'ID : ' . $data['user_id'] . ' sudah terdapat pada database bulan ' . $namaBulan . ' ' . $namaTahun,
                    //  . nama_bulan($this->tanggal) . tahun($this->tanggal)
                );
                return;
            }
        }
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
        foreach ($this->karyawan as $data) {
            if (empty($data['user_id']) || empty($data['nama_karyawan'])) {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'Pastikan semua ID karyawan telah diisi dengan benar'
                );
                return;
            }

            $record = new Bonuspotongan();
            $record->user_id = $data['user_id'];
            $record->karyawan_id = Karyawan::where('id_karyawan', $data['user_id'])->value('id');
            $record->uang_makan = $this->uang_makan;
            $record->bonus_lain = $this->bonus_lain;
            $record->thr = $this->thr;
            $record->baju_esd = $this->baju_esd;
            $record->gelas = $this->gelas;
            $record->sandal = $this->sandal;
            $record->seragam = $this->seragam;
            $record->sport_bra = $this->sport_bra;
            $record->hijab_instan = $this->hijab_instan;
            $record->id_card_hilang = $this->id_card_hilang;
            $record->masker_hijau = $this->masker_hijau;
            $record->potongan_lain = $this->potongan_lain;
            $record->tanggal = date('Y-m-d', strtotime($this->tanggal));
            $record->save();
        }

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data sudah disimpan'
        );

        $this->resetForm();
    }

    public function cancel()
    {
        $this->resetForm();
        return redirect('/tambahan');
    }

    public function resetForm()
    {
        $this->karyawan = [['user_id' => '', 'nama_karyawan' => '']];
        $this->uang_makan = null;
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

    public function render()
    {
        return view('livewire.add-tambahan')->layout('layouts.appeloe');
    }
}
