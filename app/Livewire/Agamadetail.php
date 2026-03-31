<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;

class Agamadetail extends Component
{
    public $placements;
    public $selectedPlacement;
    public $agamas = [];
    public $agama_kosong = [];

    public function mount()
    {
        // Ambil semua placements
        $this->placements = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->whereNotNull('placement_id')
            ->select('placement_id')
            ->distinct()
            ->pluck('placement_id');

        // Set default kosong (semua placement)
        $this->selectedPlacement = '';

        // Load data awal (semua placement)
        $this->loadData();
    }

    public function updatedSelectedPlacement()
    {
        // Otomatis load ulang ketika dropdown berubah
        $this->loadData();
    }

    private function loadData()
    {
        // 1. LOAD DATA AGAMA (statistik)
        $queryAgama = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT']);

        // Filter berdasarkan placement jika dipilih
        if ($this->selectedPlacement) {
            $queryAgama->where('placement_id', $this->selectedPlacement);
        }

        $this->agamas = $queryAgama
            ->select('agama', \DB::raw('count(*) as total'))
            ->groupBy('agama')
            ->orderBy('total', 'desc') // urutkan dari terbanyak
            ->pluck('total', 'agama')
            ->toArray();

        // 2. LOAD DATA KARYAWAN TANPA AGAMA
        $queryKosong = Karyawan::whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->where(function ($q) {
                $q->where('agama', '')
                    ->orWhereNull('agama');
            });

        // Filter berdasarkan placement jika dipilih
        if ($this->selectedPlacement) {
            $queryKosong->where('placement_id', $this->selectedPlacement);
        }

        $this->agama_kosong = $queryKosong
            ->orderBy('placement_id')
            ->orderBy('nama')
            ->get();
    }
    public function render()
    {
        return view('livewire.agamadetail');
    }
}
