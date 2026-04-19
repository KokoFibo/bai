<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class Cleanemail extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $progress = 0;
    public $isProcessing = false;

    // 🔄 reset pagination saat search berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // 🚀 CLEAN EMAIL PRO
    public function cleanEmailPro()
    {
        $this->isProcessing = true;
        $this->progress = 0;

        DB::transaction(function () {

            // ======================
            // STEP 1: CLEAN CEPAT SQL
            // ======================
            DB::statement("
            UPDATE karyawans
            SET email = TRIM(REPLACE(REPLACE(email, 'resigned_', ''), 'blacklist_', ''))
            WHERE email LIKE '%resigned_%'
               OR email LIKE '%blacklist_%'
        ");

            $this->progress = 30;

            // ======================
            // STEP 2: FIX EMAIL KOSONG / INVALID
            // ======================
            $kosong = Karyawan::whereNull('email')
                ->orWhere('email', '')
                ->orWhere('email', 'not like', '%@%')
                ->get();

            foreach ($kosong as $item) {
                do {
                    $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                    $newEmail = "email_kosong_{$random}@email.com";
                } while (Karyawan::where('email', $newEmail)->exists());

                $item->update(['email' => $newEmail]);
            }

            $this->progress = 60;

            // ======================
            // STEP 3: FIX DUPLICATE (SMART)
            // ======================
            $duplicates = Karyawan::select('email')
                ->groupBy('email')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('email');

            foreach ($duplicates as $email) {

                $rows = Karyawan::where('email', $email)->get();

                // 🔥 pisahkan status
                $resignedBlacklist = $rows->filter(function ($item) {
                    return in_array(strtolower($item->status_karyawan), ['resigned', 'blacklist']);
                });

                $aktif = $rows->diff($resignedBlacklist);

                // ======================
                // KASUS 1: ADA resigned / blacklist
                // ======================
                if ($resignedBlacklist->count() > 0) {

                    foreach ($resignedBlacklist as $item) {
                        do {
                            $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                            $newEmail = "email_kosong_{$random}@email.com";
                        } while (Karyawan::where('email', $newEmail)->exists());

                        $item->update(['email' => $newEmail]);
                    }
                } else {
                    // ======================
                    // KASUS 2: semua aktif
                    // ======================

                    foreach ($rows->skip(1) as $item) {
                        do {
                            $random = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                            $newEmail = "email_kosong_{$random}@email.com";
                        } while (Karyawan::where('email', $newEmail)->exists());

                        $item->update(['email' => $newEmail]);
                    }
                }
            }

            $this->progress = 100;
        });

        $this->isProcessing = false;

        session()->flash('success', 'Email berhasil dibersihkan & duplicate ditangani!');
    }

    // 📊 DATA TABLE
    public function render()
    {
        $data = Karyawan::query()
            ->where(function ($q) {
                $q->where('email', 'like', '%resigned_%')
                    ->orWhere('email', 'like', '%blacklist_%')
                    ->orWhere('email', 'like', '%email_kosong_%')
                    ->orWhere('email', 'like', '');
            })
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('id_karyawan', 'like', '%' . $this->search . '%')
                        ->orWhere('status_karyawan', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(15);

        return view('livewire.cleanemail', [
            'data' => $data
        ]);
    }
}
