<?php

namespace App\Livewire;

use App\Models\Yfrekappresensi;
use Carbon\Carbon;
use Livewire\Component;

class Yfdeletetanggalpresensiwr extends Component
{
    public $tanggal = null;
    public $lokasi = null;

    /**
     * Konversi tanggal ke format Y-m-d.
     *
     * Mendukung:
     * - 17 Sep 2026
     * - 2026-09-17
     */
    public function convertDate($date): ?string
    {
        if (blank($date)) {
            return null;
        }

        try {
            // Jika sudah dalam format Y-m-d
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return Carbon::createFromFormat('Y-m-d', $date)
                    ->format('Y-m-d');
            }

            // Jika format d M Y, contoh: 17 Sep 2026
            return Carbon::createFromFormat('d M Y', $date)
                ->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Hapus seluruh data presensi berdasarkan tanggal.
     */
    public function delete()
    {
        // Validasi input
        $this->validate(
            [
                'tanggal' => ['required'],
            ],
            [
                'tanggal.required' => 'Tanggal wajib dipilih.',
            ]
        );

        // Konversi tanggal
        $tanggal = $this->convertDate($this->tanggal);

        if (!$tanggal) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Format tanggal tidak valid.'
            );

            return;
        }

        /*
         * Jika kolom `date` di database bertipe DATE,
         * gunakan where() biasa agar index database
         * dapat digunakan secara optimal.
         */
        $deleted = Yfrekappresensi::query()
            ->where('date', $tanggal)
            ->delete();

        // Tidak ada data yang dihapus
        if ($deleted === 0) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data presensi tidak ditemukan.'
            );

            return;
        }

        // Berhasil dihapus
        $this->dispatch(
            'message',
            type: 'success',
            title: "{$deleted} data presensi berhasil dihapus."
        );

        // Bersihkan tanggal
        $this->reset('tanggal');
    }

    /**
     * Keluar dari halaman.
     */
    public function exit()
    {
        $this->reset();

        return redirect()->to('/newpresensi');
    }

    public function render()
    {
        return view('livewire.yfdeletetanggalpresensiwr');
    }
}
