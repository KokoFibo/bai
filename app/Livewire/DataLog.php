<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use App\Exports\ActivityLogExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DataLog extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $admin, $year, $month,  $admins, $years, $months;

    // public function excel(): BinaryFileResponse
    public function excel()
    {
        if (!$this->admin || !$this->month || !$this->year) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Admin, bulan, dan tahun harus dipilih',
                position: 'center'
            );
            return;
        }

        $fileName = 'log_perubahan_gaji_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ActivityLogExport($this->admin, $this->month, $this->year), $fileName);
    }
    public function proses()
    {
        if (!$this->admin || !$this->month || !$this->year) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Admin, bulan, dan tahun harus dipilih',
                position: 'center'
            );
            return;
        }
    }

    public function mount()
    {
        // $this->activity = Activity::whereIn('event', ['updated', 'deleted'])
        //     ->orderBy('updated_at', 'DESC')
        //     ->paginate(10);

        $this->admins = Activity::whereIn('event', ['updated', 'deleted'])
            ->whereNotNull('causer_id')
            ->distinct()
            ->pluck('causer_id')
            ->toArray();

        $this->months = Activity::whereIn('event', ['updated', 'deleted'])
            ->whereNotNull('updated_at')
            ->select(DB::raw('DISTINCT MONTH(updated_at) as month'))
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        $this->years = Activity::whereIn('event', ['updated', 'deleted'])
            ->whereNotNull('updated_at')
            ->select(DB::raw('DISTINCT YEAR(updated_at) as year'))
            ->orderBy('year')
            ->pluck('year')
            ->toArray();

        $this->year = $this->years[0] ?? now()->year;
        $this->month = $this->months[0] ?? now()->month;
    }

    public function render()
    {
        $query = Activity::whereIn('event', ['updated', 'deleted']);

        if ($this->admin && $this->month && $this->year) {
            $query->whereMonth('updated_at', $this->month)
                ->whereYear('updated_at', $this->year)
                ->where('causer_id', $this->admin);
        }

        $activity = $query->orderBy('updated_at', 'DESC')->paginate(10);

        return view('livewire.data-log', [
            'activity' => $activity
        ]);
    }
}
