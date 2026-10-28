<?php

namespace App\Livewire;

use App\Models\Karyawan;
use Livewire\Component;

class PendidikanStatsChart extends Component
{
    public array $labels = [];
    public array $totals = [];
    public array $percentages = [];
    public int $totalTerisi = 0;
    public int $totalKaryawanAktif = 0;

    // Urutan tetap sesuai jenjang pendidikan
    protected array $urutanPendidikan = [
        'Tidak Bersekolah',
        'SD',
        'SMP',
        'SMA/SMK',
        'D1',
        'D2',
        'D3',
        'D4',
        'S1',
        'S2',
        'S3',
    ];

    public function getColor($index)
    {
        $colors = [
            '#94a3b8',
            '#f97316',
            '#f59e0b',
            '#eab308',
            '#84cc16',
            '#22c55e',
            '#10b981',
            '#14b8a6',
            '#06b6d4',
            '#3b82f6',
            '#8b5cf6',
        ];

        return $colors[$index] ?? '#3b82f6';
    }

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $statusAktif = ['PKWT', 'PKWTT'];

        // Total karyawan aktif (dasar perhitungan)
        $this->totalKaryawanAktif = Karyawan::whereIn('status_karyawan', $statusAktif)->count();

        // Total karyawan aktif yang sudah mengisi data pendidikan
        $this->totalTerisi = Karyawan::whereIn('status_karyawan', $statusAktif)
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '!=', '')
            ->count();

        // Jumlah per jenjang pendidikan (karyawan aktif saja)
        $rawCounts = Karyawan::whereIn('status_karyawan', $statusAktif)
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '!=', '')
            ->selectRaw('pendidikan, COUNT(*) as jumlah')
            ->groupBy('pendidikan')
            ->pluck('jumlah', 'pendidikan');

        $labels = [];
        $totals = [];
        $percentages = [];

        foreach ($this->urutanPendidikan as $jenjang) {
            $jumlah = $rawCounts[$jenjang] ?? 0;

            $labels[] = $jenjang;
            $totals[] = $jumlah;
            $percentages[] = $this->totalTerisi > 0
                ? round(($jumlah / $this->totalTerisi) * 100, 1)
                : 0;
        }

        $this->labels = $labels;
        $this->totals = $totals;
        $this->percentages = $percentages;
    }

    public function render()
    {
        return view('livewire.pendidikan-stats-chart');
    }
}
