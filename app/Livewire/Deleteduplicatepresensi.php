<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Deleteduplicatepresensi extends Component
{
    public $year, $month;
    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }
    public function delete_duplikat()
    {
        $duplicates = DB::table('yfrekappresensis')
            ->select('user_id', 'date', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->groupBy('user_id', 'date')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            // Delete all records with the same user_id and date, except the one with keep_id
            DB::table('yfrekappresensis')
                ->where('user_id', $duplicate->user_id)
                ->where('date', $duplicate->date)
                ->where('id', '!=', $duplicate->keep_id) // Keep the record with the smallest id
                ->delete();
        }
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Duplikat Berhasil di delete',
            position: 'center'
        );
    }
    public function search_duplicate()
    {
        $duplicates = DB::table('yfrekappresensis')
            ->select('user_id', 'date', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->groupBy('user_id', 'date')
            ->having('count', '>', 1)
            ->get();

        $duplicates_count = DB::table('yfrekappresensis')
            // ->select('user_id', 'date', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->select('user_id', 'date', DB::raw('COUNT(*) as count'))
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->groupBy('user_id', 'date')
            ->having('count', '>', 1)
            ->count();
    }
    public function render()
    {
        $duplicates = DB::table('yfrekappresensis')
            ->select('user_id', 'date', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->groupBy('user_id', 'date')
            ->having('count', '>', 1)
            ->get();

        $duplicates_count = DB::table('yfrekappresensis')
            // ->select('user_id', 'date', DB::raw('COUNT(*) as count'), DB::raw('MIN(id) as keep_id'))
            ->select('user_id', 'date', DB::raw('COUNT(*) as count'))
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->groupBy('user_id', 'date')
            ->having('count', '>', 1)
            ->count();
        return view('livewire.deleteduplicatepresensi', [
            'duplicates' => $duplicates,
            'duplicates_count' => $duplicates_count
        ]);
    }
}
