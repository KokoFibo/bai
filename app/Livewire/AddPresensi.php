<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Yfrekappresensi;
use Livewire\Component;

class AddPresensi extends Component
{
    public $karyawan_id = null;
    public $user_id = '';
    public $date = '';
    public $nama = '';

    /**
     * Dipanggil ketika user_id berubah.
     */
    public function updatedUserId($value)
    {
        // Bersihkan data karyawan sebelumnya
        $this->resetKaryawan();

        // Jangan query jika ID kosong
        if (blank($value)) {
            return;
        }

        $data = Karyawan::query()
            ->select('id', 'id_karyawan', 'nama')
            ->where('id_karyawan', $value)
            ->first();

        if (!$data) {
            return;
        }

        $this->nama = $data->nama;
        $this->karyawan_id = $data->id;
    }

    /**
     * Simpan data presensi.
     */
    public function save()
    {
        $this->validate([
            'user_id' => [
                'required',
            ],
            'karyawan_id' => [
                'required',
                'exists:karyawans,id',
            ],
            'nama' => [
                'required',
            ],
            'date' => [
                'required',
                'date',
            ],
        ], [
            'user_id.required' => 'ID User wajib diisi.',
            'karyawan_id.required' => 'Karyawan tidak ditemukan.',
            'karyawan_id.exists' => 'Data karyawan tidak valid.',
            'nama.required' => 'Data karyawan belum ditemukan.',
            'date.required' => 'Tanggal presensi wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
        ]);

        // Cek apakah presensi sudah ada
        $exists = Yfrekappresensi::query()
            ->where('karyawan_id', $this->karyawan_id)
            ->whereDate('date', $this->date)
            ->exists();

        if ($exists) {
            $this->addError(
                'date',
                'Presensi untuk karyawan ini pada tanggal tersebut sudah ada.'
            );

            return;
        }

        // Simpan data
        Yfrekappresensi::create([
            'karyawan_id' => $this->karyawan_id,
            'user_id' => $this->user_id,
            'date' => $this->date,
        ]);

        // Reset form
        $this->reset([
            'karyawan_id',
            'user_id',
            'date',
            'nama',
        ]);

        // Kirim notifikasi
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Presensi berhasil ditambahkan.',
        );
    }

    /**
     * Reset data karyawan.
     */
    private function resetKaryawan()
    {
        $this->nama = '';
        $this->karyawan_id = null;
    }

    public function render()
    {
        return view('livewire.add-presensi');
    }
}
